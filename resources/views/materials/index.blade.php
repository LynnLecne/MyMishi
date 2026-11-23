@extends(auth()->check() && auth()->user()->isStaff() ? 'layouts.admin' : 'layouts.app')

@section('content')
    <div class="container py-4">

        {{-- Cabeçalho --}}
        <div
            class="hero-mymishi p-4 p-md-5 mb-4 shadow-sm d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <h1 class="display-6 fw-bold mb-1">🧶 Materiais</h1>
                <p class="text-muted mb-0">
                    Gere as bolas de esferovite, folhas de EVA, acessórios e embalagens disponíveis no atelier da <strong>MyMishi</strong>.
                </p>
            </div>

            {{-- Grupo de Botões --}}
            <div class="d-flex flex-wrap gap-2">
                <a href="{{ route('materials.export') }}"
                    class="btn btn-outline-pink btn-lg rounded-pill px-4 shadow-sm bg-white">
                    ⎘ Exportar Excel
                </a>
                <a href="{{ route('materials.create') }}" class="btn btn-pink btn-lg rounded-pill px-4 shadow-sm">
                    + Novo Material
                </a>
            </div>
        </div>

        @if (session('message'))
            <div class="alert alert-success alert-dismissible fade show rounded-4 shadow-sm" role="alert">
                {{ session('message') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
            </div>
        @endif

        {{-- Barra de Pesquisa --}}
        <form method="GET" action="{{ url()->current() }}" class="d-flex align-items-center gap-2 mb-3"
            style="max-width: 450px;">
            <input type="text" name="search" class="form-control rounded-pill border-pink px-4 shadow-sm"
                placeholder="Pesquisar material..." value="{{ request('search') }}">
            <button type="submit" class="btn btn-pink rounded-pill px-4 shadow-sm">Procurar</button>
            @if (request('search'))
                <a href="{{ url()->current() }}" class="btn btn-outline-pink rounded-pill px-3">Limpar</a>
            @endif
        </form>

        {{-- Tabela de Materiais --}}
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="card-header bg-white py-3 px-4 d-flex justify-content-between align-items-center border-bottom">
                <h5 class="mb-0 fw-semibold">Materiais Registados</h5>
                <span class="badge bg-pink-subtle text-pink rounded-pill px-3 py-2">
                    Total: {{ $materials->count() }}
                </span>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th style="width: 90px;" class="ps-4">Foto</th>
                            <th>Material</th>
                            <th class="text-center">Stock</th>
                            <th>Custo Unitário</th>
                            <th class="text-end pe-4">Acções</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($materials as $material)
                            <tr>
                                <td class="ps-4">
                                    @if ($material->image)
                                        <img src="{{ asset($material->image) }}" alt="{{ $material->name }}"
                                            class="stock-thumb">
                                    @else
                                        <div
                                            class="stock-thumb bg-pink-subtle d-flex align-items-center justify-content-center text-pink">
                                            <span class="fs-4">🧶</span>
                                        </div>
                                    @endif
                                </td>
                                <td class="fw-bold">{{ $material->name }}</td>
                                <td class="text-center">
                                    @if ($material->isLowStock())
                                        <span class="badge bg-danger-subtle text-danger rounded-pill px-3 py-2">
                                            {{ rtrim(rtrim(number_format($material->quantity, 2, ',', '.'), '0'), ',') }}
                                            {{ $material->unit }} (Baixo)
                                        </span>
                                    @else
                                        <span class="badge bg-pink-subtle text-pink rounded-pill px-3 py-2">
                                            {{ rtrim(rtrim(number_format($material->quantity, 2, ',', '.'), '0'), ',') }}
                                            {{ $material->unit }}
                                        </span>
                                    @endif
                                </td>
                                <td>{{ number_format($material->unit_cost, 2, ',', '.') }} € / {{ $material->unit }}</td>
                                <td class="text-end pe-4">
                                    <div class="d-inline-flex gap-2">
                                        <a href="{{ route('materials.edit', $material->id) }}"
                                            class="btn btn-outline-secondary btn-sm rounded-pill px-3">
                                            ✏️ Editar
                                        </a>

                                        @if (auth()->user()->isAdmin())
                                            <form action="{{ route('materials.delete', $material->id) }}" method="POST"
                                                onsubmit="return confirm('Tens a certeza que queres apagar este material?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="btn btn-outline-danger btn-sm rounded-pill px-2">
                                                    🗑️
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    Ainda não existem materiais registados.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="mt-4 px-2">
                    {{ $materials->links() }}
                </div>
            </div>
        </div>
        <a href="{{ route('materials.export') }}" class="btn btn-outline-pink">Exportar Excel (.xlsx)</a>
    </div>
@endsection
