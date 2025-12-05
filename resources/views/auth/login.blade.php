@extends('layouts.app')

@section('title', 'Logowanie - BMCODEX')

@section('styles')
<style>
    .auth-container {
        max-width: 500px;
        margin: 3rem auto;
        background-color: var(--light-gray);
        padding: 3rem;
        border-radius: 8px;
        border: 2px solid var(--primary-color);
    }
    
    .auth-header {
        text-align: center;
        margin-bottom: 2rem;
    }
    
    .auth-header h1 {
        font-size: 2rem;
        margin-bottom: 0.5rem;
    }
    
    .form-group {
        margin-bottom: 1.5rem;
    }
    
    .form-group label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 600;
    }
    
    .form-group input {
        width: 100%;
        padding: 0.8rem;
        background-color: var(--dark-bg);
        border: 1px solid var(--primary-color);
        color: var(--text-light);
        border-radius: 4px;
        font-size: 1rem;
    }
    
    .form-group input:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(255, 69, 0, 0.1);
    }
    
    .form-actions {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }
    
    .form-footer {
        text-align: center;
        margin-top: 2rem;
        padding-top: 2rem;
        border-top: 1px solid var(--dark-bg);
    }
    
    .form-footer a {
        color: var(--primary-color);
        text-decoration: none;
    }
    
    .form-footer a:hover {
        text-decoration: underline;
    }
</style>
@endsection

@section('content')
<div class="auth-container">
    <div class="auth-header">
        <h1>Logowanie</h1>
        <p style="color: var(--text-gray);">Zaloguj się do swojego konta</p>
    </div>
    
    <form action="{{ route('login.store') }}" method="POST">
        @csrf
        
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus>
        </div>
        
        <div class="form-group">
            <label for="password">Hasło</label>
            <input type="password" id="password" name="password" required>
        </div>
        
        <div class="form-group">
            <label style="display: flex; align-items: center; gap: 0.5rem;">
                <input type="checkbox" name="remember" style="width: auto;">
                <span>Zapamiętaj mnie</span>
            </label>
        </div>
        
        <div class="form-actions">
            <button type="submit" class="btn btn-primary" style="width: 100%; padding: 1rem;">
                Zaloguj się
            </button>
        </div>
    </form>
    
    <div class="form-footer">
        <p style="color: var(--text-gray); margin-bottom: 1rem;">
            <a href="{{ route('password.request') }}">Zapomniałeś hasła?</a>
        </p>
        <p style="color: var(--text-gray);">
            Nie masz konta? <a href="{{ route('register') }}">Zarejestruj się</a>
        </p>
    </div>
</div>
@endsection
