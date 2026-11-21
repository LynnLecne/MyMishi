<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

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
        $images = Category::AVAILABLE_IMAGES;

        return view('categories.create', compact('images'));
    }

    public function store(Request $request)
    {
        abort_unless(Auth::check() && Auth::user()->isStaff(), 403);

        $validated = $request->validate([
            'name'         => ['required', 'string', 'max:255', 'unique:categories,name'],
            'preset_image' => ['required_without:custom_image', 'nullable', 'string', Rule::in(Category::AVAILABLE_IMAGES)],
            'custom_image' => ['nullable', 'image', 'max:2048'],
        ]);

        if ($request->hasFile('custom_image')) {
            $imagePath = 'storage/' . $request->file('custom_image')->store('categories', 'public');
        } else {
            $imagePath = 'images/categories/' . $validated['preset_image'];
        }

        Category::create([
            'name'    => $validated['name'],
            'image'   => $imagePath,
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('catalog.index')->with('message', 'Categoria criada com sucesso!');
    }

    public function edit(Category $category)
    {
        abort_unless(Auth::check() && Auth::user()->isStaff(), 403);
        $images = Category::AVAILABLE_IMAGES;

        return view('categories.edit', compact('category', 'images'));
    }

    public function update(Request $request, Category $category)
    {
        abort_unless(Auth::check() && Auth::user()->isStaff(), 403);

        $validated = $request->validate([
            'name'         => ['required', 'string', 'max:255', Rule::unique('categories', 'name')->ignore($category->id)],
            'preset_image' => ['nullable', 'string', Rule::in(Category::AVAILABLE_IMAGES)],
            'custom_image' => ['nullable', 'image', 'max:2048'],
        ]);

        $imagePath = $category->image;

        if ($request->hasFile('custom_image')) {
            if ($category->image && str_starts_with($category->image, 'storage/')) {
                Storage::disk('public')->delete(str_replace('storage/', '', $category->image));
            }
            $imagePath = 'storage/' . $request->file('custom_image')->store('categories', 'public');
        } elseif (!empty($validated['preset_image'])) {
            if ($category->image && str_starts_with($category->image, 'storage/')) {
                Storage::disk('public')->delete(str_replace('storage/', '', $category->image));
            }
            $imagePath = 'images/categories/' . $validated['preset_image'];
        }

        $category->update([
            'name'  => $validated['name'],
            'image' => $imagePath,
        ]);

        return redirect()->route('catalog.index')->with('message', 'Categoria atualizada com sucesso!');
    }

    public function destroy(Category $category)
    {
        abort_unless(Auth::check() && Auth::user()->isAdmin(), 403);

        if ($category->image && str_starts_with($category->image, 'storage/')) {
            Storage::disk('public')->delete(str_replace('storage/', '', $category->image));
        }

        $category->delete();

        return back()->with('message', 'Categoria apagada com sucesso!');
    }
}
