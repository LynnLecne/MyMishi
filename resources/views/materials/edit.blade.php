@extends(auth()->check() && auth()->user()->isStaff() ? 'layouts.admin' : 'layouts.app')

@section('content')
<div class="container py-4">
    <div class="card border-0 shadow-sm rounded-4 mx-auto" style="max-width: 650px;">
        <div class="card-header bg-pink-subtle border-0 py-3 px-4 rounded-top-4">
            <h4 class="fw-bold text-pink mb-0">✏️ Editar Material: {{ $material->name }}</h4>
        </div>

        <div class="card-body p-4 bg-white rounded-bottom-4">
            <form action="{{ route('materials.update', $material->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    {{-- Nome do Material --}}
                    <div class="col-md-8">
                        <label class="form-label fw-medium">Nome do Material *</label>
                        <input type="text" name="name" class="form-control border-pink @error('name') is-invalid @enderror"
                               value="{{ old('name', $material->name) }}" required>
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    {{-- Unidade --}}
                    <div class="col-md-4">
    <label class="form-label fw-medium">Unidade *</label>
    <select name="unit" class="form-select border-pink @error('unit') is-invalid @enderror" required>
        @foreach (['un.' => 'Unidades (un.)', 'm' => 'Metros (m)'] as $val => $label)
            <option value="{{ $val }}" @selected(old('unit', $material->unit) === $val)>{{ $label }}</option>
        @endforeach
    </select>
    @error('unit') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

                    {{-- Quantidade Atual --}}
                    <div class="col-md-6">
                        <label class="form-label fw-medium">Quantidade Atual *</label>
                        <input type="number" step="0.01" name="quantity" class="form-control border-pink @error('quantity') is-invalid @enderror"
                               value="{{ old('quantity', $material->quantity) }}" min="0" required>
                        @error('quantity') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    {{-- Custo Unitário --}}
                    <div class="col-md-6">
                        <label class="form-label fw-medium">Custo Unitário (€) *</label>
                        <input type="number" step="0.01" name="unit_cost" class="form-control border-pink @error('unit_cost') is-invalid @enderror"
                               value="{{ old('unit_cost', $material->unit_cost) }}" min="0" required>
                        @error('unit_cost') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    
                   {{-- Imagem Atual --}}
@if ($material->image)
    <div class="col-12 mt-4">
        <label class="form-label fw-medium d-block">Imagem Atual</label>
        <div class="d-inline-flex p-2 border border-pink rounded-3 bg-light">
            <img src="{{ asset($material->image) }}" alt="{{ $material->name }}" class="stock-thumb">
        </div>
    </div>
@endif

                    {{-- Galeria de Ícones Predefinidos --}}
                    <div class="col-12 mt-4">
                        <label class="form-label fw-medium d-block">Escolher Ícone da Galeria</label>

                        @error('preset_image')
                            <div class="text-danger small mb-2">{{ $message }}</div>
                        @enderror

                        @php
                            $currentPreset = ($material->image && str_starts_with($material->image, 'images/materials/'))
                                ? basename($material->image)
                                : null;
                            $selectedPreset = old('preset_image', $currentPreset);
                        @endphp

                        <div class="row g-2">
                            @foreach($images as $img)
                                <div class="col-3 col-sm-2 text-center">
                                    <input type="radio"
                                           class="btn-check"
                                           name="preset_image"
                                           id="preset_{{ $loop->index }}"
                                           value="{{ $img }}"
                                           autocomplete="off"
                                           {{ $selectedPreset === $img ? 'checked' : '' }}>

                                    <label class="btn btn-outline-pink p-2 w-100 h-100 d-flex align-items-center justify-content-center rounded-3"
                                           for="preset_{{ $loop->index }}">
                                        <img src="{{ asset('images/materials/' . $img) }}"
                                             alt="Ícone {{ $loop->iteration }}"
                                             class="img-fluid rounded"
                                             style="width: 56px; height: 56px; object-fit: cover;">
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Upload Personalizado (Opcional) --}}
                    <div class="col-12 mt-3">
                        <label class="form-label fw-medium">Ou Carregar Nova Foto Personalizada (Opcional)</label>
                        <input type="file"
                               name="custom_image"
                               class="form-control border-pink @error('custom_image') is-invalid @enderror"
                               accept="image/*">
                        <div class="form-text text-muted small">
                            Se carregares uma foto aqui, ela substituirá a imagem atual e terá prioridade sobre a galeria (máx. 2MB).
                        </div>
                        @error('custom_image') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('materials.all') }}" class="btn btn-outline-pink rounded-pill px-4">Cancelar</a>
                    <button type="submit" class="btn btn-pink rounded-pill px-4 shadow-sm">Atualizar Material</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
