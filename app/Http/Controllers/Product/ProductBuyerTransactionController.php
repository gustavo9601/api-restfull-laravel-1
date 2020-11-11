<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Product;
use App\Transaction;
use App\User;
use Illuminate\Http\Request;

class ProductBuyerTransactionController extends ApiController
{


    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Product $product, User $buyer)
    {
        $rules = [
            'quantity' => 'required|integer|min:1'
        ];


        // No puede comprar el producto de quien lo vende
        if ($buyer->id == $product->seller_id) {
            return $this->errorResponse('El comprador debe ser diferente al vendedor', 409);
        }

        // verificar si el comprador es verificado
        if (!$buyer->esVerificado()) {
            return $this->errorResponse('El comprador debe ser un usuario verificado', 409);
        }


        // verificar si el vendedor del producto es verificado
        if (!$product->seller->esVerificado()) {
            return $this->errorResponse('El vendedor debe ser un usuario verificado', 409);
        }

        // verificar si el producto esta disponible
        if (!$product->estaDisponible()) {
            return $this->errorResponse('El producto para esta transaccion no esta disponible', 409);
        }

        // verificacion de cantidades de producto, para no permitir vender un stock superior al actual
        if ($product->quantity < $request->quantity) {
            return $this->errorResponse('El producto no tiene la cantidad disponible requerida para esta transaccion', 409);
        }



        // Usando una transacion para asegurar el query
        return \DB::transaction(function () use ($request, $product, $buyer){
            //  Reducir la cantidad disponible del producto
            $product->quantity -= $request->quantity;
            // actualiza el inventario en la bd
            $product->save();


            $transaction = Transaction::create([
                'quantity' => $request->quantity,
                'buyer_id' => $buyer->id,
                'product_id' => $product->id
            ]);

            // Devuelve la creacion
            return $this->showOne($transaction, 201);
        });
    }

}
