<?php

namespace App\Policies;

use App\Buyer;
use App\Traits\AdminActions;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class BuyerPolicy
{

    // AdminActions
    // Trait propio que hace la verificacion de si es administrador en el before
    use HandlesAuthorization, AdminActions;




    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param \App\User $user
     * @param \App\Buyer $buyer
     * @return mixed
     */
    public function view(User $user, Buyer $buyer)
    {
        // Verifica que el id del usuario autenticado, sea el mismo del id del buyer
        return $user->id === $buyer->id;
    }

    /**
     * Determine whether the user can purchase something
     *
     * @param \App\User $user
     * @param \App\Buyer $buyer
     * @return mixed
     */
    public function purchase(User $user, Buyer $buyer)
    {
        // Verifica que el id del usuario autenticado, sea el mismo del id del buyer
        return $user->id === $buyer->id;
    }
}
