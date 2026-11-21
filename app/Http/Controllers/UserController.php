<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
 public function index()
{
    $searchPar = request()->query('search');
    $query = User::select('id', 'name', 'address', 'email', 'nif', 'photo', 'role');

    if ($searchPar) {
        $query->where('name', 'LIKE', "%{$searchPar}%")
              ->orWhere('email', 'LIKE', "%{$searchPar}%");
    }

    $users = $query->paginate(10)->withQueryString();

    return view('users.allusers', compact('users'));
}

    public function create()
    {
        return view('users.addusers');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:50',
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'role'     => 'user',
            'password' => Hash::make($request->password),
            'photo'    => 'images/avatars/17.png',
        ]);

        Auth::login($user);

        return redirect()->route('catalog.index')->with('message', 'Conta criada com sucesso!');
    }

    public function edit(User $user)
    {
        // Admin/Mod editam qualquer um; User só edita o próprio
        abort_unless(Auth::user()->isStaff() || Auth::id() === $user->id, 403);

        $userInfo = $user;

        return view('users.editusers', compact('userInfo'));
    }

    public function update(Request $request, User $user)
    {
        abort_unless(Auth::user()->isStaff() || Auth::id() === $user->id, 403);

        $request->validate([
            'name'    => 'required|string|max:50',
            'email'   => 'required|email|max:50|unique:users,email,' . $user->id,
            'address' => 'nullable|string|max:255',
            'nif'     => 'nullable|string|max:9',
            'photo'   => 'nullable|image|max:2048',
        ]);

        $photoPath = $user->photo;

        if ($request->hasFile('photo')) {
            $photoPath = 'storage/' . $request->file('photo')->store('userPhotos', 'public');
        } elseif ($request->filled('avatar')) {
            $photoPath = $request->avatar;
        }

        $data = [
            'name'    => $request->name,
            'email'   => $request->email,
            'address' => $request->address,
            'nif'     => $request->nif,
            'photo'   => $photoPath,
        ];

        // Apenas o admin pode alterar o perfil (role)
        if (Auth::user()->isAdmin() && $request->has('role')) {
            $data['role'] = $request->role;
        }

        $user->update($data);

        return redirect()->route('users.all')->with('message', 'Utilizador actualizado com sucesso!');
    }

    public function destroy(User $user)
    {
        // Só o admin pode apagar (e não se apaga a si próprio)
        abort_unless(Auth::user()->isAdmin() && Auth::id() !== $user->id, 403);

        $user->delete();

        return back()->with('message', 'Utilizador apagado com sucesso!');
    }
}
