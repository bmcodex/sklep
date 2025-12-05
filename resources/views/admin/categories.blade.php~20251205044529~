@extends('layouts.app')

@section('title', 'Zarządzanie kategoriami - Panel Admin')

@section('content')
<div class="container" style="margin-top: 2rem;">
    <h1 style="margin-bottom: 2rem; color: var(--primary-color);">Zarządzanie kategoriami</h1>

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
                    <th style="padding: 1rem; text-align: left; color: var(--primary-color);">Nazwa</th>
                    <th style="padding: 1rem; text-align: left; color: var(--primary-color);">Slug</th>
                    <th style="padding: 1rem; text-align: left; color: var(--primary-color);">Liczba produktów</th>
                    <th style="padding: 1rem; text-align: left; color: var(--primary-color);">Akcje</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $category)
                    <tr style="border-bottom: 1px solid #333;">
                        <td style="padding: 1rem; color: var(--text-light);">#{{ $category->id }}</td>
                        <td style="padding: 1rem; color: var(--text-light);">{{ $category->name }}</td>
                        <td style="padding: 1rem; color: #999;">{{ $category->slug }}</td>
                        <td style="padding: 1rem; color: var(--primary-color); font-weight: 700;">{{ $category->products_count }}</td>
                        <td style="padding: 1rem;">
                            <button onclick="toggleEdit({{ $category->id }})" class="btn btn-secondary" style="padding: 0.5rem 1rem; margin-right: 0.5rem;">
                                Edytuj
                            </button>
                            @if($category->products_count == 0)
                                <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" style="display: inline;" onsubmit="return confirm('Czy na pewno chcesz usunąć tę kategorię?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn" style="padding: 0.5rem 1rem; background-color: #dc3545; color: white;">
                                        Usuń
                                    </button>
                                </form>
                            @else
                                <span style="color: #999; font-size: 0.9rem;">Zawiera produkty</span>
                            @endif
                        </td>
                    </tr>
                    <tr id="edit-{{ $category->id }}" style="display: none; background-color: var(--dark-bg);">
                        <td colspan="5" style="padding: 1.5rem;">
                            <form action="{{ route('admin.categories.update', $category) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                                    <div>
                                        <label style="color: var(--text-light); display: block; margin-bottom: 0.5rem;">Nazwa</label>
                                        <input type="text" name="name" value="{{ $category->name }}" required
                                            style="width: 100%; padding: 0.5rem; background-color: var(--light-gray); color: var(--text-light); border: 1px solid var(--primary-color); border-radius: 4px;">
                                    </div>
                                    <div>
                                        <label style="color: var(--text-light); display: block; margin-bottom: 0.5rem;">Slug</label>
                                        <input type="text" name="slug" value="{{ $category->slug }}" required
                                            style="width: 100%; padding: 0.5rem; background-color: var(--light-gray); color: var(--text-light); border: 1px solid var(--primary-color); border-radius: 4px;">
                                    </div>
                                </div>
                                <div style="margin-top: 1rem;">
                                    <button type="submit" class="btn btn-primary" style="padding: 0.7rem 2rem;">
                                        Zapisz zmiany
                                    </button>
                                    <button type="button" onclick="toggleEdit({{ $category->id }})" class="btn btn-secondary" style="padding: 0.7rem 2rem; margin-left: 0.5rem;">
                                        Anuluj
                                    </button>
                                </div>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="padding: 2rem; text-align: center; color: #999;">
                            Brak kategorii
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
function toggleEdit(categoryId) {
    const row = document.getElementById('edit-' + categoryId);
    row.style.display = row.style.display === 'none' ? 'table-row' : 'none';
}
</script>
@endsection
