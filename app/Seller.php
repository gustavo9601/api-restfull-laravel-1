<?php

namespace App;

class Seller extends User
{
    // Un vendedor tiene muchos productos
    public function products(){
        return $this->hasMany(Product::class);
    }
}
