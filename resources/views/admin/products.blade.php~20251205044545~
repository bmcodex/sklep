@extends('layouts.app')

@section('title', 'Zarządzanie produktami - Panel Admin')

@section('content')
<div class="container" style="margin-top: 2rem;">
    <h1 style="margin-bottom: 2rem; color: var(--primary-color);">Zarządzanie produktami</h1>

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
                    <th style="padding: 1rem; text-align: left; color: var(--primary-color);">Kategoria</th>
                    <th style="padding: 1rem; text-align: left; color: var(--primary-color);">Cena</th>
                    <th style="padding: 1rem; text-align: left; color: var(--primary-color);">Stan</th>
                    <th style="padding: 1rem; text-align: left; color: var(--primary-color);">SKU</th>
                    <th style="padding: 1rem; text-align: left; color: var(--primary-color);">Akcje</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                    <tr style="border-bottom: 1px solid #333;">
                        <td style="padding: 1rem; color: var(--text-light);">#{{ $product->id }}</td>
                        <td style="padding: 1rem; color: var(--text-light);">{{ $product->name }}</td>
                        <td style="padding: 1rem; color: #999;">{{ $product->category->name }}</td>
                        <td style="padding: 1rem; color: var(--primary-color); font-weight: 700;">{{ number_format($product->price, 2) }} PLN</td>
                        <td style="padding: 1rem;">
                            @if($product->stock > 10)
                                <span style="color: #28a745;">✓ {{ $product->stock }} szt.</span>
                            @elseif($product->stock > 0)
                                <span style="color: #ffc107;">⚠ {{ $product->stock }} szt.</span>
                            @else
                                <span style="color: #dc3545;">✗ Brak</span>
                            @endif
                        </td>
                        <td style="padding: 1rem; color: #999;">{{ $product->sku }}</td>
                        <td style="padding: 1rem;">
                            <button onclick="toggleEdit({{ $product->id }})" class="btn btn-secondary" style="padding: 0.5rem 1rem; margin-right: 0.5rem;">
                                Edytuj
                            </button>
                            <form action="{{ route('admin.products.destroy', $product) }}" method="POST" style="display: inline;" onsubmit="return confirm('Czy na pewno chcesz usunąć ten produkt?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn" style="padding: 0.5rem 1rem; background-color: #dc3545; color: white;">
                                    Usuń
                                </button>
                            </form>
                        </td>
                    </tr>
                    <tr id="edit-{{ $product->id }}" style="display: none; background-color: var(--dark-bg);">
                        <td colspan="7" style="padding: 1.5rem;">
                            <form action="{{ route('admin.products.update', $product) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                                    <div>
                                        <label style="color: var(--text-light); display: block; margin-bottom: 0.5rem;">Nazwa</label>
                                        <input type="text" name="name" value="{{ $product->name }}" required
                                            style="width: 100%; padding: 0.5rem; background-color: var(--light-gray); color: var(--text-light); border: 1px solid var(--primary-color); border-radius: 4px;">
                                    </div>
                                    <div>
                                        <label style="color: var(--text-light); display: block; margin-bottom: 0.5rem;">Cena (PLN)</label>
                                        <input type="number" name="price" value="{{ $product->price }}" step="0.01" required
                                            style="width: 100%; padding: 0.5rem; background-color: var(--light-gray); color: var(--text-light); border: 1px solid var(--primary-color); border-radius: 4px;">
                                    </div>
                                    <div>
                                        <label style="color: var(--text-light); display: block; margin-bottom: 0.5rem;">Stan magazynowy</label>
                                        <input type="number" name="stock" value="{{ $product->stock }}" required
                                            style="width: 100%; padding: 0.5rem; background-color: var(--light-gray); color: var(--text-light); border: 1px solid var(--primary-color); border-radius: 4px;">
                                    </div>
                                    <div>
                                        <label style="color: var(--text-light); display: block; margin-bottom: 0.5rem;">SKU</label>
                                        <input type="text" name="sku" value="{{ $product->sku }}" required
                                            style="width: 100%; padding: 0.5rem; background-color: var(--light-gray); color: var(--text-light); border: 1px solid var(--primary-color); border-radius: 4px;">
                                    </div>
                                </div>
                                <div style="margin-top: 1rem;">
                                    <button type="submit" class="btn btn-primary" style="padding: 0.7rem 2rem;">
                                        Zapisz zmiany
                                    </button>
                                    <button type="button" onclick="toggleEdit({{ $product->id }})" class="btn btn-secondary" style="padding: 0.7rem 2rem; margin-left: 0.5rem;">
                                        Anuluj
                                    </button>
                                </div>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="padding: 2rem; text-align: center; color: #999;">
                            Brak produktów
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div style="margin-top: 2rem;">
            {{ $products->links() }}
        </div>
    </div>
</div>

<script>
function toggleEdit(productId) {
    const row = document.getElementById('edit-' + productId);
    row.style.display = row.style.display === 'none' ? 'table-row' : 'none';
}
</script>
@endsection
