@extends('layouts.admin')

@section('content')
<div class="container py-4">

    {{-- Cabeçalho --}}
    <div class="hero-mymishi p-4 p-md-5 mb-4 shadow-sm d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
        <div>
            <h1 class="display-6 fw-bold mb-1">🌸 Resumo da Loja</h1>
            <p class="text-muted mb-0">
                Olá, <strong>{{ auth()->user()->name }}</strong>! Aqui tens o resumo em tempo real do armazém da <strong>MyMishi</strong>.
            </p>
        </div>


    </div>

    {{-- 4 Cartões de Resumo (KPIs) --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm rounded-4 p-3 h-100 text-center">
                <span class="fs-2 mb-1">⭐</span>
                <h3 class="fw-bold text-pink mb-0">{{ $totalCategories }}</h3>
                <small class="text-muted fw-medium">Categorias</small>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm rounded-4 p-3 h-100 text-center">
                <span class="fs-2 mb-1">🧸</span>
                <h3 class="fw-bold text-pink mb-0">{{ $totalProducts }}</h3>
                <small class="text-muted fw-medium">Modelos criados</small>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm rounded-4 p-3 h-100 text-center">
                <span class="fs-2 mb-1">🎀</span>
                <h3 class="fw-bold text-pink mb-0">{{ $totalStockUnits }}</h3>
                <small class="text-muted fw-medium">Disponíveis na loja</small>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm rounded-4 p-3 h-100 text-center">
                <span class="fs-2 mb-1">🦋</span>
                <h3 class="fw-bold text-pink mb-0">{{ $totalUsers }}</h3>
                <small class="text-muted fw-medium">Utilizadores</small>
            </div>
        </div>
    </div>

    <div class="row g-4">
        {{-- Coluna Esquerda: Alertas de Stock Baixo / Esgotado --}}
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white py-3 px-4 d-flex justify-content-between align-items-center border-bottom">
                    <h5 class="fw-bold mb-0">⚠️ Alertas de Stock (≤ 5 un.)</h5>
                    <span class="badge bg-danger-subtle text-danger rounded-pill px-3 py-2">
                        {{ $lowStockProducts->count() }}
                    </span>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th class="ps-4">Artigo</th>
                                <th>Categoria</th>
                                <th class="text-center">Stock</th>
                                <th class="text-end pe-4">Ação</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($lowStockProducts as $item)
                                <tr>
                                    <td class="ps-4 fw-semibold">{{ $item->name }}</td>
                                    <td class="text-muted small">{{ $item->category->name ?? '—' }}</td>
                                    <td class="text-center">
                                        @if ($item->quantity == 0)
                                            <span class="badge bg-danger-subtle text-danger rounded-pill px-3">Esgotado</span>
                                        @else
                                            <span class="badge bg-warning-subtle text-warning-emphasis rounded-pill px-3">
                                                {{ $item->quantity }} un.
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-end pe-4">
                                        <a href="{{ route('products.edit', $item->id) }}" class="btn btn-outline-pink btn-sm rounded-pill px-3">
                                            Repor
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted">
                                        🎉 Tudo em ordem! Nenhum artigo com stock baixo.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Coluna Direita: Últimos Artigos Adicionados --}}
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white py-3 px-4 d-flex justify-content-between align-items-center border-bottom">
                    <h5 class="fw-bold mb-0">✨ Últimos Artigos Adicionados</h5>
                    <a href="{{ route('catalog.index') }}" class="text-decoration-none text-pink small fw-semibold">
                        Ver catálogo →
                    </a>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th class="ps-4">Foto</th>
                                <th>Artigo</th>
                                <th>Categoria</th>
                                <th class="text-end pe-4">Stock</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($recentProducts as $recent)
                                <tr>
                                    <td class="ps-4">
                                        @if ($recent->image)
                                            <img src="{{ asset($recent->image) }}" alt="{{ $recent->name }}" class="stock-thumb">
                                        @else
                                            <div class="stock-thumb bg-pink-subtle d-flex align-items-center justify-content-center text-pink">
                                                🧸
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="fw-semibold">{{ $recent->name }}</div>
                                        <small class="text-muted">por {{ $recent->user->name ?? 'Sistema' }}</small>
                                    </td>
                                    <td class="text-muted small">{{ $recent->category->name ?? '—' }}</td>
                                    <td class="text-end pe-4">
                                        <span class="badge bg-pink-subtle text-pink rounded-pill px-3">
                                            {{ $recent->quantity }} un.
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted">
                                        Ainda não existem artigos registados.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
