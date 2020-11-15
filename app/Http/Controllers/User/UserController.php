<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\ApiController;
use App\Mail\UserCreated;
use App\Transformers\UserTransformer;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class UserController extends ApiController
{

    public function __construct()
    {
        // Permite acceso general a cualquier cliente con token, "no es lo mismo que a un usuario"
        $this->middleware(['client.credentials'])->only(['store', 'resend']);
        $this->middleware(['auth:api'])->except(['store', 'resend', 'verify']);

        // Middleware de trasnformacion de respuestas y obtencion de la data
        // $this->middleware(['transform.input:' . UserTransformer::class])->only(['store', 'update']);


        // Usamos el scope de Passport, y le pasamos en comas, los scopes a usar y validar
        $this->middleware(['scope:manage-account'])->only(['show', 'update']);

        $this->middleware(['can:view,user'])->only(['show']);
        $this->middleware(['can:update,user'])->only(['update']);
        $this->middleware(['can:destroy,user'])->only(['destroy']);
    }

    public function index()
    {

        // Usando el Gate definido en el padre y heradedado a este controlller
        $this->allowedAminAction();

        $usuarios = User::all();
        // return response()->json(['data' => $usuarios], 200);
        return $this->showAll($usuarios);
    }

    public function show(User $user)
    {
        // $usuario = User::findOrFail($id);
        // return response()->json(['data' => $usuario], 200);
        return $this->showOne($user);
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

    public function update(Request $request, User $user)
    {
        // No es necesario que sean requeridos, ya que se puede actualizar uno o mas campos
        $rules = [
            'email' => 'email|unique:users,email,' . $user->id, // la validacion de unique, descarta el id actual para la columna email
            'password' => 'min:6|confirmed',
            'admin' => 'in:' . User::USUARIO_ADMINISTRADOR . ',' . User::USUARIO_REGULAR  // Debe incluir alguno de los valores pasados
        ];

        $this->validate($request, $rules);

        if ($request->has('name')) {
            $user->name = $request->input('name');
        }

        if ($request->has('email')) {
            // Si el correo es diferente del ya usado
            // se genera un nuevo token y pasa a ser no verificado
            if ($user->email != $request->input('email')) {
                $user->verified = User::USUARIO_NO_VERIFICADO;
                $user->verification_token = User::generarVerificationToken();
            }
            $user->email = $request->input('email');
        }

        if ($request->has('password')) {
            $user->password = $request->input('password');
        }

        if ($request->has('admin')) {

            // Usando el Gate definido en el padre y heradedado a este controlller
            // Solo un admin puede actualizar a otro usuario como admin
            $this->allowedAminAction();

            // Si no esta verificado el usuario no puede modificar el admin
            if (!$user->esVerificado()) {
                // return response()->json(['error' => 'Unicamente los usuarios verificados pueden cambiar su valor de administrador', 'code' => 409], 409);
                return $this->errorResponse('Unicamente los usuarios verificados pueden cambiar su valor de administrador', 409);
            }
            $user->admin = $request->admin;
        }

        // Verifica si alguno de los valores iniciales, sufrio algun cambio

        if (!$user->isDirty()) {
            // return response()->json(['error' => 'Se debe especificar al menos un valor diferente para actualizar', 'code' => 422], 422);
            return $this->errorResponse('Se debe especificar al menos un valor diferente para actualizar', 422);
        }

        // Actualiza
        $user->save();

        // return response()->json(['data' => $user], 200);
        return $this->showOne($user, 200);
    }

    public function destroy(User $user)
    {
        $user->delete();
        // return response()->json(['data' => $usuario], 200);
        return $this->showOne($user, 200);
    }


    public function verify($token)
    {
        // Consultamos en la bd si existe el registro por el token, en caso contrario falla el request
        $user = User::where('verification_token', $token)->firstOrFail();

        // Actualiza el estado a verificado, y remueve el token
        $user->verified = User::USUARIO_VERIFICADO;
        $user->verification_token = null;

        $user->save();

        return $this->showMessage('Usuario verificado');

    }

    public function resend(User $user){
        // Si ya esta verificada la cuenta no es necesario volver a enviar el email
        if ($user->esVerificado()){
            return $this->errorResponse('Este usuario ya ha sido verificado', 409);
        }

        retry(5, function () use ($user){
            Mail::to($user->email)->send(new UserCreated($user));
        }, 200);


        return $this->showMessage('El Correo de verificacion se ha reenviado');
    }
}
