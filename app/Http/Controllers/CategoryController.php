<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
   public function index()
{
    $categories = Category::withCount('products')
        ->orderBy('name')
        ->paginate(10)
        ->withQueryString();

    return view('categories.index', compact('categories'));
}

public function show(Category $category)
{
    $products = $category->products()
        ->orderBy('name')
        ->paginate(10)
        ->withQueryString();

    return view('categories.show', compact('category', 'products'));
}

    public function create()
    {
        abort_unless(Auth::check() && Auth::user()->isStaff(), 403);
        return view('categories.create');
    }

    public function store(Request $request)
    {
        abort_unless(Auth::check() && Auth::user()->isStaff(), 403);

        $request->validate([
            'name'  => 'required|string|max:255|unique:categories,name',
            'image' => 'nullable|image|max:2048',
        ]);

        $imagePath = $request->hasFile('image')
            ? 'storage/' . $request->file('image')->store('categories', 'public')
            : null;

        Category::create([
            'name'    => $request->name,
            'image'   => $imagePath,
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('catalog.index')->with('message', 'Categoria criada com sucesso!');
    }

    public function edit(Category $category)
    {
        abort_unless(Auth::check() && Auth::user()->isStaff(), 403);
        return view('categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        abort_unless(Auth::check() && Auth::user()->isStaff(), 403);

        $request->validate([
            'name'  => 'required|string|max:255|unique:categories,name,' . $category->id,
            'image' => 'nullable|image|max:2048',
        ]);

        $imagePath = $category->image;
        if ($request->hasFile('image')) {
            $imagePath = 'storage/' . $request->file('image')->store('categories', 'public');
        }

        $category->update([
            'name'  => $request->name,
            'image' => $imagePath,
        ]);

        return redirect()->route('catalog.index')->with('message', 'Categoria atualizada com sucesso!');
    }

    public function destroy(Category $category)
    {
        abort_unless(Auth::check() && Auth::user()->isAdmin(), 403);
        $category->delete();

        return back()->with('message', 'Categoria apagada com sucesso!');
    }
}
