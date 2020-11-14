<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\ApiController;
use App\Seller;
use Illuminate\Http\Request;

class SellerController extends ApiController
{
    public function __construct()
    {
        parent::__construct();


        // Usamos el scope de Passport, y le pasamos en comas, los scopes a usar y validar
        $this->middleware(['scope:read-general'])->only(['show']);
    }

    public function __invoke(Request $request)
    {
        //
    }


    public function index()
    {
        // con has pregunta si tiene products
        // $sellers = Seller::has('products')->get();
        $sellers = Seller::all();  // El global scope limita a solo lo que tenga products
        return $this->showAll($sellers);
    }

    public function show(Seller $seller)
    {
        // Primero verifica que tenga products y despues que exista
        // $seller = Seller::has('products')->findOrFail($id);

        return $this->showOne($seller);
    }
}
