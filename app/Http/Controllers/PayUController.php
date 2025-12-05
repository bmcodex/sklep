<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Order;

class PayUController extends Controller
{
    private $posId;
    private $secondKey;
    private $clientId;
    private $clientSecret;
    private $apiUrl;
    
    public function __construct()
    {
        // Dane testowe PayU Sandbox
        $this->posId = env('PAYU_POS_ID', '300746');
        $this->secondKey = env('PAYU_SECOND_KEY', 'b6ca15b0d1020e8094d9b5f8d163db54');
        $this->clientId = env('PAYU_CLIENT_ID', '300746');
        $this->clientSecret = env('PAYU_CLIENT_SECRET', '2ee86a66e5d97e3fadc400c9f19b065d');
        $this->apiUrl = env('PAYU_API_URL', 'https://secure.snd.payu.com');
    }
    
    /**
     * Utworzenie płatności PayU
     */
    public function createPayment(Request $request)
    {
        try {
            // Pobierz zamówienie
            $orderId = $request->input('order_id');
            $order = Order::findOrFail($orderId);
            
            // Pobierz token OAuth
            $token = $this->getOAuthToken();
            
            if (!$token) {
                return redirect()->back()->with('error', 'Nie udało się połączyć z PayU');
            }
            
            // Przygotuj dane zamówienia
            $orderData = [
                'notifyUrl' => route('payu.notify'),
                'customerIp' => $request->ip(),
                'merchantPosId' => $this->posId,
                'description' => 'Zamówienie #' . $order->id . ' - BMCODEX',
                'currencyCode' => 'PLN',
                'totalAmount' => (int)($order->total_amount * 100), // kwota w groszach
                'buyer' => [
                    'email' => $order->user->email,
                    'phone' => $order->phone ?? '',
                    'firstName' => $order->first_name,
                    'lastName' => $order->last_name,
                    'language' => 'pl'
                ],
                'products' => $this->getOrderProducts($order)
            ];
            
            // Wyślij żądanie do PayU
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token,
                'Content-Type' => 'application/json'
            ])->post($this->apiUrl . '/api/v2_1/orders', $orderData);
            
            if ($response->successful()) {
                $data = $response->json();
                
                // Zapisz ID transakcji PayU
                $order->update([
                    'payment_id' => $data['orderId'],
                    'payment_status' => 'pending'
                ]);
                
                // Przekieruj do PayU
                return redirect($data['redirectUri']);
            } else {
                Log::error('PayU Error:', $response->json());
                return redirect()->back()->with('error', 'Błąd podczas tworzenia płatności');
            }
            
        } catch (\Exception $e) {
            Log::error('PayU Exception: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Wystąpił błąd: ' . $e->getMessage());
        }
    }
    
    /**
     * Obsługa powiadomień z PayU (webhook)
     */
    public function notify(Request $request)
    {
        try {
            // Pobierz dane z PayU
            $body = $request->getContent();
            $headers = $request->headers->all();
            
            // Weryfikacja podpisu
            if (!$this->verifySignature($body, $headers)) {
                Log::error('PayU: Invalid signature');
                return response('Invalid signature', 400);
            }
            
            $data = json_decode($body, true);
            $order = $data['order'];
            
            // Znajdź zamówienie w bazie
            $localOrder = Order::where('payment_id', $order['orderId'])->first();
            
            if (!$localOrder) {
                Log::error('PayU: Order not found: ' . $order['orderId']);
                return response('Order not found', 404);
            }
            
            // Aktualizuj status płatności
            $status = $order['status'];
            
            switch ($status) {
                case 'COMPLETED':
                    $localOrder->update([
                        'payment_status' => 'paid',
                        'status' => 'processing'
                    ]);
                    break;
                    
                case 'CANCELED':
                    $localOrder->update([
                        'payment_status' => 'cancelled'
                    ]);
                    break;
                    
                case 'PENDING':
                    $localOrder->update([
                        'payment_status' => 'pending'
                    ]);
                    break;
            }
            
            return response('OK', 200);
            
        } catch (\Exception $e) {
            Log::error('PayU Notify Exception: ' . $e->getMessage());
            return response('Error', 500);
        }
    }
    
    /**
     * Strona powrotu z PayU
     */
    public function callback(Request $request)
    {
        $orderId = $request->input('order_id');
        $order = Order::where('payment_id', $orderId)->first();
        
        if (!$order) {
            return redirect()->route('home')->with('error', 'Zamówienie nie zostało znalezione');
        }
        
        if ($order->payment_status === 'paid') {
            return redirect()->route('order.confirmation', $order->id)
                ->with('success', 'Płatność została zrealizowana pomyślnie!');
        } else {
            return redirect()->route('cart.index')
                ->with('error', 'Płatność nie została zrealizowana');
        }
    }
    
    /**
     * Pobierz token OAuth z PayU
     */
    private function getOAuthToken()
    {
        try {
            $response = Http::asForm()->post($this->apiUrl . '/pl/standard/user/oauth/authorize', [
                'grant_type' => 'client_credentials',
                'client_id' => $this->clientId,
                'client_secret' => $this->clientSecret
            ]);
            
            if ($response->successful()) {
                return $response->json()['access_token'];
            }
            
            return null;
            
        } catch (\Exception $e) {
            Log::error('PayU OAuth Error: ' . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Pobierz produkty z zamówienia
     */
    private function getOrderProducts(Order $order)
    {
        $products = [];
        
        foreach ($order->items as $item) {
            $products[] = [
                'name' => $item->product->name,
                'unitPrice' => (int)($item->price * 100),
                'quantity' => $item->quantity
            ];
        }
        
        return $products;
    }
    
    /**
     * Weryfikacja podpisu PayU
     */
    private function verifySignature($body, $headers)
    {
        if (!isset($headers['openpayu-signature'][0])) {
            return false;
        }
        
        $signature = $headers['openpayu-signature'][0];
        $signatureParts = explode(';', $signature);
        $signatureData = [];
        
        foreach ($signatureParts as $part) {
            list($key, $value) = explode('=', $part);
            $signatureData[$key] = $value;
        }
        
        $calculatedSignature = hash('sha256', $body . $this->secondKey);
        
        return $calculatedSignature === $signatureData['signature'];
    }
}
