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
            'identifier' => (int)$transaction->id,
            'cantidad' => (int)$transaction->quantity,
            'comprador' => (int)$transaction->buyer_id,
            'product' => (int)$transaction->product_id,
            'fechaCreacion' => $transaction->created_at,
            'fechaActualizacion' => $transaction->updated_at,
            'fechaEliminacion' => isset($transaction->deleted_at) ? (string)$transaction->deleted_at : null,


            'links' => [
                [
                    'rel' => 'self',
                    'href' => route('transactions.show', [$transaction->id])
                ], [
                    'rel' => 'transactions.categories',
                    'href' => route('transactions.categories.index', [$transaction->id])
                ], [
                    'rel' => 'transactions.seller',
                    'href' => route('transactions.sellers.index', [$transaction->id])
                ],
                [
                    'rel' => 'buyer',
                    'href' => route('buyers.show', [$transaction->buyer_id])
                ],
                [
                    'rel' => 'product',
                    'href' => route('products.show', [$transaction->product_id])
                ]
            ]
        ];
    }

    public static function originalAttribute($index)
    {
        $attributes = [
            'identifier' => 'id',
            'cantidad' => 'quantity',
            'comprador' => 'buyer_id',
            'product' => 'product_id',
            'fechaCreacion' => 'created_at',
            'fechaActualizacion' => 'updated_at',
            'fechaEliminacion' => 'deleted_at',
        ];

        return isset($attributes[$index]) ? $attributes[$index] : null;
    }
}
