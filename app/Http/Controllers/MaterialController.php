<?php

namespace App\Http\Controllers;

use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class MaterialController extends Controller
{
    public function index(Request $request)
    {
        abort_unless(Auth::check() && Auth::user()->isStaff(), 403);

        $search = $request->input('search');

        $materials = Material::when($search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('materials.index', compact('materials'));
    }

    public function create()
    {
        abort_unless(Auth::check() && Auth::user()->isStaff(), 403);
        $images = Material::AVAILABLE_IMAGES;

        return view('materials.create', compact('images'));
    }

    public function store(Request $request)
    {
        abort_unless(Auth::check() && Auth::user()->isStaff(), 403);

        $validated = $request->validate([
            'name'         => ['required', 'string', 'max:255', 'unique:materials,name'],
            'unit'         => ['required', 'string', Rule::in(['un.', 'm'])],
            'quantity'     => ['required', 'numeric', 'min:0'],
            'unit_cost'    => ['required', 'numeric', 'min:0'],
            'preset_image' => ['required_without:custom_image', 'nullable', 'string', Rule::in(Material::AVAILABLE_IMAGES)],
            'custom_image' => ['nullable', 'image', 'max:2048'],
        ]);

        if ($request->hasFile('custom_image')) {
            $imagePath = 'storage/' . $request->file('custom_image')->store('materials', 'public');
        } else {
            $imagePath = 'images/materials/' . $validated['preset_image'];
        }

        Material::create([
            'name'      => $validated['name'],
            'unit'      => $validated['unit'],
            'quantity'  => $validated['quantity'],
            'unit_cost' => $validated['unit_cost'],
            'image'     => $imagePath,
            'user_id'   => Auth::id(),
        ]);

        return redirect()->route('materials.all')->with('message', 'Material adicionado com sucesso!');
    }

    public function edit(Material $material)
    {
        abort_unless(Auth::check() && Auth::user()->isStaff(), 403);
        $images = Material::AVAILABLE_IMAGES;

        return view('materials.edit', compact('material', 'images'));
    }

    public function update(Request $request, Material $material)
    {
        abort_unless(Auth::check() && Auth::user()->isStaff(), 403);

        $validated = $request->validate([
            'name'         => ['required', 'string', 'max:255', Rule::unique('materials', 'name')->ignore($material->id)],
            'unit'         => ['required', 'string', 'max:50'],
            'quantity'     => ['required', 'numeric', 'min:0'],
            'unit_cost'    => ['required', 'numeric', 'min:0'],
            'preset_image' => ['nullable', 'string', Rule::in(Material::AVAILABLE_IMAGES)],
            'custom_image' => ['nullable', 'image', 'max:2048'],
        ]);

        $imagePath = $material->image;

        if ($request->hasFile('custom_image')) {
            if ($material->image && str_starts_with($material->image, 'storage/')) {
                Storage::disk('public')->delete(str_replace('storage/', '', $material->image));
            }
            $imagePath = 'storage/' . $request->file('custom_image')->store('materials', 'public');
        } elseif (!empty($validated['preset_image'])) {
            if ($material->image && str_starts_with($material->image, 'storage/')) {
                Storage::disk('public')->delete(str_replace('storage/', '', $material->image));
            }
            $imagePath = 'images/materials/' . $validated['preset_image'];
        }

        $material->update([
            'name'      => $validated['name'],
            'unit'      => $validated['unit'],
            'quantity'  => $validated['quantity'],
            'unit_cost' => $validated['unit_cost'],
            'image'     => $imagePath,
        ]);

        return redirect()->route('materials.all')->with('message', 'Material atualizado com sucesso!');
    }

    public function destroy(Material $material)
    {
        abort_unless(Auth::check() && Auth::user()->isAdmin(), 403);

        if ($material->image && str_starts_with($material->image, 'storage/')) {
            Storage::disk('public')->delete(str_replace('storage/', '', $material->image));
        }

        $material->delete();

        return back()->with('message', 'Material apagado com sucesso!');
    }

    public function export()
{
    if (!auth()->user()?->isStaff()) {
        abort(403);
    }

    $fileName = 'inventario_mymishi_' . now()->format('Y-m-d_H-i') . '.xlsx';

    return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\MaterialsExport, $fileName);
}
}
