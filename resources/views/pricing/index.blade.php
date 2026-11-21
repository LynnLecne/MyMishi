@extends('layouts.admin')

@section('content')
<div class="container py-4">

    {{-- Cabeçalho --}}
    <div class="hero-mymishi p-4 p-md-5 mb-4 shadow-sm d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
        <div>
            <h1 class="display-6 fw-bold mb-1">👛 Precificação & Margens</h1>
            <p class="text-muted mb-0">
                Define os custos de produção, margens de lucro e preços finais das peças da <strong>mymishi</strong>.
            </p>
        </div>
        <span class="badge bg-pink-subtle text-pink rounded-pill px-3 py-2 fs-6">
            🔐︎ Exclusivo Admin
        </span>
    </div>

    @if (session('message'))
        <div class="alert alert-success alert-dismissible fade show rounded-4 shadow-sm" role="alert">
            {{ session('message') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
        </div>
    @endif

    {{-- Pesquisa --}}
    <form method="GET" action="{{ url()->current() }}" class="d-flex align-items-center gap-2 mb-3" style="max-width: 450px;">
        <input type="text" name="search" class="form-control rounded-pill border-pink px-4 shadow-sm"
               placeholder="Pesquisar artigo..." value="{{ request('search') }}">
        <button type="submit" class="btn btn-pink rounded-pill px-4 shadow-sm">Procurar</button>
        @if (request('search'))
            <a href="{{ url()->current() }}" class="btn btn-outline-pink rounded-pill px-3">Limpar</a>
        @endif
    </form>

    {{-- Tabela de Precificação --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-header bg-white py-3 px-4 d-flex justify-content-between align-items-center border-bottom">
            <h5 class="mb-0 fw-semibold">Tabela de Preços</h5>
            <span class="badge bg-pink-subtle text-pink rounded-pill px-3 py-2">
    Total: {{ $products->total() }}
</span>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th style="width: 90px;" class="ps-4">Foto</th>
                        <th>Artigo</th>
                        <th>Coleção</th>
                        <th>Custo Total</th>
                        <th>Margem</th>
                        <th>Preço Final</th>
                        <th class="text-end pe-4">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($products as $product)
                        @php
                            $pricing = $product->pricing;
                            $totalCost = $pricing
                                ? ($pricing->materials_cost + $pricing->labor_cost + $pricing->packaging_cost)
                                : 0;
                        @endphp
                        <tr>
                            <td class="ps-4">
                                @if ($product->image)
                                    <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" class="stock-thumb">
                                @else
                                    <div class="stock-thumb bg-pink-subtle d-flex align-items-center justify-content-center text-pink">
                                        🧸
                                    </div>
                                @endif
                            </td>
                            <td class="fw-bold">{{ $product->name }}</td>
                            <td class="text-muted">{{ $product->category->name ?? '—' }}</td>
                            <td>
                                @if ($pricing)
                                    {{ number_format($totalCost, 2, ',', '.') }} €
                                @else
                                    <span class="text-muted small">Não definido</span>
                                @endif
                            </td>
                            <td>
                                @if ($pricing)
                                    <span class="badge bg-light text-dark border rounded-pill px-3">
                                        +{{ number_format($pricing->profit_margin, 0) }}%
                                    </span>
                                @else
                                    —
                                @endif
                            </td>
                            <td>
                                @if ($pricing)
                                    <span class="badge bg-pink-subtle text-pink fs-6 rounded-pill px-3 py-2">
                                        {{ number_format($pricing->final_price, 2, ',', '.') }} €
                                    </span>
                                @else
                                    <span class="badge bg-warning-subtle text-warning-emphasis rounded-pill px-3">
                                        Sem preço
                                    </span>
                                @endif
                            </td>
                            <td class="text-end pe-4">
                                <a href="{{ route('pricing.edit', $product->id) }}" class="btn btn-outline-pink btn-sm rounded-pill px-3">
                                    🍬   Definir Preço
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                Ainda não existem artigos registados no catálogo.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="mt-4 px-2">
    {{ $products->links() }}
</div>
        </div>
    </div>

</div>
@endsection
