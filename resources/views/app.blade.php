<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'BMCODEX - Performance Without Limits')</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@400;600;700&family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        :root {
            --primary-color: #FF4500;
            --dark-bg: #1A1A1A;
            --light-gray: #2A2A2A;
            --text-light: #FFFFFF;
            --text-gray: #CCCCCC;
        }
        
        body {
            font-family: 'Roboto', sans-serif;
            background-color: var(--dark-bg);
            color: var(--text-light);
            line-height: 1.6;
        }
        
        h1, h2, h3, h4, h5, h6 {
            font-family: 'Rajdhani', sans-serif;
            font-weight: 700;
            letter-spacing: 1px;
        }
        
        /* Header & Navigation */
        header {
            background-color: var(--dark-bg);
            border-bottom: 2px solid var(--primary-color);
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        
        nav {
            max-width: 1200px;
            margin: 0 auto;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .logo {
            font-family: 'Rajdhani', sans-serif;
            font-size: 2rem;
            font-weight: 700;
            color: var(--text-light);
            text-decoration: none;
            letter-spacing: 2px;
        }
        
        .logo span {
            color: var(--primary-color);
        }
        
        .nav-links {
            display: flex;
            list-style: none;
            gap: 2rem;
            align-items: center;
        }
        
        .nav-links a {
            color: var(--text-light);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s;
            position: relative;
        }
        
        .nav-links a:hover {
            color: var(--primary-color);
        }
        
        .nav-links a::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 0;
            width: 0;
            height: 2px;
            background-color: var(--primary-color);
            transition: width 0.3s;
        }
        
        .nav-links a:hover::after {
            width: 100%;
        }
        
        .btn {
            padding: 0.6rem 1.5rem;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s;
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            color: var(--text-light);
        }
        
        .btn-primary:hover {
            background-color: #E63E00;
            transform: translateY(-2px);
        }
        
        .btn-secondary {
            background-color: transparent;
            color: var(--text-light);
            border: 2px solid var(--primary-color);
        }
        
        .btn-secondary:hover {
            background-color: var(--primary-color);
        }
        
        /* Container */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }
        
        /* Footer */
        footer {
            background-color: var(--light-gray);
            color: var(--text-gray);
            text-align: center;
            padding: 2rem;
            margin-top: 4rem;
            border-top: 2px solid var(--primary-color);
        }
        
        /* Cart Badge */
        .cart-badge {
            background-color: var(--primary-color);
            color: var(--text-light);
            border-radius: 50%;
            padding: 0.2rem 0.5rem;
            font-size: 0.8rem;
            position: absolute;
            top: -8px;
            right: -8px;
        }
        
        .cart-icon {
            position: relative;
        }
        
        /* Flash Messages */
        .alert {
            padding: 1rem;
            margin-bottom: 1rem;
            border-radius: 4px;
            font-weight: 500;
        }
        
        .alert-success {
            background-color: #28a745;
            color: white;
        }
        
        .alert-error {
            background-color: #dc3545;
            color: white;
        }
        
        .alert-info {
            background-color: #17a2b8;
            color: white;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            nav {
                flex-direction: column;
                gap: 1rem;
                padding: 1rem;
            }
            
            .logo {
                font-size: 1.5rem;
            }
            
            .nav-links {
                flex-direction: column;
                gap: 0.8rem;
                width: 100%;
                text-align: center;
            }
            
            .container {
                padding: 1rem;
            }
            
            .btn {
                padding: 0.5rem 1rem;
                font-size: 0.9rem;
            }
            
            h1 {
                font-size: 1.8rem;
            }
            
            h2 {
                font-size: 1.5rem;
            }
            
            h3 {
                font-size: 1.2rem;
            }
        }
        
        @media (max-width: 480px) {
            .logo {
                font-size: 1.3rem;
            }
            
            .container {
                padding: 0.5rem;
            }
            
            h1 {
                font-size: 1.5rem;
            }
            
            h2 {
                font-size: 1.3rem;
            }
        }
    </style>
    
    @yield('styles')
</head>
<body>
    <header>
        <nav>
            <a href="{{ route('home') }}" class="logo">
                BMC<span>ODEX</span>
            </a>
            
            <ul class="nav-links">
                <li><a href="{{ route('home') }}">Strona główna</a></li>
                <li><a href="{{ route('products.index') }}">Produkty</a></li>
                
                @auth
                    @if(auth()->user()->isAdmin())
                        <li><a href="{{ route('admin.dashboard') }}">Panel Admin</a></li>
                    @endif
                    <li><a href="{{ route('home') }}">Moje konto</a></li>
                    <li>
                        <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                            @csrf
                            <button type="submit" class="btn btn-secondary">Wyloguj</button>
                        </form>
                    </li>
                @else
                    <li><a href="{{ route('login') }}">Logowanie</a></li>
                    <li><a href="{{ route('register') }}" class="btn btn-primary">Rejestracja</a></li>
                @endauth
                
                <li class="cart-icon">
                    <a href="{{ route('cart.index') }}">🛒 Koszyk</a>
                    @if(session('cart_count', 0) > 0)
                        <span class="cart-badge">{{ session('cart_count') }}</span>
                    @endif
                </li>
            </ul>
        </nav>
    </header>
    
    <main class="container">
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        
        @if(session('error'))
            <div class="alert alert-error">
                {{ session('error') }}
            </div>
        @endif
        
        @if($errors->any())
            <div class="alert alert-error">
                <ul style="list-style: none;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        @yield('content')
    </main>
    
    <footer>
        <p>&copy; 2025 BMCODEX - Performance Without Limits</p>
        <p>Wszystkie prawa zastrzeżone | Michał Nurzyński</p>
    </footer>
    
    @yield('scripts')
</body>
</html>
