<?php

namespace App\Transformers;

use App\User;
use League\Fractal\TransformerAbstract;
use function GuzzleHttp\Psr7\str;

class UserTransformer extends TransformerAbstract
{
    /**
     * List of resources to automatically include
     *
     * @var array
     */
    protected $defaultIncludes = [
        //
    ];

    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [
        //
    ];

    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(User $user)
    {
        return [
            'identifier' => (int)$user->id,
            'nombre' => (string)$user->name,
            'correo' => (string)$user->email,
            'esVerificado' => (int)$user->verified,
            'esAdministrador' => ($user->admin === 'true'),
            'fechaCreacion' => (string)$user->created_at->format('d-m-Y H:i:s'),  // format de carbon
            'fechaActualizacion' => (string)$user->updated_at->format('d-m-Y H:i:s'),
            'fechaEliminacion' => isset($user->deleted_at) ? (string)$user->deleted_at : null,

            'links' => [
                [
                    'rel' => 'self',
                    'href' => route('users.show', [$user->id])
                ]
            ]
        ];
    }

    public static function originalAttribute($index)
    {


        $attributes = [
            'identifier' => 'id',
            'nombre' => 'name',
            'correo' => 'email',
            'esVerificado' => 'verified',
            'esAdministrador' => 'admin',
            'fechaCreacion' => 'created_at',
            'fechaActualizacion' => 'updated_at',
            'fechaEliminacion' => 'deleted_at',
        ];

        return isset($attributes[$index]) ? $attributes[$index]:  null;
    }
}
