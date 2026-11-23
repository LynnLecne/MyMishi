@extends(auth()->check() && auth()->user()->isStaff() ? 'layouts.admin' : 'layouts.app')

@section('content')
    <div class="container py-3">
        @if (session('message'))
            <div class="alert alert-success">{{ session('message') }}</div>
        @endif

        {{-- Categoria --}}
        <div
            class="bg-pink-subtle rounded-4 p-4 mb-4 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            {{-- Imagem e título --}}
            <div>
                <h2 class="fw-bold mb-1 d-flex align-items-center gap-2">
                    @if ($category->image)
                        <img src="{{ asset($category->image) }}" alt="{{ $category->name }}"
                            style="width: 36px; height: 36px; object-fit: cover; cursor: zoom-in;" class="rounded-circle"
                            title="Clica para ampliar" data-bs-toggle="modal" data-bs-target="#imageLightboxModal"
                            data-image-src="{{ asset($category->image) }}" data-image-title="{{ $category->name }}">
                    @else
                        <span>🪷</span>
                    @endif
                    <span>{{ $category->name }}</span>
                </h2>
                <p class="text-muted mb-0">
                    Consulta os artigos disponíveis nesta categoria e as quantidades em stock.
                </p>
            </div>

            {{-- Botões de Acção --}}
            <div class="d-flex flex-wrap align-items-center gap-2">
                <a href="{{ route('catalog.index') }}" class="btn btn-outline-pink bg-white rounded-pill px-4">
                    ← Voltar às Categorias
                </a>

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
                                    <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" class="stock-thumb"
                                        style="cursor: zoom-in;" title="Clica para ver em tamanho real"
                                        data-bs-toggle="modal" data-bs-target="#imageLightboxModal"
                                        data-image-src="{{ asset($product->image) }}"
                                        data-image-title="{{ $product->name }}">
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
                {{ $products->links() }}
            </div>
        </div>
    </div>

    {{-- Modal Lightbox para Imagem em Tamanho Real --}}
    <div class="modal fade" id="imageLightboxModal" tabindex="-1" aria-labelledby="imageLightboxLabel" aria-hidden="true"
        onclick="closeImageLightbox(event)">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow rounded-4 overflow-hidden" onclick="event.stopPropagation()">
                <div class="modal-header bg-pink-subtle border-0 py-3 px-4">
                    <h5 class="modal-title fw-bold text-pink mb-0" id="imageLightboxLabel"></h5>
                    <button type="button" class="btn-close" aria-label="Fechar" onclick="hideImageLightbox()"></button>
                </div>
                <div class="modal-body p-3 text-center bg-white">
                    <img id="imageLightboxPreview" src="" alt="" class="img-fluid rounded-3"
                        style="max-height: 80vh;">
                </div>
            </div>
        </div>
    </div>

        <script>
        function openImageLightbox(src, title) {
            const modalEl = document.getElementById('imageLightboxModal');
            const previewEl = document.getElementById('imageLightboxPreview');

            previewEl.src = src;
            previewEl.alt = title;
            document.getElementById('imageLightboxLabel').textContent = title;

            modalEl.classList.add('show');
            modalEl.style.display = 'block';
            modalEl.style.backgroundColor = 'rgba(0, 0, 0, 0.5)';
            document.body.classList.add('modal-open');
        }

        function hideImageLightbox() {
            const modalEl = document.getElementById('imageLightboxModal');
            modalEl.classList.remove('show');
            modalEl.style.display = 'none';
            document.body.classList.remove('modal-open');
            document.getElementById('imageLightboxPreview').src = '';
        }

        function closeImageLightbox(event) {
            if (event.target.id === 'imageLightboxModal') {
                hideImageLightbox();
            }
        }

        document.addEventListener('click', function (event) {
            const trigger = event.target.closest('[data-bs-target="#imageLightboxModal"]');
            if (trigger) {
                event.preventDefault();
                openImageLightbox(
                    trigger.getAttribute('data-image-src'),
                    trigger.getAttribute('data-image-title')
                );
            }
        });
    </script>

@endsection
