@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow-sm">
                <div class="card-header bg-pink-subtle py-3 text-center border-0">
                    <h4 class="fw-bold mb-0 text-pink">Nova Password</h4>
                </div>
                <div class="card-body p-4 bg-white">
                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf
                        <input type="hidden" name="token" value="{{ $request->route('token') }}">

                        <div class="mb-3">
                            <label class="form-label fw-medium">Email</label>
                            <input type="email" name="email" class="form-control bg-light text-muted @error('email') is-invalid @enderror" value="{{ old('email', $request->email) }}" readonly>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-medium">Nova Password</label>
                            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required autofocus autocomplete="new-password">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-medium">Confirmar Password</label>
                            <input type="password" name="password_confirmation" class="form-control" required autocomplete="new-password">
                        </div>

                        <button type="submit" class="btn btn-pink w-100 py-2">Guardar Nova Password</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
