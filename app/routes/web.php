<?php

use App\Support\PageRouter;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| File-based Auto Routes (pages/**)
|--------------------------------------------------------------------------
|
| PageRouter scans src/pages/** and registers a GET route for every Blade
| file automatically. Conventions:
|
|   pages/home.blade.php           →  GET /home        (name: pages.home)
|   pages/index.blade.php          →  GET /            (name: pages)
|   pages/about/index.blade.php    →  GET /about       (name: pages.about)
|   pages/about/us-me.blade.php    →  GET /about/us-me (name: pages.about.us-me)
|   pages/blog/[slug].blade.php    →  GET /blog/{slug} (name: pages.blog.slug)
|
| You can still define manual routes BELOW to override any auto-generated
| route — Laravel processes routes in registration order, and named manual
| routes will win because they're explicit.
|
*/

PageRouter::register([
    'exclude' => [
        'index', 'login', 'dashboard',
        'categories', 'categories/*', 'barangs', 'barangs/*',
        'warehouses', 'warehouses/*', 'customers', 'customers/*',
        'transactions', 'transactions/*', 'reports', 'reports/*',
    ],
    'middleware' => ['web', 'auth'],
]);

Route::redirect('/', '/dashboard')->middleware('auth')->name('home');

/*
|--------------------------------------------------------------------------
| Manual Route Overrides
|--------------------------------------------------------------------------
|
| Place any route that needs custom data, middleware, or logic here.
| Manual routes registered after PageRouter::register() will override
| the auto-generated equivalent.
|
| Example:
|
|   Route::get('/home', fn () => page('home', [
|       'title' => config('app.name').' | Welcome',
|       'description' => 'The best app ever.',
|   ]))->name('pages.home');
|
*/

Route::middleware('guest')->group(function () {
    Route::get('/login', [\App\Http\Controllers\Auth\LoginController::class, 'create'])->name('login');
    Route::post('/login', [\App\Http\Controllers\Auth\LoginController::class, 'store'])->name('login.store');
});

Route::post('/logout', [\App\Http\Controllers\Auth\LoginController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

Route::put('/dashboard/profile', [\App\Http\Controllers\ProfileController::class, 'update'])
    ->middleware('auth')
    ->name('profile.update');

Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])
    ->middleware('auth')
    ->name('pages.dashboard');

Route::middleware(['auth', 'role:admin,operator'])->group(function () {
    Route::get('/transactions', [\App\Http\Controllers\InventoryTransactionController::class, 'index'])
        ->name('transactions.index');
    Route::get('/transactions/receipts/create', [\App\Http\Controllers\InventoryTransactionController::class, 'createReceipt'])
        ->name('transactions.receipts.create');
    Route::post('/transactions/receipts', [\App\Http\Controllers\InventoryTransactionController::class, 'storeReceipt'])
        ->name('transactions.receipts.store');
    Route::get('/transactions/sales/create', [\App\Http\Controllers\InventoryTransactionController::class, 'createSale'])
        ->name('transactions.sales.create');
    Route::post('/transactions/sales', [\App\Http\Controllers\InventoryTransactionController::class, 'storeSale'])
        ->name('transactions.sales.store');
    Route::get('/transactions/transfers/create', [\App\Http\Controllers\InventoryTransactionController::class, 'createTransfer'])
        ->name('transactions.transfers.create');
    Route::post('/transactions/transfers', [\App\Http\Controllers\InventoryTransactionController::class, 'storeTransfer'])
        ->name('transactions.transfers.store');
    Route::get('/transactions/stock-availability', [\App\Http\Controllers\InventoryTransactionController::class, 'stockAvailability'])
        ->name('transactions.stock-availability');
    Route::get('/transactions/{transaction}', [\App\Http\Controllers\InventoryTransactionController::class, 'show'])
        ->name('transactions.show');
    Route::post('/transactions/{transaction}/cancel', [\App\Http\Controllers\InventoryTransactionController::class, 'cancel'])
        ->name('transactions.cancel');
});

Route::get('/reports', [\App\Http\Controllers\ReportController::class, 'index'])
    ->middleware(['auth', 'role:admin'])
    ->name('reports.index');

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::resource('categories', \App\Http\Controllers\CategoryController::class);
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::resource('barangs', \App\Http\Controllers\BarangController::class);
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::resource('warehouses', \App\Http\Controllers\WarehouseController::class);
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::resource('customers', \App\Http\Controllers\CustomerController::class);
});
