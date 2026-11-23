<!doctype html>
<html lang="pt">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>mymishi | Backoffice Admin</title>
    {{-- bootstrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.9/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-yEPZ7jP9S0QKiUQ0m3jjsO4ProIW7wvR9gXkI7JOGa2XVsw53Yke2bsdkBbjmmiX" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>

<body>

    <!-- Navbar-->
    <nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top shadow-sm py-3 mb-4 border-bottom border-2">
        <div class="container">

            <!-- Logótipo -->
            <a class="navbar-brand d-flex align-items-center py-0" href="{{ url('/') }}">
                <img src="{{ asset('images/mymishi.png') }}" alt="Mymishi" class="brand-logo">
            </a>

            <!-- Botão Mobile (Hambúrguer) -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarAdmin"
                aria-controls="navbarAdmin" aria-expanded="false" aria-label="Alternar navegação">
                <span class="navbar-toggler-icon"></span>
            </button>


            <!-- Links de Navegação -->
            <div class="collapse navbar-collapse" id="navbarAdmin">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-4 gap-2">

                    <li class="nav-item">
                        <a class="nav-link fs-6 fw-medium px-3 {{ request()->is('/') ? 'active text-pink bg-pink-subtle rounded-pill' : '' }}"
                            href="{{ url('/') }}">
                            🩷 Catálogo
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link fs-6 fw-medium px-3 {{ request()->routeIs('dashboard') ? 'active text-pink bg-pink-subtle rounded-pill' : '' }}"
                            href="{{ route('dashboard') }}">
                            🌸 Dashboard
                        </a>
                    </li>


                    <li class="nav-item">
                        <a class="nav-link fs-6 fw-medium px-3 {{ request()->routeIs('materials.*') ? 'active text-pink bg-pink-subtle rounded-pill' : '' }}"
                            href="{{ route('materials.all') }}">
                            🧶 Inventário
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link fs-6 fw-medium px-3 {{ request()->routeIs('users.*') ? 'active text-pink bg-pink-subtle rounded-pill' : '' }}"
                            href="{{ route('users.all') }}">
                            🦋 Utilizadores
                        </a>
                    </li>

                    <!-- Precificação => apenas para admin -->
                    @if (auth()->check() && auth()->user()->role === 'admin')
                        <li class="nav-item">
                            <a class="nav-link fs-6 fw-medium px-3 {{ request()->routeIs('pricing.*') ? 'active text-pink bg-pink-subtle rounded-pill' : '' }}"
                                href="{{ route('pricing.index') }}">
                                👛 Precificação
                            </a>
                        </li>
                    @endif
                </ul>

                <!-- Perfil & Logout -->
                <div class="d-flex gap-2 align-items-center">

                    <a href="{{ route('users.edit', auth()->id()) }}"
                        class="badge bg-pink-subtle text-pink fs-6 px-3 py-2 rounded-pill me-2 text-decoration-none"
                        title="Ver/Editar o meu perfil">
                        @if (auth()->user()->isAdmin())
                            👑
                        @elseif(auth()->user()->isMod())
                            🎀
                        @endif
                        {{ auth()->user()->name }}
                    </a>

                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger rounded-pill px-3 py-2">
                            Sair
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <!-- Conteúdo das páginas Admin -->
    <main class="container">
        @yield('content')
    </main>

    <footer class="text-center py-4 text-muted mt-5 border-top bg-white">
        <p class="mb-0">BACKOFFICE 🩷 <strong>MyMishi</strong> &copy; {{ date('Y') }}</p>
    </footer>

    <!-- Bootstrap 5.3.9 JS -->
    <script src="https://jsdelivr.net" integrity="sha384-WyOxWvTyr1QaMPfiPJSv76iReVthorl5uWlFtZPyK0IEB0hGzo9y5Bp9mYanr8Bu"
        crossorigin="anonymous"></script>
    @include('partials.inactivity-logout')
</body>

</html>
