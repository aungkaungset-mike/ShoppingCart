<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SliderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PdfController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/admin', [AdminController::class, 'admin']);

Route::get('/addcategory', [CategoryController::class, 'addcategory']);

Route::post('/savecategory', [CategoryController::class, 'savecategory']);

Route::get('/categories', [CategoryController::class, 'categories']);

Route::get('/editcategory/{id}', [CategoryController::class, 'editcategory']);

Route::post('/updatecategory', [CategoryController::class, 'updatecategory']);

Route::get('/deletecategory/{id}', [CategoryController::class, 'deletecategory']);



Route::get('/addslider', [SliderController::class, 'addslider']);

Route::get('/sliders', [SliderController::class, 'sliders']);

Route::post('/saveslider', [SliderController::class, 'saveslider']);

Route::get('/editslider/{id}', [SliderController::class, 'editslider']);

Route::post('/updateslider', [SliderController::class, 'updateslider']);

Route::get('/deleteslider/{id}', [SliderController::class, 'deleteslider']);

Route::get('/activateslider/{id}', [SliderController::class, 'activateslider']);

Route::get('/unactivateslider/{id}', [SliderController::class, 'unactivateslider']);





Route::get('/addproduct', [ProductController::class, 'addproduct']);

Route::get('/products', [ProductController::class, 'products']);

Route::post('/saveproduct', [ProductController::class, 'saveproduct']);

Route::get('/editproduct/{id}', [ProductController::class, 'editproduct']);

Route::post('/updateproduct', [ProductController::class, 'updateproduct']);

Route::get('/deleteproduct/{id}', [ProductController::class, 'deleteproduct']);

Route::get('/activateproduct/{id}', [ProductController::class, 'activateproduct']);

Route::get('/unactivateproduct/{id}', [ProductController::class, 'unactivateproduct']);

Route::get('/viewproductbycategory/{category_name}', [ProductController::class, 'viewproductbycategory']);




Route::get('/', [ClientController::class, 'home']);

Route::get('/shop', [ClientController::class, 'shop']);

Route::get('/cart', [ClientController::class, 'cart']);

Route::get('/addToCart/{id}', [ClientController::class, 'addToCart']);

Route::post('/update_qty/{id}', [ClientController::class, 'update_qty']);

Route::get('/remove_from_cart/{id}', [ClientController::class, 'remove_from_cart']);

Route::get('/checkout', [ClientController::class, 'checkout']);

Route::get('/login', [ClientController::class, 'login']);

Route::get('/signup', [ClientController::class, 'signup']);

Route::get('/orders', [ClientController::class, 'orders']);

Route::post('/createaccount', [ClientController::class, 'createaccount']);

Route::post('/accessaccount', [ClientController::class, 'accessaccount']);

Route::get('/logout', [ClientController::class, 'logout']);

Route::post('/postcheckout', [ClientController::class, 'postcheckout']);




Route::get('/viewpdforder/{id}', [PdfController::class, 'view_pdf']);





// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth'])->name('dashboard');

// require __DIR__.'/auth.php';
