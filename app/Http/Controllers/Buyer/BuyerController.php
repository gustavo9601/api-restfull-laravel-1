<?php

namespace App\Http\Controllers\Buyer;

use App\Buyer;
use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;

class BuyerController extends ApiController
{
    /**
     * Handle the incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        //
    }

    public function index()
    {
        // con has pregunta si tiene trabsaccionts
        $buyers = Buyer::has('transactions')->get();
        // return response()->json(['data' => $buyers], 200);
        return $this->showAll($buyers);
    }

    public function show($id)
    {
        // Primero verifica que tenga transactions y despues que exista
        $buyer = Buyer::has('transactions')->findOrFail($id);

        // return response()->json(['data' => $buyer], 200);
        return $this->showOne($buyer);
    }


}
