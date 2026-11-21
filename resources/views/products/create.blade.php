@extends(auth()->check() && auth()->user()->isStaff() ? 'layouts.admin' : 'layouts.app')

@section('content')
<div class="container py-4">
    <div class="card border-0 shadow-sm rounded-4 mx-auto" style="max-width: 600px;">
        <div class="card-header bg-pink-subtle border-0 py-3 px-4 rounded-top-4">
            <h4 class="fw-bold text-pink mb-0">🧸 Adicionar Novo Artigo</h4>
        </div>

        <div class="card-body p-4 bg-white rounded-bottom-4">
            <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <label class="form-label fw-medium">Categoria *</label>
                    <select name="category_id" class="form-select border-pink @error('category_id') is-invalid @enderror" required>
                        <option value="">Seleciona uma categoria...</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" @selected(old('category_id', $selectedCategory) == $category->id)>
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
                           value="{{ old('name') }}" placeholder="Ex: Coelhinho Pastel Mishi" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-medium">Quantidade em Stock *</label>
                    <input type="number" name="quantity" class="form-control border-pink @error('quantity') is-invalid @enderror"
                           value="{{ old('quantity', 1) }}" min="0" required>
                    @error('quantity')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label fw-medium">Fotografia do Artigo (Opcional)</label>
                    <input type="file" name="image" class="form-control border-pink @error('image') is-invalid @enderror" accept="image/*">
                    @error('image')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ $selectedCategory ? route('categories.show', $selectedCategory) : route('catalog.index') }}"
                       class="btn btn-outline-pink rounded-pill px-4">Cancelar</a>
                    <button type="submit" class="btn btn-pink rounded-pill px-4 shadow-sm">Guardar Artigo</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
