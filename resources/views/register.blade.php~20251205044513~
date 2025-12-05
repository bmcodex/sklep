@extends('layouts.app')

@section('title', 'Rejestracja - BMCODEX')

@section('content')
<div class="container" style="max-width: 600px; margin: 4rem auto;">
    <div style="background-color: var(--light-gray); padding: 3rem; border-radius: 8px; border: 2px solid var(--primary-color);">
        <h1 style="font-size: 2.5rem; margin-bottom: 2rem; color: var(--text-light); text-align: center;">
            Rejestracja
        </h1>

        <form action="{{ route('register.store') }}" method="POST">
            @csrf

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.5rem;">
                <div>
                    <label style="display: block; margin-bottom: 0.5rem; color: var(--text-light); font-weight: 600;">Imię *</label>
                    <input type="text" name="first_name" value="{{ old('first_name') }}" required 
                        style="width: 100%; padding: 0.8rem; background-color: var(--dark-bg); color: var(--text-light); border: 2px solid var(--primary-color); border-radius: 4px; font-size: 1rem;">
                    @error('first_name')
                        <span style="color: #dc3545; font-size: 0.9rem; margin-top: 0.3rem; display: block;">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label style="display: block; margin-bottom: 0.5rem; color: var(--text-light); font-weight: 600;">Nazwisko *</label>
                    <input type="text" name="last_name" value="{{ old('last_name') }}" required 
                        style="width: 100%; padding: 0.8rem; background-color: var(--dark-bg); color: var(--text-light); border: 2px solid var(--primary-color); border-radius: 4px; font-size: 1rem;">
                    @error('last_name')
                        <span style="color: #dc3545; font-size: 0.9rem; margin-top: 0.3rem; display: block;">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; margin-bottom: 0.5rem; color: var(--text-light); font-weight: 600;">Email *</label>
                <input type="email" name="email" value="{{ old('email') }}" required 
                    style="width: 100%; padding: 0.8rem; background-color: var(--dark-bg); color: var(--text-light); border: 2px solid var(--primary-color); border-radius: 4px; font-size: 1rem;">
                @error('email')
                    <span style="color: #dc3545; font-size: 0.9rem; margin-top: 0.3rem; display: block;">{{ $message }}</span>
                @enderror
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; margin-bottom: 0.5rem; color: var(--text-light); font-weight: 600;">Telefon</label>
                <input type="tel" name="phone" value="{{ old('phone') }}" 
                    style="width: 100%; padding: 0.8rem; background-color: var(--dark-bg); color: var(--text-light); border: 2px solid var(--primary-color); border-radius: 4px; font-size: 1rem;">
                @error('phone')
                    <span style="color: #dc3545; font-size: 0.9rem; margin-top: 0.3rem; display: block;">{{ $message }}</span>
                @enderror
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; margin-bottom: 0.5rem; color: var(--text-light); font-weight: 600;">Hasło *</label>
                <input type="password" name="password" required 
                    style="width: 100%; padding: 0.8rem; background-color: var(--dark-bg); color: var(--text-light); border: 2px solid var(--primary-color); border-radius: 4px; font-size: 1rem;">
                @error('password')
                    <span style="color: #dc3545; font-size: 0.9rem; margin-top: 0.3rem; display: block;">{{ $message }}</span>
                @enderror
                <small style="color: #999; font-size: 0.85rem; margin-top: 0.3rem; display: block;">Minimum 8 znaków</small>
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; margin-bottom: 0.5rem; color: var(--text-light); font-weight: 600;">Potwierdź hasło *</label>
                <input type="password" name="password_confirmation" required 
                    style="width: 100%; padding: 0.8rem; background-color: var(--dark-bg); color: var(--text-light); border: 2px solid var(--primary-color); border-radius: 4px; font-size: 1rem;">
            </div>

            <button type="submit" 
                style="width: 100%; padding: 1rem; background-color: var(--primary-color); color: var(--dark-bg); border: none; border-radius: 4px; font-size: 1.2rem; font-weight: 700; cursor: pointer; transition: all 0.3s ease; margin-bottom: 1rem;">
                Zarejestruj się
            </button>

            <div style="text-align: center; color: var(--text-light);">
                Masz już konto? 
                <a href="{{ route('login') }}" style="color: var(--primary-color); font-weight: 600; text-decoration: none;">
                    Zaloguj się
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
