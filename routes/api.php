<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


// Debug querys

/*DB::listen(function ($query) {
    dump($query->sql);
    dump($query->time);
});*/

/*
 * Buyers
 * */
Route::apiResource('buyers', 'Buyer\BuyerController')
    ->only(['index', 'show']);

// transacciones para un comprador
Route::apiResource('buyers.transactions', 'Buyer\BuyerTransactionController')
    ->only(['index']);

// productos de un comprador
Route::apiResource('buyers.products', 'Buyer\BuyerProductController')
    ->only(['index']);

// vendores de productos para compradores
Route::apiResource('buyers.sellers', 'Buyer\BuyerSellerController')
    ->only(['index']);

// categorias de productos de compradoras
Route::apiResource('buyers.categories', 'Buyer\BuyerCategoryController')
    ->only(['index']);



/*
 * Categories
 * */
Route::apiResource('categories', 'Category\CategoryController');

// Productos para la categoria
Route::apiResource('categories.products', 'Category\CategoryProductController')
    ->only(['index']);
// Vendedores por categoria
Route::apiResource('categories.sellers', 'Category\CategorySellerController')
    ->only(['index']);

// Transacciones por categoria
Route::apiResource('categories.transactions', 'Category\CategoryTransactionController')
    ->only(['index']);

// Compradores por categoria
Route::apiResource('categories.buyers', 'Category\CategoryBuyerController')
    ->only(['index']);

/*
 * Products
 * */
Route::apiResource('products', 'Product\ProductController')
    ->only(['index', 'show']);

// Transacciones para un productio
Route::apiResource('products.transactions', 'Product\ProductTransactionController')
    ->only(['index']);

// Compradores para un producto
Route::apiResource('products.buyers', 'Product\ProductBuyerController')
    ->only(['index']);

// Categorias para un producto
Route::apiResource('products.categories', 'Product\ProductCategoryController')
    ->only(['index', 'destroy', 'update']);


// Permite crear transacciones, para un producto desde un comprador
Route::apiResource('products.buyers.transactions', 'Product\ProductBuyerTransactionController')
    ->only(['store']);


/*
 * Transactions
 * */

Route::apiResource('transactions', 'Transaction\TransactionController')
    ->only(['index', 'show']);

// categorias de una transaccion
Route::apiResource('transactions.categories', 'Transaction\TransactionCategoryController')
    ->only(['index']);

// vendedor de una transaccion
Route::apiResource('transactions.sellers', 'Transaction\TransactionSellerController')
    ->only(['index']);
/*
 * Sellers
 * */
Route::apiResource('sellers', 'Seller\SellerController')
    ->only(['index', 'show']);

// Transacciones de un vendedor
Route::apiResource('sellers.transactions', 'Seller\SellerTransactionController')
    ->only(['index']);

// Categorias de un vendedor
Route::apiResource('sellers.categories', 'Seller\SellerCategoryController')
    ->only(['index']);


// Compradores que tienen relacion con vendedores
Route::apiResource('sellers.buyers', 'Seller\SellerBuyerController')
    ->only(['index']);


// Productos de un vendedor
Route::apiResource('sellers.products', 'Seller\SellerProductController')
    ->only(['index', 'store', 'update', 'destroy']);


/*
 * Users
 * */
Route::apiResource('users', 'User\UserController');
// Verificar el token cuando se crea una cuenta
Route::get('users/verify/{token}', 'User\UserController@verify')->name('verify');
// permite enviar un token de nuevo
Route::get('users/{user}/resend', 'User\UserController@resend')->name('resend');



// Sobrescribiendo la rutas propias de Passport
Route::post('oauth/token', '\Laravel\Passport\Http\Controllers\AccessTokenController@issueToken');
