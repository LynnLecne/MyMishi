@extends(auth()->check() && auth()->user()->isStaff() ? 'layouts.admin' : 'layouts.app')

@section('content')
<div class="container py-4">
    <div class="card border-0 shadow-sm rounded-4 mx-auto" style="max-width: 650px;">
        <div class="card-header bg-pink-subtle border-0 py-3 px-4 rounded-top-4">
            <h4 class="fw-bold text-pink mb-0">🛍️ Nova Categoria</h4>
        </div>

        <div class="card-body p-4 bg-white rounded-bottom-4">
            <form action="{{ route('categories.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                {{-- Nome da Categoria --}}
                <div class="mb-4">
                    <label class="form-label fw-medium">Nome da Categoria *</label>
                    <input type="text" name="name" class="form-control border-pink @error('name') is-invalid @enderror"
                           value="{{ old('name') }}" placeholder="Ex: Amigurumis, Porta-chaves..." required autofocus>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Galeria de Ícones Predefinidos --}}
                <div class="mb-4">
                    <label class="form-label fw-medium d-block">Escolher Ícone da Galeria</label>

                    @error('preset_image')
                        <div class="text-danger small mb-2">{{ $message }}</div>
                    @enderror

                    <div class="row g-2">
                        @foreach($images as $img)
                            <div class="col-3 col-sm-2 text-center">
                                <input type="radio"
                                       class="btn-check"
                                       name="preset_image"
                                       id="preset_{{ $loop->index }}"
                                       value="{{ $img }}"
                                       autocomplete="off"
                                       {{ old('preset_image', $images[0] ?? '') === $img ? 'checked' : '' }}>

                                <label class="btn btn-outline-pink p-2 w-100 h-100 d-flex align-items-center justify-content-center rounded-3"
                                       for="preset_{{ $loop->index }}">
                                    <img src="{{ asset('images/categories/' . $img) }}"
                                         alt="Ícone {{ $loop->iteration }}"
                                         class="img-fluid rounded"
                                         style="width: 56px; height: 56px; object-fit: cover;">
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Upload Personalizado (Opcional) --}}
                <div class="mb-4">
                    <label class="form-label fw-medium">Ou Carregar Imagem Personalizada (Opcional)</label>
                    <input type="file"
                           name="custom_image"
                           id="custom_image"
                           class="form-control border-pink @error('custom_image') is-invalid @enderror"
                           accept="image/*">
                    <div class="form-text text-muted small">
                        Se carregares um ficheiro aqui, ele terá prioridade sobre o ícone selecionado acima (máx. 2MB).
                    </div>
                    @error('custom_image')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Botões de Ação --}}
                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('catalog.index') }}" class="btn btn-outline-pink rounded-pill px-4">Cancelar</a>
                    <button type="submit" class="btn btn-pink rounded-pill px-4 shadow-sm">Guardar Categoria</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
