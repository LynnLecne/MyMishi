@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow-sm">
                <div class="card-header bg-pink-subtle py-3 text-center border-0">
                    <h4 class="fw-bold mb-0 text-pink">Recuperar Password</h4>
                </div>
                <div class="card-body p-4 bg-white">
                    <p class="text-muted small mb-3">
                        Indica o teu endereço de email e enviaremos um link para definires uma nova password.
                    </p>

                    @if (session('status'))
                        <div class="alert alert-success small mb-3" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-medium">Email</label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required autofocus>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-pink w-100 py-2">Enviar Link de Recuperação</button>
                        <div class="text-center mt-3">
                            <a href="{{ route('login') }}" class="small text-pink text-decoration-none">Voltar ao Login</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
