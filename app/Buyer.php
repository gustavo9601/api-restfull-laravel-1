<?php

namespace App;

use App\Scopes\BuyerScope;
use App\Transformers\BuyerTransformer;


class Buyer extends User
{
    // Especificando la transformacion
    public $transformer = BuyerTransformer::class;

    // Inicializa el modelo, para especificarle que scropes usar
    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new BuyerScope);
    }

    protected $table = 'users';

    // Un buyer tiene muchas transactions
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
