@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow-sm">
                <div class="card-header bg-pink-subtle py-3 text-center border-0">
                    <h4 class="fw-bold mb-0 text-pink">Iniciar Sessão</h4>
                </div>
                <div class="card-body p-4 bg-white">
                    @if (session('status'))
                        <div class="alert alert-success small mb-3" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-medium">Email ou Utilizador</label>
                            <input type="text" name="login" class="form-control @error('login') is-invalid @enderror" value="{{ old('login') }}" required autofocus>
                            @error('login')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <label class="form-label fw-medium mb-1">Password</label>
                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}" class="small text-pink text-decoration-none">
                                        Esqueceste-te da password?
                                    </a>
                                @endif
                            </div>
                            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-pink w-100 py-2">Entrar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
