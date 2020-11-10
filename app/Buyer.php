<?php

namespace App;

class Buyer extends User
{
    // Un buyer tiene muchas transactions
    public function transactions(){
        return $this->hasMany(Transaction::class);
    }
}
