<?php

namespace App\Traits;


use App\User;

trait AdminActions
{
    // Se delega la logica a un trait para no repetir codigo en los policies
   public function before(User $user, $ability){
        if ($user->esAdministrador()){
            return true;
        }
   }

}
