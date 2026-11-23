<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function create(Request $request)
    {
        abort_unless(Auth::check() && Auth::user()->isStaff(), 403);

        $categories = Category::orderBy('name')->paginate(10)->withQueryString();
        $selectedCategory = $request->query('category_id');

        return view('products.create', compact('categories', 'selectedCategory'));
    }

    public function store(Request $request)
    {
        abort_unless(Auth::check() && Auth::user()->isStaff(), 403);

        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name'        => 'required|string|max:255',
            'quantity'    => 'required|integer|min:0',
            'image'       => 'nullable|image|max:2048',
        ]);

        $imagePath = $request->hasFile('image')
            ? 'storage/' . $request->file('image')->store('products', 'public')
            : null;

        $product = Product::create([
            'category_id' => $request->category_id,
            'user_id'     => Auth::id(),
            'name'        => $request->name,
            'quantity'    => $request->quantity,
            'image'       => $imagePath,
        ]);

        return redirect()->route('categories.show', $product->category_id)
            ->with('message', 'Artigo adicionado com sucesso!');
    }

    public function edit(Product $product)
    {
        abort_unless(Auth::check() && Auth::user()->isStaff(), 403);
        $categories = Category::orderBy('name')->get();

        return view('products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        abort_unless(Auth::check() && Auth::user()->isStaff(), 403);

        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name'        => 'required|string|max:255',
            'quantity'    => 'required|integer|min:0',
            'image'       => 'nullable|image|max:2048',
        ]);

        $imagePath = $product->image;
        if ($request->hasFile('image')) {
            $imagePath = 'storage/' . $request->file('image')->store('products', 'public');
        }

        $product->update([
            'category_id' => $request->category_id,
            'name'        => $request->name,
            'quantity'    => $request->quantity,
            'image'       => $imagePath,
        ]);

        return redirect()->route('categories.show', $product->category_id)
            ->with('message', 'Artigo actualizado com sucesso!');
    }

    public function destroy(Product $product)
    {
        abort_unless(Auth::check() && Auth::user()->isAdmin(), 403);
        $product->delete();

        return back()->with('message', 'Artigo apagado com sucesso!');
    }
}
