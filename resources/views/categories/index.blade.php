@extends(auth()->check() && auth()->user()->isStaff() ? 'layouts.admin' : 'layouts.app')

@section('content')
<div class="container py-4">

    {{-- Cabeçalho / Hero Section mymishi --}}
    <div class="hero-mymishi p-4 p-md-5 mb-4 shadow-sm d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
        <div>
            <h1 class="display-6 fw-bold mb-1">
                🩷 Catálogo de Produtos
            </h1>
            <p class="text-muted mb-0">
                Consulta as categorias de armazém e verifica a disponibilidade dos produtos em tempo real.
            </p>
        </div>

        {{-- Botão de Criar Categoria (Visível para Mod e Admin) --}}
        @if(auth()->check() && auth()->user()->isStaff())
    <div class="d-flex flex-wrap align-items-center gap-2">
        <a href="{{ route('categories.create') }}" class="btn btn-outline-pink bg-white rounded-pill px-4">
            + Categoria
        </a>
        <a href="{{ route('products.create') }}" class="btn btn-pink rounded-pill px-4 shadow-sm">
            + Novo Artigo
        </a>
    </div>
@endif
    </div>

    {{-- Mensagens de Sucesso (Feedback Visual) --}}
    @if(session('message') || session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-4 shadow-sm" role="alert">
            {{ session('message') ?? session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
        </div>
    @endif

    {{-- Tabela Principal de Categorias --}}
    <div class="card">
        <div class="card-header bg-white py-3 px-4 d-flex justify-content-between align-items-center border-bottom">
            <h5 class="mb-0 fw-semibold">Categorias Registadas</h5>
           <span class="badge bg-pink-subtle text-pink rounded-pill px-3 py-2">
    Total: {{ $categories->total() }}
</span>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th style="width: 90px;" class="ps-4">Imagem</th>
                        <th>Nome da Categoria</th>
                        <th class="text-center">Nº de Produtos</th>
                        <th class="text-end pe-4">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $category)
                        <tr>
                            {{-- Foto da Categoria (com fallback rosa caso não tenha foto) --}}
                            <td class="ps-4">
                                @if($category->image)
                                    <img src="{{ asset($category->image) }}"
                                         alt="{{ $category->name }}"
                                         class="stock-thumb">
                                @else
                                    <div class="stock-thumb bg-pink-subtle d-flex align-items-center justify-content-center text-pink">
                                        <span class="fs-3">🪷</span>
                                    </div>
                                @endif
                            </td>

                            {{-- Nome --}}
                            <td>
                                <span class="fw-bold fs-6">{{ $category->name }}</span>
                            </td>

                            {{-- Contagem de Produtos --}}
                            <td class="text-center">
                                <span class="badge rounded-pill bg-pink-subtle text-pink px-3 py-2">
                                    {{ $category->products_count }} {{ $category->products_count === 1 ? 'produto' : 'produtos' }}
                                </span>
                            </td>

                            {{-- Botões de Ação --}}
                            <td class="text-end pe-4">
                                <div class="d-inline-flex gap-2">
                                    {{-- Botão Público: Ver Produtos --}}
                                    <a href="{{ route('categories.show', $category->id) }}" class="btn btn-outline-pink btn-sm rounded-pill px-3">
                                        👁️ Ver Produtos
                                    </a>

                                    {{-- Botão Editar: Apenas Mod e Admin --}}
                                    @auth
                                        @if(auth()->user()->isStaff())
                                            <a href="{{ route('categories.edit', $category->id) }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
                                                ✏️ Editar
                                            </a>
                                        @endif

                                        {{-- Botão Apagar: Apenas Admin --}}
                                        @if(auth()->user()->isAdmin())
                                            <form action="{{ route('categories.delete', $category->id) }}" method="POST"
                                                  onsubmit="return confirm('Tens a certeza que queres apagar esta categoria e todos os seus produtos?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill px-2">
                                                    🗑️
                                                </button>
                                            </form>
                                        @endif
                                    @endauth
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted">
                                Ainda não existem categorias registadas no sistema.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
         <div class="mt-4 px-2">
    {{ $categories->links() }}
</div>
        </div>
    </div>
</div>
@endsection
