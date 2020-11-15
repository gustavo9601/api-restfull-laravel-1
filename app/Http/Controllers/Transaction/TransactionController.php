<?php

namespace App\Http\Controllers\Transaction;

use App\Http\Controllers\ApiController;
use App\Transaction;

class TransactionController extends ApiController
{
    public function __construct()
    {
        parent::__construct();


        // Usamos el scope de Passport, y le pasamos en comas, los scopes a usar y validar
        $this->middleware(['scope:read-general'])->only(['show']);

        $this->middleware(['can:view,transaction'])->only(['show']);
    }

    public function index()
    {

        // Usando el Gate definido en el padre y heradedado a este controlller
        $this->allowedAminAction();

        $transactions = Transaction::all();

        return $this->showAll($transactions);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function show(Transaction $transaction)
    {
        return $this->showOne($transaction);
    }
}
