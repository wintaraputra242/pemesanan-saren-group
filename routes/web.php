<?php

use App\Livewire\Frontend\CartDrawer;
use App\Livewire\Frontend\CheckoutPage;
use App\Livewire\Frontend\HomePage;
use App\Livewire\Frontend\OrderTracker;
use App\Livewire\Frontend\ProductCatalog;
use App\Livewire\Frontend\ProductDetail;
use Illuminate\Support\Facades\Route;

Route::get('/', HomePage::class)->name('home');
Route::get('/katalog', ProductCatalog::class)->name('catalog');
Route::get('/produk/{slug}', ProductDetail::class)->name('product.detail');
Route::get('/keranjang', CartDrawer::class)->name('cart');
Route::get('/checkout', CheckoutPage::class)->name('checkout');
Route::get('/track/{invoice}', OrderTracker::class)->name('order.track');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
});

require __DIR__.'/settings.php';
