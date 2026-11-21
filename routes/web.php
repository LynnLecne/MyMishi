<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PricingController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| 1. ÁREA PÚBLICA (Loja / Catálogo & Registo)
|--------------------------------------------------------------------------
*/
// Catálogo Público (Todos veem)
Route::get('/', [CategoryController::class, 'index'])->name('catalog.index');

// Gestão de Categorias e Artigos (Mod e Admin)
Route::middleware(['auth'])->group(function () {
    // Categorias
    Route::get('/categories/create', [CategoryController::class, 'create'])->name('categories.create');
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::get('/categories/{category}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
    Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.delete');

    // Artigos / Produtos
    Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.delete');


    // Inventário / Matéria-Prima (Mod e Admin)
    Route::prefix('admin/materials')->name('materials.')->group(function () {
        Route::get('/', [MaterialController::class, 'index'])->name('all');
        Route::get('/create', [MaterialController::class, 'create'])->name('create');
        Route::post('/', [MaterialController::class, 'store'])->name('store');
        Route::get('/{material}/edit', [MaterialController::class, 'edit'])->name('edit');
        Route::put('/{material}', [MaterialController::class, 'update'])->name('update');
        Route::delete('/{material}', [MaterialController::class, 'destroy'])->name('delete');
    });

        // Precificação (Exclusivo Admin)
    Route::prefix('admin/pricing')->name('pricing.')->group(function () {
        Route::get('/', [PricingController::class, 'index'])->name('index');
        Route::get('/{product}/edit', [PricingController::class, 'edit'])->name('edit');
        Route::put('/{product}', [PricingController::class, 'update'])->name('update');
    });

});

// Ver produtos de uma categoria (Colocar DEPOIS de /categories/create)
Route::get('/categories/{category}', [CategoryController::class, 'show'])->name('categories.show');


// Registo de novos utilizadores (aberto a todos)
Route::get('/users/addusers', [UserController::class, 'create'])->name('users.create');
Route::post('/users', [UserController::class, 'store'])->name('users.store');


/*
|--------------------------------------------------------------------------
| 2. REDIRECIONAMENTO APÓS LOGIN (/home)
|--------------------------------------------------------------------------
*/
Route::get('/home', function () {
    if (in_array(auth()->user()->role, ['admin', 'mod'])) {
        return redirect()->route('dashboard');
    }

    return redirect()->route('catalog.index');
})->middleware('auth')->name('home');


/*
|--------------------------------------------------------------------------
| 3. ÁREA DE BACKOFFICE (Utilizadores Autenticados: Admin & Mod)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

   Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');


    // Gestão de Utilizadores (nomes alinhados com o teu UserController)
    Route::prefix('admin/users')->name('users.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('all');
        Route::get('/{user}/edit', [UserController::class, 'edit'])->name('edit');
        Route::put('/{user}', [UserController::class, 'update'])->name('update');
        Route::delete('/{user}', [UserController::class, 'destroy'])->name('delete');
    });

    // Futuras rotas de Inventário (Peças Prontas e Materiais)
    // Route::prefix('admin/inventory')->name('inventory.')->group(function () { ... });

    // Futuras rotas de Precificação (Apenas Admin)
    // Route::prefix('admin/pricing')->name('pricing.')->group(function () { ... });
});
