<?php

namespace App;

class Buyer extends User
{

    protected $table = 'users';
    // Un buyer tiene muchas transactions
    public function transactions(){
        return $this->hasMany(Transaction::class);
    }
}
