@extends(auth()->check() && auth()->user()->isStaff() ? 'layouts.admin' : 'layouts.app')

@section('content')
<div class="container py-4">
    <div class="card border-0 shadow-sm rounded-4 mx-auto" style="max-width: 600px;">
        <div class="card-header bg-pink-subtle border-0 py-3 px-4 rounded-top-4">
            <h4 class="fw-bold text-pink mb-0">✏️ Editar Categoria: {{ $category->name }}</h4>
        </div>

        <div class="card-body p-4 bg-white rounded-bottom-4">
            <form action="{{ route('categories.update', $category->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label fw-medium">Nome da Categoria *</label>
                    <input type="text" name="name" class="form-control border-pink @error('name') is-invalid @enderror"
                           value="{{ old('name', $category->name) }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label fw-medium">Substituir Imagem (Opcional)</label>
                    @if ($category->image)
                        <div class="mb-2">
                            <img src="{{ asset($category->image) }}" alt="{{ $category->name }}" class="stock-thumb">
                        </div>
                    @endif
                    <input type="file" name="image" class="form-control border-pink @error('image') is-invalid @enderror" accept="image/*">
                    @error('image')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('catalog.index') }}" class="btn btn-outline-pink rounded-pill px-4">Cancelar</a>
                    <button type="submit" class="btn btn-pink rounded-pill px-4 shadow-sm">Atualizar Categoria</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
