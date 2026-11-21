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
                            <label class="form-label fw-medium">Password</label>
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
