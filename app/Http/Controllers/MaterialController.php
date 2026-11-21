<?php

namespace App\Http\Controllers;

use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MaterialController extends Controller
{
    public function index(Request $request)
    {
        abort_unless(Auth::check() && Auth::user()->isStaff(), 403);

        $search = $request->query('search');
        $query = Material::query();

        if ($search) {
            $query->where('name', 'LIKE', "%{$search}%");

        }

        $materials = $query->orderBy('name')->paginate(10)->withQueryString();

        return view('materials.index', compact('materials'));
    }

    public function create()
    {
        abort_unless(Auth::check() && Auth::user()->isStaff(), 403);

        return view('materials.create');
    }

    public function store(Request $request)
    {
        abort_unless(Auth::check() && Auth::user()->isStaff(), 403);

        $request->validate([
            'name'      => 'required|string|max:255',
            'unit'      => 'required|string|max:30',
            'quantity'  => 'required|numeric|min:0',
            'unit_cost' => 'required|numeric|min:0',
            'image'     => 'nullable|image|max:2048',
        ]);

        $imagePath = $request->hasFile('image')
            ? 'storage/' . $request->file('image')->store('materials', 'public')
            : null;

        Material::create([
            'user_id'   => Auth::id(),
            'name'      => $request->name,
            'unit'      => $request->unit,
            'quantity'  => $request->quantity,
            'unit_cost' => $request->unit_cost,
            'image'     => $imagePath,
        ]);

        return redirect()->route('materials.all')->with('message', 'Material adicionado com sucesso!');
    }

    public function edit(Material $material)
    {
        abort_unless(Auth::check() && Auth::user()->isStaff(), 403);

        return view('materials.edit', compact('material'));
    }

    public function update(Request $request, Material $material)
    {
        abort_unless(Auth::check() && Auth::user()->isStaff(), 403);

        $request->validate([
            'name'      => 'required|string|max:255',
            'unit'      => 'required|string|max:30',
            'quantity'  => 'required|numeric|min:0',
            'unit_cost' => 'required|numeric|min:0',
            'image'     => 'nullable|image|max:2048',
        ]);

        $imagePath = $material->image;
        if ($request->hasFile('image')) {
            $imagePath = 'storage/' . $request->file('image')->store('materials', 'public');
        }

        $material->update([
            'name'      => $request->name,
            'unit'      => $request->unit,
            'quantity'  => $request->quantity,
            'unit_cost' => $request->unit_cost,
            'image'     => $imagePath,
        ]);

        return redirect()->route('materials.all')->with('message', 'Material atualizado com sucesso!');
    }

    public function destroy(Material $material)
    {
        abort_unless(Auth::check() && Auth::user()->isAdmin(), 403);

        $material->delete();

        return back()->with('message', 'Material apagado com sucesso!');
    }
}
