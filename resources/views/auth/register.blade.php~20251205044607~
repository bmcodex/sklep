@extends('layouts.app')

@section('title', 'Rejestracja - BMCODEX')

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
    
    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
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
        <h1>Rejestracja</h1>
        <p style="color: var(--text-gray);">Utwórz nowe konto w BMCODEX</p>
    </div>
    
    <form action="{{ route('register.store') }}" method="POST">
        @csrf
        
        <div class="form-row">
            <div class="form-group">
                <label for="first_name">Imię</label>
                <input type="text" id="first_name" name="first_name" value="{{ old('first_name') }}" required autofocus>
            </div>
            
            <div class="form-group">
                <label for="last_name">Nazwisko</label>
                <input type="text" id="last_name" name="last_name" value="{{ old('last_name') }}" required>
            </div>
        </div>
        
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required>
        </div>
        
        <div class="form-group">
            <label for="phone">Telefon</label>
            <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" placeholder="+48 123 456 789">
        </div>
        
        <div class="form-group">
            <label for="password">Hasło</label>
            <input type="password" id="password" name="password" required>
            <small style="color: var(--text-gray);">Minimum 8 znaków</small>
        </div>
        
        <div class="form-group">
            <label for="password_confirmation">Potwierdź hasło</label>
            <input type="password" id="password_confirmation" name="password_confirmation" required>
        </div>
        
        <div class="form-group">
            <label style="display: flex; align-items: center; gap: 0.5rem;">
                <input type="checkbox" name="terms" style="width: auto;" required>
                <span>Akceptuję <a href="#" style="color: var(--primary-color);">regulamin</a> i <a href="#" style="color: var(--primary-color);">politykę prywatności</a></span>
            </label>
        </div>
        
        <div class="form-actions">
            <button type="submit" class="btn btn-primary" style="width: 100%; padding: 1rem;">
                Zarejestruj się
            </button>
        </div>
    </form>
    
    <div class="form-footer">
        <p style="color: var(--text-gray);">
            Masz już konto? <a href="{{ route('login') }}">Zaloguj się</a>
        </p>
    </div>
</div>
@endsection
