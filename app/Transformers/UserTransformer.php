<?php

namespace App\Transformers;

use App\Models\User;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{
    public function transform(User $user): array
    {
        return [
            'id'                => $user->id,
            'uuid'              => $user->uuid,
            'first_name'        => $user->first_name,
            'last_name'         => $user->last_name,
            'is_admin'          => $user->is_admin,
            'email_verified_at' => $user->email_verified_at,
            'avatar'            => $user->avatar,
            'address'           => $user->address,
            'phone_number'      => $user->phone_number,
            'is_marketing'      => $user->is_marketing,
            'created_at'        => $user->created_at,
            'updated_at'        => $user->updated_at,
            'last_login_at'     => $user->last_login_at,
        ];
    }
}
