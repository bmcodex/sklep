@extends('layouts.app')

@section('title', 'Zarządzanie użytkownikami - Panel Admin')

@section('content')
<div class="container" style="margin-top: 2rem;">
    <h1 style="margin-bottom: 2rem; color: var(--primary-color);">Zarządzanie użytkownikami</h1>

    @if(session('success'))
        <div style="background-color: #28a745; color: white; padding: 1rem; border-radius: 4px; margin-bottom: 1rem;">
            {{ session('success') }}
        </div>
    @endif

    <div style="background-color: var(--light-gray); padding: 2rem; border-radius: 8px;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="border-bottom: 2px solid var(--primary-color);">
                    <th style="padding: 1rem; text-align: left; color: var(--primary-color);">ID</th>
                    <th style="padding: 1rem; text-align: left; color: var(--primary-color);">Imię i nazwisko</th>
                    <th style="padding: 1rem; text-align: left; color: var(--primary-color);">Email</th>
                    <th style="padding: 1rem; text-align: left; color: var(--primary-color);">Telefon</th>
                    <th style="padding: 1rem; text-align: left; color: var(--primary-color);">Rola</th>
                    <th style="padding: 1rem; text-align: left; color: var(--primary-color);">Data rejestracji</th>
                    <th style="padding: 1rem; text-align: left; color: var(--primary-color);">Akcje</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    <tr style="border-bottom: 1px solid #333;">
                        <td style="padding: 1rem; color: var(--text-light);">#{{ $user->id }}</td>
                        <td style="padding: 1rem; color: var(--text-light);">{{ $user->first_name }} {{ $user->last_name }}</td>
                        <td style="padding: 1rem; color: #999;">{{ $user->email }}</td>
                        <td style="padding: 1rem; color: #999;">{{ $user->phone ?? '-' }}</td>
                        <td style="padding: 1rem;">
                            <form action="{{ route('admin.users.updateRole', $user) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('PATCH')
                                <select name="role" onchange="this.form.submit()"
                                    style="padding: 0.5rem; background-color: var(--dark-bg); color: var(--text-light); border: 1px solid var(--primary-color); border-radius: 4px;">
                                    <option value="user" {{ $user->role === 'user' ? 'selected' : '' }}>Użytkownik</option>
                                    <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Administrator</option>
                                </select>
                            </form>
                        </td>
                        <td style="padding: 1rem; color: #999;">{{ $user->created_at->format('d.m.Y') }}</td>
                        <td style="padding: 1rem;">
                            @if($user->id !== auth()->id())
                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" style="display: inline;" onsubmit="return confirm('Czy na pewno chcesz usunąć tego użytkownika?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn" style="padding: 0.5rem 1rem; background-color: #dc3545; color: white;">
                                        Usuń
                                    </button>
                                </form>
                            @else
                                <span style="color: #999; font-size: 0.9rem;">To Ty</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="padding: 2rem; text-align: center; color: #999;">
                            Brak użytkowników
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div style="margin-top: 2rem;">
            {{ $users->links() }}
        </div>
    </div>
</div>
@endsection
