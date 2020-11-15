<?php

namespace App\Policies;

use App\Traits\AdminActions;
use App\Transaction;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TansactionPolicy
{

    // AdminActions
    // Trait propio que hace la verificacion de si es administrador en el before
    use HandlesAuthorization, AdminActions;


    public function view(User $user, Transaction $transaction)
    {

        // Es valido que lo vea o el comprador del producto o el vendedor del producto
        return $user->id === $transaction->buyer_id || $user->id === $transaction->product->seller->id;
    }


}
