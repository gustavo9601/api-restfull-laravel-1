<?php

namespace App\Transformers;

use App\Transaction;
use League\Fractal\TransformerAbstract;

class TransactionTransformer extends TransformerAbstract
{
    /**
     * List of resources to automatically include
     *
     * @var array
     */
    protected $defaultIncludes = [
        //
    ];

    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [
        //
    ];

    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Transaction $transaction)
    {
        return [
            'identifier' => (int) $transaction->id,
            'cantidad' => (int) $transaction->quantity,
            'comprador' => (int) $transaction->buyer_id,
            'product' => (int) $transaction->product_id,
            'fechaCreacion' => $transaction->created_at,
            'fechaActualizacion' => $transaction->updated_at,
            'fechaEliminacion' => isset($transaction->deleted_at) ? (string) $transaction->deleted_at : null
        ];
    }
}
