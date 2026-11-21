@extends(auth()->user()->isStaff() ? 'layouts.admin' : 'layouts.app')

@section('content')
<div class="row justify-content-center py-3">
    <div class="col-md-8 col-lg-6">
        <div class="card shadow-sm">
            <!-- Cabeçalho do Cartão -->
            <div class="card-header bg-pink-subtle py-4 text-center border-0">
                <h4 class="fw-bold mb-1 text-pink">✏️ Editar Utilizador</h4>
                <div class="d-flex justify-content-center gap-2 mt-2">
                    <span class="badge bg-white text-dark border rounded-pill px-3 py-1">ID: {{ $userInfo->id }}</span>
                    <span class="badge bg-white text-pink border border-pink rounded-pill px-3 py-1">
                        NIF: {{ $userInfo->nif ?? 'Não definido' }}
                    </span>
                </div>
            </div>

            <!-- Corpo do Formulário -->
            <div class="card-body p-4 p-md-5 bg-white">
                <form method="POST" action="{{ route('users.update', $userInfo->id) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <!-- Nome -->
                    <div class="mb-3">
                        <label for="name" class="form-label fw-medium">Nome</label>
                        <input type="text"
                               id="name"
                               name="name"
                               class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name', $userInfo->name) }}"
                               required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="mb-3">
                        <label for="email" class="form-label fw-medium">Email</label>
                        <input type="email"
                               id="email"
                               name="email"
                               class="form-control bg-light @error('email') is-invalid @enderror"
                               value="{{ old('email', $userInfo->email) }}"
                               readonly>
                        <div class="form-text small">O endereço de email não pode ser alterado aqui.</div>
                    </div>

                    <!-- Morada e NIF lado a lado -->
                    <div class="row">
                        <div class="col-md-7 mb-3">
                            <label for="address" class="form-label fw-medium">Morada</label>
                            <input type="text"
                                   id="address"
                                   name="address"
                                   class="form-control @error('address') is-invalid @enderror"
                                   value="{{ old('address', $userInfo->address) }}"
                                   placeholder="Rua, Código Postal, Localidade">
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-5 mb-3">
                            <label for="nif" class="form-label fw-medium">NIF</label>
                            <input type="text"
                                   id="nif"
                                   name="nif"
                                   maxlength="9"
                                   class="form-control @error('nif') is-invalid @enderror"
                                   value="{{ old('nif', $userInfo->nif) }}"
                                   placeholder="123456789">
                            @error('nif')
     x                           <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Cargo / Role (Visível apenas para ADMIN) -->
                    @if(auth()->user()->role === 'admin')
                        <div class="mb-3">
                            <label for="role" class="form-label fw-medium">Perfil de Acesso</label>
                            <select id="role" name="role" class="form-select">
                                <option value="user" {{ ($userInfo->role ?? '') === 'user' ? 'selected' : '' }}>User (Cliente)</option>
                                <option value="mod" {{ ($userInfo->role ?? '') === 'mod' ? 'selected' : '' }}>Mod (Gestão de Stock)</option>
                                <option value="admin" {{ ($userInfo->role ?? '') === 'admin' ? 'selected' : '' }}>Admin (Acesso Total)</option>
                            </select>
                        </div>
                    @endif

                    <div class="mb-4">
    <label class="form-label fw-medium">Escolhe um Avatar ou carrega uma foto</label>

    <!-- Grelha de Avatares Predefinidos -->
<div class="avatar-scroll-box d-flex flex-wrap gap-3 mb-3 p-3 rounded-4 bg-pink-subtle justify-content-center">
    @foreach(range(1, 17) as $num)
        @php
            $avatar = str_pad($num, 2, '0', STR_PAD_LEFT) . '.png';
        @endphp
        <label class="avatar-option text-center" style="cursor: pointer;">
            <input type="radio" name="avatar" value="images/avatars/{{ $avatar }}" class="d-none"
                   {{ $userInfo->photo === "images/avatars/{$avatar}" ? 'checked' : '' }}>
            <img src="{{ asset('images/avatars/' . $avatar) }}" alt="Avatar {{ $num }}" class="profile-preview avatar-img">
        </label>
    @endforeach
</div>

    <!-- Upload do Computador (Alternativa) -->
    <div class="d-flex align-items-center gap-3">
        <input type="file" id="photo" name="photo" accept="image/*" class="form-control form-control-sm">
    </div>
    <small class="text-muted">Se carregares uma foto do computador, ela substitui o avatar selecionado.</small>
</div>



                    <!-- Botões de Ação -->
                    <div class="d-flex justify-content-between align-items-center pt-2">
                        <a href="{{ route('users.all') }}" class="btn btn-outline-secondary rounded-pill px-4">
                            Voltar
                        </a>
                        <button type="submit" class="btn btn-pink rounded-pill px-5 shadow-sm">
                            Atualizar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
