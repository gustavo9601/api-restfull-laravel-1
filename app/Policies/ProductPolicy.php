<?php

namespace App\Policies;

use App\Product;
use App\Traits\AdminActions;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProductPolicy
{
    // AdminActions
    // Trait propio que hace la verificacion de si es administrador en el before
    use HandlesAuthorization, AdminActions;


    public function view(User $user, Product $product)
    {
        // Solo podra ver el producto quien lo venda
        return $user->id === $product->seller->id;
    }

    public function addCategory(User $user, Product $product)
    {
        // Solo podra ver el producto quien lo venda
        return $user->id === $product->seller->id;
    }

    public function deleteCategory(User $user, Product $product)
    {
        // Solo podra ver el producto quien lo venda
        return $user->id === $product->seller->id;
    }
}
