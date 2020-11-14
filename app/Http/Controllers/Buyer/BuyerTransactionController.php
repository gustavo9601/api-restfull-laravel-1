<?php

namespace App\Http\Controllers\Buyer;

use App\Buyer;
use App\Http\Controllers\ApiController;
use App\Transaction;
use Illuminate\Http\Request;

class BuyerTransactionController extends ApiController
{
    public function __construct()
    {
        parent::__construct();

        // Usamos el scope de Passport, y le pasamos en comas, los scopes a usar y validar
        $this->middleware(['scope:read-general'])->only(['index']);
    }

    public function index(Buyer $buyer)
    {
        $transactions = $buyer->transactions;

        return $this->showAll($transactions);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function show(Transaction $transaction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Transaction $transaction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function destroy(Transaction $transaction)
    {
        //
    }
}
