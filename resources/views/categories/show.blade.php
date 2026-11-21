@extends(auth()->check() && auth()->user()->isStaff() ? 'layouts.admin' : 'layouts.app')

@section('content')
    <div class="container py-3">
        @if (session('message'))
            <div class="alert alert-success">{{ session('message') }}</div>
        @endif

        {{-- Cabeçalho da Categoria --}}
        <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-pink-subtle">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                <div>
                    <a href="{{ route('catalog.index') }}" class="text-decoration-none text-pink small fw-semibold">
                        ← Voltar às Categorias
                    </a>
                    <h3 class="fw-bold mt-1 mb-1">🪷 {{ $category->name }}</h3>
                    <p class="text-muted mb-0">Consulta os artigos disponíveis nesta categoria e as quantidades em stock.</p>
                </div>

                @if (auth()->check() && auth()->user()->isStaff())
                    <a href="{{ route('products.create', ['category_id' => $category->id]) }}"
                        class="btn btn-pink rounded-pill px-4 shadow-sm">
                        + Novo Artigo
                    </a>
                @endif
            </div>
        </div>

        {{-- Tabela de Artigos --}}
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="card-header bg-white py-3 px-4 d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0">Artigos Registados</h5>
                <span class="badge bg-pink-subtle text-pink rounded-pill px-3 py-2">
    Total: {{ $products->total() }}
</span>
            </div>

            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">Imagem</th>
                        <th>Nome do Artigo</th>
                        <th>Quantidade em Stock</th>
                        @if (auth()->check() && auth()->user()->isStaff())
                            <th class="text-end pe-4">Acções</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @forelse ($products as $product)
                        <tr>
                            <td class="ps-4">
                                @if ($product->image)
                                    <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" class="stock-thumb">
                                @else
                                    <div
                                        class="stock-thumb bg-pink-subtle d-flex align-items-center justify-content-center text-pink">
                                        <span class="fs-3">🧸</span>
                                    </div>
                                @endif
                            </td>
                            <td class="fw-semibold">{{ $product->name }}</td>
                            <td>
                                @if ($product->quantity > 0)
                                    <span class="badge bg-pink-subtle text-pink rounded-pill px-3 py-2">
                                        {{ $product->quantity }} {{ $product->quantity === 1 ? 'unidade' : 'unidades' }}
                                    </span>
                                @else
                                    <span class="badge bg-danger-subtle text-danger rounded-pill px-3 py-2">
                                        Esgotado (0)
                                    </span>
                                @endif
                            </td>

                            @if (auth()->check() && auth()->user()->isStaff())
                                <td class="text-end pe-4">
                                    <div class="d-inline-flex gap-2">
                                        <a href="{{ route('products.edit', $product->id) }}"
                                            class="btn btn-outline-secondary btn-sm rounded-pill px-3">
                                            ✏️ Editar
                                        </a>

                                        @if (auth()->user()->isAdmin())
                                            <form action="{{ route('products.delete', $product->id) }}" method="POST"
                                                class="d-inline"
                                                onsubmit="return confirm('Tens a certeza que queres apagar este artigo?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="btn btn-outline-danger btn-sm rounded-pill px-3">
                                                    🗑️
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ auth()->check() && auth()->user()->isStaff() ? 4 : 3 }}"
                                class="text-center py-4 text-muted">
                                Ainda não existem artigos registados nesta categoria.
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
@endsection
