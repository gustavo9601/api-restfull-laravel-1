<?php

namespace App\Policies;

use App\Traits\AdminActions;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
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
     * @param  \App\User  $user
     * @param  \App\User  $model
     * @return mixed
     */
    public function view(User $userAuthenticated, User $user)
    {
        return $userAuthenticated->id === $user->id;
    }


    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User  $user
     * @param  \App\User  $model
     * @return mixed
     */
    public function update(User $userAuthenticated, User $user)
    {
        return $userAuthenticated->id === $user->id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\User  $model
     * @return mixed
     */
    public function delete(User $userAuthenticated, User $user)
    {
        // userAuthenticated->token()->client->personal_access_client
        // retorna booleano si el cliente autenticado es personal
        return $userAuthenticated->id === $user->id && $userAuthenticated->token()->client->personal_access_client;
    }


}
