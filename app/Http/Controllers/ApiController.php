<?php

namespace App\Http\Controllers;

use App\Traits\ApiResponser;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ApiController extends Controller
{
    // Usando el trait de respuestas generalizadas;
    use ApiResponser;

    public function __construct()
    {
        // Usando en el Padre al Middleware de Api
        $this->middleware('auth:api');
    }

    // Se centraliza el gate para usarlo en todos los controladores que hereden de este padre
    protected function allowedAminAction()
    {
        if (Gate::denies('admin-action')) {
            throw new AuthorizationException('Esta accion es solo permitida por los admins :)');
        }
    }
}
