<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    // status product
    const PRODUCTO_DISPONIBLE = 'disponible';
    const PRODUCTO_NO_DISPONIBLE = 'no disponible';

    protected $fillable = [
        'name', 'description', 'quantity', 'status', 'image', 'seller_id'
    ];

    // Retornara si esta disponible el producto
    public function estaDisponible(){
        return $this->status == self::PRODUCTO_DISPONIBLE;
    }

    // un producto tiene muchas categorias
    public function categories(){
        return $this->belongsToMany(Category::class);
    }


    // Un producto pertenece a un vendedor
    public function seller(){
        return $this->belongsTo(Seller::class);
    }

    // Un producto tiene muchas transacciones
    public function transactions(){
        return $this->hasMany(Transaction::class);
    }

}
