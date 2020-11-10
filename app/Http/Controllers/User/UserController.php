<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\ApiController;
use App\User;
use Illuminate\Http\Request;

class UserController extends ApiController
{
    /**
     * Handle the incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        //
    }

    public function index()
    {
        $usuarios = User::all();
        // return response()->json(['data' => $usuarios], 200);
        return $this->showAll($usuarios);
    }

    public function show($id)
    {
        $usuario = User::findOrFail($id);
        // return response()->json(['data' => $usuario], 200);
        return $this->showOne($usuario);
    }

    public function store(Request $request)
    {
        $rules = [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed'
        ];

        $this->validate($request, $rules);

        $campos = $request->all();
        $campos['verified'] = User::USUARIO_NO_VERIFICADO;
        $campos['verification_token'] = User::generarVerificationToken();
        $campos['admin'] = User::USUARIO_REGULAR;

        $usuario = User::create($campos);

        // return response()->json(['data' => $usuario], 201);
        return $this->showOne($usuario, 201);
    }

    public function update(Request $request, $id)
    {
        $usuario = User::findOrFail($id);
        // No es necesario que sean requeridos, ya que se puede actualizar uno o mas campos
        $rules = [
            'email' => 'email|unique:users,email,' . $usuario->id, // la validacion de unique, descarta el id actual para la columna email
            'password' => 'min:6|confirmed',
            'admin' => 'in:' . User::USUARIO_ADMINISTRADOR . ',' . User::USUARIO_REGULAR  // Debe incluir alguno de los valores pasados
        ];

        $this->validate($request, $rules);

        if ($request->has('name')) {
            $usuario->name = $request->input('name');
        }

        if ($request->has('email')) {
            // Si el correo es diferente del ya usado
            // se genera un nuevo token y pasa a ser no verificado
            if ($usuario->email != $request->input('email')) {
                $usuario->verified = User::USUARIO_NO_VERIFICADO;
                $usuario->verification_token = User::generarVerificationToken();
            }
            $usuario->email = $request->input('email');
        }

        if ($request->has('password')) {
            $usuario->password = $request->input('password');
        }

        if ($request->has('admin')) {
            // Si no esta verificado el usuario no puede modificar el admin
            if (!$usuario->esVerificado()) {
                // return response()->json(['error' => 'Unicamente los usuarios verificados pueden cambiar su valor de administrador', 'code' => 409], 409);
                return $this->errorResponse('Unicamente los usuarios verificados pueden cambiar su valor de administrador', 409);
            }
            $usuario->admin = $request->admin;
        }

        // Verifica si alguno de los valores iniciales, sufrio algun cambio

        if (!$usuario->isDirty()) {
            // return response()->json(['error' => 'Se debe especificar al menos un valor diferente para actualizar', 'code' => 422], 422);
            return $this->errorResponse('Se debe especificar al menos un valor diferente para actualizar', 422);
        }

        // Actualiza
        $usuario->save();

        // return response()->json(['data' => $usuario], 200);
        return $this->showOne($usuario, 200);
    }

    public function destroy($id)
    {
        $usuario = User::findOrFail($id);
        $usuario->delete();
        // return response()->json(['data' => $usuario], 200);
        return $this->showOne($usuario, 200);
    }

}
