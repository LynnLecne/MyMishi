@extends(auth()->check() && auth()->user()->isStaff() ? 'layouts.admin' : 'layouts.app')

@section('content')
<div class="container py-4">
    <div class="card border-0 shadow-sm rounded-4 mx-auto" style="max-width: 600px;">
        <div class="card-header bg-pink-subtle border-0 py-3 px-4 rounded-top-4">
            <h4 class="fw-bold text-pink mb-0">✏️ Editar Artigo: {{ $product->name }}</h4>
        </div>

        <div class="card-body p-4 bg-white rounded-bottom-4">
            <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label fw-medium">Categoria *</label>
                    <select name="category_id" class="form-select border-pink @error('category_id') is-invalid @enderror" required>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" @selected(old('category_id', $product->category_id) == $category->id)>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-medium">Nome do Artigo *</label>
                    <input type="text" name="name" class="form-control border-pink @error('name') is-invalid @enderror"
                           value="{{ old('name', $product->name) }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-medium">Quantidade em Stock *</label>
                    <input type="number" name="quantity" class="form-control border-pink @error('quantity') is-invalid @enderror"
                           value="{{ old('quantity', $product->quantity) }}" min="0" required>
                    @error('quantity')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label fw-medium">Substituir Fotografia (Opcional)</label>
                    @if ($product->image)
                        <div class="mb-2">
                            <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" class="stock-thumb">
                        </div>
                    @endif
                    <input type="file" name="image" class="form-control border-pink @error('image') is-invalid @enderror" accept="image/*">
                    @error('image')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('categories.show', $product->category_id) }}" class="btn btn-outline-pink rounded-pill px-4">Cancelar</a>
                    <button type="submit" class="btn btn-pink rounded-pill px-4 shadow-sm">Actualizar Artigo</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
