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
/*

Debug querys

DB::listen(function ($query) {
    echo '<pre>' . $query->sql . '</pre>';   // consulta sql
    echo '<pre>' . $query->time . '</pre>';  // tiempo en retornar el servidor
});*/

/*
 * Buyers
 * */
Route::apiResource('buyers', 'Buyer\BuyerController')
    ->only(['index', 'show']);

/*
 * Categories
 * */
Route::apiResource('categories', 'Category\CategoryController');

/*
 * Products
 * */
Route::apiResource('products', 'Product\ProductController')
    ->only(['index', 'show']);

/*
 * Transactions
 * */

Route::apiResource('transactions', 'Transaction\TransactionController')
    ->only(['index', 'show']);
/*
 * Sellers
 * */
Route::apiResource('sellers', 'Seller\SellerController')
    ->only(['index', 'show']);

/*
 * Users
 * */
Route::apiResource('users', 'User\UserController');
