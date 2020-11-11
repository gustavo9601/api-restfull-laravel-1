<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{

    use SoftDeletes;

    protected $dates = ['deleted_at'];
    // status product
    const PRODUCTO_DISPONIBLE = 'disponible';
    const PRODUCTO_NO_DISPONIBLE = 'no disponible';

    protected $fillable = [
        'name', 'description', 'quantity', 'status', 'image', 'seller_id'
    ];

    // Esconde de la respuesta del modelo, las columnas o relaciones
    protected $hidden = ['pivot'];


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
