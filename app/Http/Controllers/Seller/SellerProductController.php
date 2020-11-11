<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Product;
use App\Seller;
use App\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

class SellerProductController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Seller $seller)
    {
        $products = $seller->products;

        return $this->showAll($products);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    // Usamos el modelo de User, ya que puede darse que usuarios nuevos que no han sido vendedores
    // quieran publicar un producto
    public function store(Request $request, User $seller)
    {
        $rules = [
            'name' => 'required',
            'description' => 'required',
            'quantity' => 'required|integer|min:1',
            'image' => 'required|image'
        ];

        $this->validate($request, $rules);

        $data = $request->all();

        $data['status'] = Product::PRODUCTO_NO_DISPONIBLE;
        $data['image'] = 'default.png';
        $data['seller_id'] = $seller->id;
        $product = Product::create($data);

        return $this->showOne($product, 201);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Seller  $seller
     * @return \Illuminate\Http\Response
     */
    public function show(Seller $seller)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Seller  $seller
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Seller $seller, Product $product)
    {
        $rules = [
            'quantity' => 'integer|min:1',
            'status' => 'in:' . Product::PRODUCTO_DISPONIBLE . ',' . Product::PRODUCTO_NO_DISPONIBLE,
            'image' => 'image'
        ];

        $this->validate($request, $rules);

        // Validacion si el id del vendedor es el mismo asignado al del producto
        $this->verificarVendedor($seller, $product);

        // Fill permite emular el llenado de un modelo
        $product->fill($request->only(['name', 'description', 'quantity']));
        // Si envio estado, se le asigna por default el que se envio
        if ($request->has('status')){
            $product->status = $request->input('status');
            // Verificacion si una ves actualizado el estado al modelo, y no tiene categorias
            // no lo puede actualizar, pese a la logica de negocio
            if ($product->estaDisponible() && $product->categories()->count() == 0){
                return $this->errorResponse('Un producto activo debe tener al menos una categoria', 409);
            }
        }

        // Verificar si se cambio algun valor, en caso conrario rechaza el update
        if ($product->isClean()){
            return $this->errorResponse('Se debe especificar al menos un valor diferente para actualizar', 422);
        }
        // Actualiza el recurso
        $product->save();

        return $this->showOne($product);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Seller  $seller
     * @return \Illuminate\Http\Response
     */
    public function destroy(Seller $seller, Product $product)
    {
        // Validacion si el id del vendedor es el mismo asignado al del producto
        $this->verificarVendedor($seller, $product);

        $product->delete();

        return $this->showOne($product);
    }

    public function verificarVendedor(Seller $seller, Product $product){
        // Validacion si el id del vendedor es el mismo asignado al del producto
        if ($seller->id != $product->seller_id){
            // lanzando una exepcion de tipo http
            throw new HttpException(422, 'El vendedor especificado no es el vendedor real del producto');
        }
    }
}