<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{

    use SoftDeletes;

    protected $dates = ['deleted_at'];

    // Esconde de la respuesta del modelo, las columnas o relaciones
    protected $hidden = ['pivot'];

    protected $fillable = [
      'name', 'description'
    ];

    // una categoria pertenece muchos productos
    public function products(){
        return $this->belongsToMany(Product::class);
    }
}
