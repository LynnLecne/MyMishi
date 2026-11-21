<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PricingController extends Controller
{
    public function index(Request $request)
    {
        abort_unless(Auth::check() && Auth::user()->isAdmin(), 403);

        $search = $request->query('search');
        $query = Product::with(['category', 'pricing']);

        if ($search) {
            $query->where('name', 'LIKE', "%{$search}%");
        }

        $products = $query->orderBy('name')->paginate(10)->withQueryString();

        return view('pricing.index', compact('products'));
    }

 public function edit(Product $product)
{
    abort_unless(Auth::check() && Auth::user()->isAdmin(), 403);

    $pricing = $product->pricing;
    $materials = Material::orderBy('name')->paginate(10)->withQueryString();
    $usedMaterials = $product->materials->pluck('pivot.quantity_used', 'id')->toArray();

    // Vai buscar o último valor/hora usado noutro produto (ou 8.00€ por defeito)
    $defaultHourlyRate = \App\Models\Pricing::latest('updated_at')->value('hourly_rate') ?? 8.00;

    return view('pricing.edit', compact('product', 'pricing', 'materials', 'usedMaterials', 'defaultHourlyRate'));
}

    public function update(Request $request, Product $product)
{
    abort_unless(Auth::check() && Auth::user()->isAdmin(), 403);

    $request->validate([
        'materials_cost' => 'required|numeric|min:0',
        'hourly_rate'    => 'required|numeric|min:0',
        'hours_spent'    => 'required|numeric|min:0',
        'labor_cost'     => 'required|numeric|min:0',
        'packaging_cost' => 'required|numeric|min:0',
        'profit_margin'  => 'required|numeric|min:0',
        'final_price'    => 'required|numeric|min:0',
        'materials'      => 'nullable|array',
        'materials.*'    => 'nullable|numeric|min:0',
    ]);

    $syncData = [];
    if ($request->has('materials')) {
        foreach ($request->materials as $materialId => $qty) {
            if ($qty > 0) {
                $syncData[$materialId] = ['quantity_used' => $qty];
            }
        }
    }
    $product->materials()->sync($syncData);

    $product->pricing()->updateOrCreate(
        ['product_id' => $product->id],
        [
            'materials_cost' => $request->materials_cost,
            'hourly_rate'    => $request->hourly_rate,
            'hours_spent'    => $request->hours_spent,
            'labor_cost'     => $request->labor_cost,
            'packaging_cost' => $request->packaging_cost,
            'profit_margin'  => $request->profit_margin,
            'final_price'    => $request->final_price,
        ]
    );

    return redirect()->route('pricing.index')->with('message', 'Precificação de "' . $product->name . '" guardada com sucesso!');
}
}
