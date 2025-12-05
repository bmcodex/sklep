@extends('layouts.app')

@section('title', 'Logowanie - BMCODEX')

@section('content')
<div class="container" style="max-width: 500px; margin: 4rem auto;">
    <div style="background-color: var(--light-gray); padding: 3rem; border-radius: 8px; border: 2px solid var(--primary-color);">
        <h1 style="font-size: 2.5rem; margin-bottom: 2rem; color: var(--text-light); text-align: center;">
            Logowanie
        </h1>

        <form action="{{ route('login.store') }}" method="POST">
            @csrf

            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; margin-bottom: 0.5rem; color: var(--text-light); font-weight: 600;">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required autofocus 
                    style="width: 100%; padding: 0.8rem; background-color: var(--dark-bg); color: var(--text-light); border: 2px solid var(--primary-color); border-radius: 4px; font-size: 1rem;">
                @error('email')
                    <span style="color: #dc3545; font-size: 0.9rem; margin-top: 0.3rem; display: block;">{{ $message }}</span>
                @enderror
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; margin-bottom: 0.5rem; color: var(--text-light); font-weight: 600;">Hasło</label>
                <input type="password" name="password" required 
                    style="width: 100%; padding: 0.8rem; background-color: var(--dark-bg); color: var(--text-light); border: 2px solid var(--primary-color); border-radius: 4px; font-size: 1rem;">
                @error('password')
                    <span style="color: #dc3545; font-size: 0.9rem; margin-top: 0.3rem; display: block;">{{ $message }}</span>
                @enderror
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label style="display: flex; align-items: center; color: var(--text-light); cursor: pointer;">
                    <input type="checkbox" name="remember" style="margin-right: 0.5rem;">
                    Zapamiętaj mnie
                </label>
            </div>

            <button type="submit" 
                style="width: 100%; padding: 1rem; background-color: var(--primary-color); color: var(--dark-bg); border: none; border-radius: 4px; font-size: 1.2rem; font-weight: 700; cursor: pointer; transition: all 0.3s ease; margin-bottom: 1rem;">
                Zaloguj się
            </button>

            <div style="text-align: center; color: var(--text-light);">
                Nie masz konta? 
                <a href="{{ route('register') }}" style="color: var(--primary-color); font-weight: 600; text-decoration: none;">
                    Zarejestruj się
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
