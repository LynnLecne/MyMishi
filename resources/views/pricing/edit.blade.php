@extends('layouts.admin')

@section('content')
<div class="container py-4">
    <div class="card border-0 shadow-sm rounded-4 mx-auto" style="max-width: 850px;">
        <div class="card-header bg-pink-subtle border-0 py-3 px-4 rounded-top-4">
            <h4 class="fw-bold text-pink mb-0">🏷️ Precificar: {{ $product->name }}</h4>
            <small class="text-muted">Colecção: {{ $product->category->name ?? 'Geral' }}</small>
        </div>

        <div class="card-body p-4 bg-white rounded-bottom-4">
            <form action="{{ route('pricing.update', $product->id) }}" method="POST">
                @csrf
                @method('PUT')

                {{-- Materiais do Inventário --}}
                <h6 class="fw-bold text-pink mb-3">🧶 1. Materiais Gastos na Peça</h6>
                <div class="table-responsive border rounded-4 mb-4">
                    <table class="table table-sm align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-3 py-2">Material</th>
                                <th>Custo Unitário</th>
                                <th style="width: 150px;">Qtd. Gasta</th>
                                <th class="text-end pe-3">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($materials as $material)
                                @php
                                    $savedQty = old('materials.' . $material->id, $usedMaterials[$material->id] ?? 0);
                                @endphp
                                <tr>
                                    <td class="ps-3 fw-medium">
                                        {{ $material->name }}
                                        <small class="text-muted">({{ $material->unit }})</small>
                                    </td>
                                    <td class="text-muted">
                                        {{ number_format($material->unit_cost, 2, ',', '.') }} € / {{ $material->unit }}
                                    </td>
                                    <td>
                                        <input type="number"
                                               step="0.01"
                                               min="0"
                                               name="materials[{{ $material->id }}]"
                                               value="{{ $savedQty > 0 ? $savedQty : '' }}"
                                               placeholder="0"
                                               data-unit-cost="{{ $material->unit_cost }}"
                                               data-material-id="{{ $material->id }}"
                                               class="form-control form-control-sm border-pink material-qty-input">
                                    </td>
                                    <td class="text-end pe-3 fw-semibold" id="subtotal_{{ $material->id }}">
                                        0,00 €
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-3 text-muted small">
                                        Ainda não tens produtos registados no Inventário.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                     {{-- Custos e Margem --}}
                <h6 class="fw-bold text-pink mb-3">💰 2. Custos e Margem de Lucro</h6>
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label fw-medium">Total Materiais (€) *</label>
                        <input type="number" step="0.01" name="materials_cost" id="materials_cost"
                               class="form-control border-pink bg-light fw-semibold calc-input"
                               value="{{ old('materials_cost', $pricing->materials_cost ?? '0.00') }}" min="0" required>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-medium">Valor / Hora (€) *</label>
                        <input type="number" step="0.50" name="hourly_rate" id="hourly_rate"
                               class="form-control border-pink calc-input"
                               value="{{ old('hourly_rate', $pricing->hourly_rate ?? number_format($defaultHourlyRate, 2, '.', '')) }}" min="0" required>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-medium">Tempo (Horas) *</label>
                        <input type="number" step="0.25" name="hours_spent" id="hours_spent"
                               class="form-control border-pink calc-input"
                               value="{{ old('hours_spent', $pricing->hours_spent ?? '0.25') }}" min="0" required>
                        <small class="text-muted" id="minutes_helper" style="font-size: 0.75rem;">15 minutos</small>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-medium">Total Mão de Obra (€) *</label>
                        <input type="number" step="0.01" name="labor_cost" id="labor_cost"
                               class="form-control border-pink bg-light fw-semibold"
                               value="{{ old('labor_cost', $pricing->labor_cost ?? '0.00') }}" min="0" readonly required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-medium">Embalagem (€) *</label>
                        <input type="number" step="0.01" name="packaging_cost" id="packaging_cost"
                               class="form-control border-pink calc-input"
                               value="{{ old('packaging_cost', $pricing->packaging_cost ?? '0.50') }}" min="0" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-medium">Margem de Lucro (%) *</label>
                        <input type="number" step="0.01" name="profit_margin" id="profit_margin"
                               class="form-control border-pink calc-input"
                               value="{{ old('profit_margin', $pricing->profit_margin ?? '40') }}" min="0" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-bold text-pink">Preço Final de Venda (€) *</label>
                        <input type="number" step="0.01" name="final_price" id="final_price"
                               class="form-control fw-bold bg-pink-subtle text-pink border-pink"
                               value="{{ old('final_price', $pricing->final_price ?? '0.00') }}" min="0" required>
                    </div>
                </div>


                <div class="mt-4 p-3 rounded-4 bg-light d-flex justify-content-between align-items-center">
                    <span class="text-muted small">Custo Base Total: <strong id="display_cost">0,00 €</strong></span>
                    <span class="text-muted small">Lucro Estimado: <strong id="display_profit" class="text-success">0,00 €</strong></span>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('pricing.index') }}" class="btn btn-outline-pink rounded-pill px-4">Cancelar</a>
                    <button type="submit" class="btn btn-pink rounded-pill px-4 shadow-sm">Guardar Precificação</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function actualizarMateriais() {
        let totalMateriais = 0;

        document.querySelectorAll('.material-qty-input').forEach(input => {
            const qty = parseFloat(input.value) || 0;
            const unitCost = parseFloat(input.dataset.unitCost) || 0;
            const subtotal = qty * unitCost;
            totalMateriais += subtotal;

            const subEl = document.getElementById('subtotal_' + input.dataset.materialId);
            if (subEl) {
                subEl.textContent = subtotal.toFixed(2).replace('.', ',') + ' €';
            }
        });

        const hasMaterials = document.querySelectorAll('.material-qty-input').length > 0;
        if (hasMaterials) {
            document.getElementById('materials_cost').value = totalMateriais.toFixed(2);
        }

        calcularPreco();
    }

    function calcularPreco() {
        const mat = parseFloat(document.getElementById('materials_cost').value) || 0;
        const rate = parseFloat(document.getElementById('hourly_rate').value) || 0;
        const hours = parseFloat(document.getElementById('hours_spent').value) || 0;
        const pkg = parseFloat(document.getElementById('packaging_cost').value) || 0;
        const margin = parseFloat(document.getElementById('profit_margin').value) || 0;

        // Calcula mão de obra por quartos de hora (rate * hours)
        const lab = rate * hours;
        document.getElementById('labor_cost').value = lab.toFixed(2);

        // Actualiza texto auxiliar em minutos (ex: 1.5h = 90 min)
        const totalMins = Math.round(hours * 60);
        document.getElementById('minutes_helper').textContent = totalMins + ' minutos';

        const baseCost = mat + lab + pkg;
        const finalPrice = baseCost * (1 + (margin / 100));
        const profit = finalPrice - baseCost;

        document.getElementById('final_price').value = finalPrice.toFixed(2);
        document.getElementById('display_cost').textContent = baseCost.toFixed(2).replace('.', ',') + ' €';
        document.getElementById('display_profit').textContent = profit.toFixed(2).replace('.', ',') + ' €';
    }

    document.querySelectorAll('.material-qty-input').forEach(el => el.addEventListener('input', actualizarMateriais));
    document.querySelectorAll('.calc-input').forEach(el => el.addEventListener('input', calcularPreco));

    actualizarMateriais();
</script>
@endsection
