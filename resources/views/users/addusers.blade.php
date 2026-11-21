@extends(auth()->user()?->isStaff() ? 'layouts.admin' : 'layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-6">
            <div class="card shadow-sm">
                <!-- Cabeçalho do Cartão -->
                <div class="card-header bg-pink-subtle py-4 text-center border-0">
                    <h3 class="fw-bold mb-1 text-pink">✨ Criar Conta</h3>
                    <p class="text-muted small mb-0">Preenche os teus dados para te registares na <strong>mymishi</strong></p>
                </div>

                <!-- Corpo do Formulário -->
                <div class="card-body p-4 p-md-5 bg-white">
                    <form method="POST" action="{{ route('users.store') }}">
                        @csrf


                        <div class="mb-3">
                            <label for="name" class="form-label fw-medium">Username <span class="text-danger">*</span></label>
                            <input type="text"
                                   id="name"
                                   name="name"
                                   class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name') }}"
                                   placeholder="Ex: Ana Silva"
                                   required
                                   autofocus>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="mb-3">
                            <label for="email" class="form-label fw-medium">Email <span class="text-danger">*</span></label>
                            <input type="email"
                                   id="email"
                                   name="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   value="{{ old('email') }}"
                                   placeholder="ana@exemplo.pt"
                                   required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>


                        <!-- Password e Confirmação -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label fw-medium">Password <span class="text-danger">*</span></label>
                                <input type="password"
                                       id="password"
                                       name="password"
                                       class="form-control @error('password') is-invalid @enderror"
                                       placeholder="Mínimo 8 caracteres"
                                       required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-4">
                                <label for="password_confirmation" class="form-label fw-medium">Confirmar Password <span class="text-danger">*</span></label>
                                <input type="password"
                                       id="password_confirmation"
                                       name="password_confirmation"
                                       class="form-control"
                                       placeholder="Repete a password"
                                       required>
                            </div>
                        </div>

                        <!-- Botão Submeter -->
                        <button type="submit" class="btn btn-pink w-100 py-2 rounded-pill shadow-sm fs-6">
                            Registar Conta
                        </button>

                        <!-- Link para Login (apenas se for visitante) -->
                        @guest
                            <div class="text-center mt-4 small text-muted">
                                Já tens uma conta?
                                <a href="{{ route('login') }}" class="text-pink fw-semibold text-decoration-none">
                                    Iniciar Sessão
                                </a>
                            </div>
                        @endguest
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
