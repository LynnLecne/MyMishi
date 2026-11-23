@extends(auth()->user()->isStaff() ? 'layouts.admin' : 'layouts.app')

@section('content')
<div class="container">
    @if (session('message'))
        <div class="alert alert-success">
            {{ session('message') }}
        </div>
    @endif

    <form method="GET" action="{{ url()->current() }}" class="d-flex align-items-center gap-2 mb-3" style="max-width: 450px;">
        <input type="text" name="search" class="form-control rounded-pill border-pink px-4 shadow-sm"
            placeholder="Pesquisar utilizador..." value="{{ request('search') }}">

        <button type="submit" class="btn btn-pink rounded-pill px-4 shadow-sm">
            Procurar
        </button>

        @if (request('search'))
            <a href="{{ url()->current() }}" class="btn btn-outline-pink rounded-pill px-3">
                Limpar
            </a>
        @endif
    </form>

    <table class="table table-hover align-middle mb-0">
        <thead>
            <tr>
                <th class="ps-4">Avatar</th>
                <th scope="col">#</th>
                <th scope="col">Name</th>
                <th scope="col">Email</th>
                @if (auth()->user()->isAdmin())
                    <th scope="col">Address</th>
                    <th scope="col">NIF</th>
                @endif
                <th class="text-end pe-4"></th>
            </tr>
        </thead>
        <tbody>
            @forelse ($users as $user)
                <tr>
                    <td class="ps-4">
                        <img class="stock-thumb" src="{{ asset($user->photo ?? 'images/avatars/17.png') }}"
                            alt="Avatar de {{ $user->name }}">
                    </td>
                    <th scope="row">{{ $user->id }}</th>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>

                    {{-- Address e NIF: só para admin --}}
                    @if (auth()->user()->isAdmin())
                        <td>{{ $user->address ?? '—' }}</td>
                        <td>{{ $user->nif ?? '—' }}</td>
                    @endif

                    <td class="text-end pe-4">
                        <div class="d-inline-flex gap-2">
                            {{-- Ver/Editar: admin/mod vêem de todos; user só vê o seu --}}
                            @if (auth()->user()->isStaff() || auth()->id() === $user->id)
                                <a href="{{ route('users.edit', $user->id) }}"
                                    class="btn btn-outline-pink btn-sm rounded-pill px-3">
                                    Ver/Editar
                                </a>
                            @endif

                            {{-- Apagar: só admin --}}
                            @if (auth()->user()->isAdmin() && auth()->id() !== $user->id)
                                <form action="{{ route('users.delete', $user->id) }}" method="POST" class="d-inline"
                                    onsubmit="return confirm('Tens a certeza que queres apagar este utilizador?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill px-3">
                                        Apagar
                                    </button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center py-4 text-muted">
                        Nenhum utilizador encontrado.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
    <div class="mt-4 px-2">
    {{ $users->links() }}
</div>
    </div>
@endsection
