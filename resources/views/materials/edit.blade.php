@extends('layouts.admin')

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
                    <div class="col-md-8">
                        <label class="form-label fw-medium">Nome do Material *</label>
                        <input type="text" name="name" class="form-control border-pink @error('name') is-invalid @enderror"
                               value="{{ old('name', $material->name) }}" required>
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-medium">Unidade *</label>
                        <select name="unit" class="form-select border-pink" required>
                            @foreach (['un.' => 'Unidades (un.)', 'm' => 'Metros (m)', 'g' => 'Gramas (g)', 'novelos' => 'Novelos', 'rolos' => 'Rolos'] as $val => $label)
                                <option value="{{ $val }}" @selected(old('unit', $material->unit) === $val)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-medium">Quantidade Atual *</label>
                        <input type="number" step="0.01" name="quantity" class="form-control border-pink @error('quantity') is-invalid @enderror"
                               value="{{ old('quantity', $material->quantity) }}" min="0" required>
                        @error('quantity') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-medium">Custo Unitário (€) *</label>
                        <input type="number" step="0.01" name="unit_cost" class="form-control border-pink @error('unit_cost') is-invalid @enderror"
                               value="{{ old('unit_cost', $material->unit_cost) }}" min="0" required>
                        @error('unit_cost') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>



                    <div class="col-md-6">
                        <label class="form-label fw-medium">Substituir Foto (Opcional)</label>
                        <input type="file" name="image" class="form-control border-pink" accept="image/*">
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
