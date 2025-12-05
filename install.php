<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BMCODEX - Instalacja</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #1A1A1A 0%, #2d2d2d 100%);
            color: #fff;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .container {
            background: #2d2d2d;
            border-radius: 12px;
            padding: 40px;
            max-width: 600px;
            width: 100%;
            box-shadow: 0 10px 40px rgba(0,0,0,0.5);
            border: 2px solid #FF4500;
        }
        h1 {
            color: #FF4500;
            margin-bottom: 10px;
            font-size: 32px;
        }
        .tagline {
            color: #999;
            margin-bottom: 30px;
            font-size: 14px;
        }
        .step {
            background: #1A1A1A;
            padding: 20px;
            margin: 15px 0;
            border-radius: 8px;
            border-left: 4px solid #FF4500;
        }
        .step.success { border-left-color: #4CAF50; }
        .step.error { border-left-color: #f44336; }
        .step h3 {
            color: #FF4500;
            margin-bottom: 10px;
            font-size: 18px;
        }
        .step.success h3 { color: #4CAF50; }
        .step.error h3 { color: #f44336; }
        .step p {
            color: #ccc;
            line-height: 1.6;
            font-size: 14px;
        }
        .btn {
            background: #FF4500;
            color: #fff;
            border: none;
            padding: 15px 40px;
            border-radius: 6px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            margin-top: 20px;
            transition: all 0.3s;
            display: inline-block;
            text-decoration: none;
        }
        .btn:hover {
            background: #ff5722;
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(255, 69, 0, 0.4);
        }
        .btn:disabled {
            background: #666;
            cursor: not-allowed;
            transform: none;
        }
        .loading {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid #fff;
            border-radius: 50%;
            border-top-color: transparent;
            animation: spin 1s linear infinite;
            margin-right: 10px;
        }
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        pre {
            background: #000;
            padding: 15px;
            border-radius: 6px;
            overflow-x: auto;
            margin: 10px 0;
            font-size: 12px;
            color: #0f0;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🚗 BMCODEX</h1>
        <p class="tagline">Performance Without Limits</p>

<?php
if (!isset($_GET['run'])) {
    ?>
        <div class="step">
            <h3>Gotowy do instalacji?</h3>
            <p>Ten skrypt automatycznie:</p>
            <ul style="margin: 15px 0 15px 20px; color: #ccc;">
                <li>Uruchomi migracje bazy danych</li>
                <li>Wypełni bazę przykładowymi danymi (produkty, kategorie, użytkownicy)</li>
                <li>Wyczyści cache aplikacji</li>
                <li>Usunie sam siebie po zakończeniu</li>
            </ul>
            <p style="margin-top: 15px;"><strong>Uwaga:</strong> Upewnij się, że plik <code>.env</code> jest poprawnie skonfigurowany.</p>
        </div>
        <a href="?run=1" class="btn">Rozpocznij instalację</a>
    <?php
    exit;
}

// Rozpocznij instalację
set_time_limit(300);
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo '<div class="step"><h3><span class="loading"></span>Instalacja w toku...</h3></div>';
flush();

try {
    // Sprawdź czy Laravel jest załadowany
    if (!file_exists(__DIR__.'/vendor/autoload.php')) {
        throw new Exception('Brak katalogu vendor/. Upewnij się, że projekt został poprawnie przesłany.');
    }

    define('LARAVEL_START', microtime(true));
    require __DIR__.'/vendor/autoload.php';
    $app = require_once __DIR__.'/bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

    // Krok 1: Migracje
    echo '<div class="step">';
    echo '<h3>📊 Uruchamianie migracji bazy danych...</h3>';
    flush();
    
    ob_start();
    $status = $kernel->call('migrate', ['--force' => true]);
    $output = ob_get_clean();
    
    if ($status === 0) {
        echo '<p class="success" style="color: #4CAF50;">✅ Migracje wykonane pomyślnie!</p>';
        if ($output) echo '<pre>' . htmlspecialchars($output) . '</pre>';
    } else {
        throw new Exception('Błąd podczas migracji: ' . $output);
    }
    echo '</div>';
    flush();

    // Krok 2: Seedery
    echo '<div class="step">';
    echo '<h3>🌱 Wypełnianie bazy danymi testowymi...</h3>';
    flush();
    
    ob_start();
    $status = $kernel->call('db:seed', ['--force' => true]);
    $output = ob_get_clean();
    
    if ($status === 0) {
        echo '<p style="color: #4CAF50;">✅ Dane testowe dodane!</p>';
        echo '<ul style="margin: 10px 0 10px 20px; color: #ccc;">';
        echo '<li>Kategorie produktów: Turbosprężarki, Wydech, Zawieszenie, Hamulce, Elektronika</li>';
        echo '<li>Produkty: 15 przykładowych części do tuningu</li>';
        echo '<li>Użytkownicy: admin@bmcodex.com (hasło: admin123), customer@example.com (hasło: customer123)</li>';
        echo '</ul>';
        if ($output) echo '<pre>' . htmlspecialchars($output) . '</pre>';
    } else {
        echo '<p style="color: #ff9800;">⚠️ Ostrzeżenie: Seedery mogły nie zostać w pełni wykonane.</p>';
        if ($output) echo '<pre>' . htmlspecialchars($output) . '</pre>';
    }
    echo '</div>';
    flush();

    // Krok 3: Cache
    echo '<div class="step">';
    echo '<h3>🧹 Czyszczenie cache...</h3>';
    flush();
    
    $kernel->call('config:clear');
    $kernel->call('cache:clear');
    $kernel->call('route:clear');
    $kernel->call('view:clear');
    
    echo '<p style="color: #4CAF50;">✅ Cache wyczyszczony!</p>';
    echo '</div>';
    flush();

    // Sukces!
    echo '<div class="step success">';
    echo '<h3>🎉 Instalacja zakończona pomyślnie!</h3>';
    echo '<p>Sklep BMCODEX jest gotowy do użycia!</p>';
    echo '<p style="margin-top: 15px;"><strong>Dane logowania:</strong></p>';
    echo '<ul style="margin: 10px 0 10px 20px;">';
    echo '<li><strong>Administrator:</strong> admin@bmcodex.com / admin123</li>';
    echo '<li><strong>Klient testowy:</strong> customer@example.com / customer123</li>';
    echo '</ul>';
    echo '<p style="margin-top: 15px;">Ten skrypt zostanie automatycznie usunięty za 5 sekund...</p>';
    echo '</div>';
    
    // Usuń skrypt instalacyjny
    echo '<script>setTimeout(function() { window.location.href = "delete_install.php"; }, 5000);</script>';
    
    // Utwórz skrypt do usunięcia
    $deleteScript = '<?php unlink(__DIR__."/install.php"); unlink(__FILE__); header("Location: /"); exit; ?>';
    file_put_contents(__DIR__.'/delete_install.php', $deleteScript);
    
    echo '<a href="/" class="btn">Przejdź do sklepu</a>';
    
} catch (Exception $e) {
    echo '<div class="step error">';
    echo '<h3>❌ Błąd instalacji</h3>';
    echo '<p>' . htmlspecialchars($e->getMessage()) . '</p>';
    echo '<pre>' . htmlspecialchars($e->getTraceAsString()) . '</pre>';
    echo '</div>';
    echo '<a href="?run=1" class="btn">Spróbuj ponownie</a>';
}
?>

    </div>
</body>
</html>
