<?php

namespace App\Http\Controllers;

use App\Traits\ApiResponser;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    // Usando el trait de respuestas generalizadas;
    use ApiResponser;

    public function __construct()
    {
    }
}
