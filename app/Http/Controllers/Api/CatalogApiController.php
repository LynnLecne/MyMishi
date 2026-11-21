<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\JsonResponse;

class CatalogApiController extends Controller
{
    /**
     * Devolve todas as categorias com os respetivos artigos.
     */
    public function index(): JsonResponse
    {
        $categories = Category::with(['products' => function ($query) {
            $query->select('id', 'category_id', 'name', 'image', 'quantity')
                  ->orderBy('name');
        }])
        ->select('id', 'name', 'image')
        ->orderBy('name')
        ->get()
        ->map(function ($category) {
            return [
                'id' => $category->id,
                'name' => $category->name,
                'image_url' => $this->resolveImageUrl($category->image, 'categories'),
                'products_count' => $category->products->count(),
                'products' => $category->products->map(function ($product) {
                    return [
                        'id' => $product->id,
                        'name' => $product->name,
                        'image_url' => $this->resolveImageUrl($product->image, 'products'),
                        'quantity' => $product->quantity,
                        'in_stock' => $product->quantity > 0,
                    ];
                }),
            ];
        });

        return response()->json([
            'store' => 'mymishi',
            'exported_at' => now()->toIso8601String(),
            'total_categories' => $categories->count(),
            'data' => $categories,
        ]);
    }

    /**
     * Devolve apenas a lista plana de artigos com a respetiva categoria.
     */
    public function products(): JsonResponse
    {
        $products = Product::with('category:id,name')
            ->select('id', 'category_id', 'name', 'image', 'quantity')
            ->orderBy('name')
            ->get()
            ->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'category' => $product->category?->name,
                    'image_url' => $this->resolveImageUrl($product->image, 'products'),
                    'quantity' => $product->quantity,
                    'in_stock' => $product->quantity > 0,
                ];
            });

        return response()->json([
            'store' => 'mymishi',
            'total_products' => $products->count(),
            'data' => $products,
        ]);
    }

    /**
     * Resolve o URL completo da imagem (galeria predefinida vs storage upload).
     */
    private function resolveImageUrl(?string $image, string $folder): ?string
    {
        if (!$image) {
            return null;
        }

        // Se já for um caminho relativo ao storage ou se existir na galeria pública
        if (file_exists(public_path("images/{$folder}/{$image}"))) {
            return asset("images/{$folder}/{$image}");
        }

        return asset("storage/{$image}");
    }
}
