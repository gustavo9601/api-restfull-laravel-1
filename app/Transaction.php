<?php

namespace App;

use App\Transformers\TransactionTransformer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{

    use SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $fillable = [
        'quantity', 'buyer_id', 'product_id'
    ];

    // Especificando la transformacion
    public $transformer = TransactionTransformer::class;

    // una transaccion pertenece a un producto
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // una transaccion pertenece a un comprador
    public function buyer()
    {
        return $this->belongsTo(Buyer::class);
    }
}
