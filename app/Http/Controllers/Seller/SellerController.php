<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\ApiController;
use App\Seller;
use Illuminate\Http\Request;

class SellerController extends ApiController
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        //
    }


    public function index()
    {
        // con has pregunta si tiene products
        $sellers = Seller::has('products')->get();
        return $this->showAll($sellers);
    }

    public function show($id)
    {
        // Primero verifica que tenga products y despues que exista
        $seller = Seller::has('products')->findOrFail($id);

        return $this->showOne($seller);
    }
}
