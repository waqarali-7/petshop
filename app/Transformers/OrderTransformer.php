<?php

namespace App\Transformers;

use App\Models\Order;
use League\Fractal\TransformerAbstract;

class OrderTransformer extends TransformerAbstract
{
    public function transform(Order $user): array
    {
        return [
            'id'                => $user->id,
            'uuid'              => $user->uuid,
            'order_status_id'   => $user->order_status_id,
            'payment_id'        => $user->payment_id,
            'products'          => $user->products,
            'address'           => $user->address,
            'delivery_fee'      => $user->delivery_fee,
            'amount'            => $user->amount,
            'created_at'        => $user->created_at,
            'shipped_at'        => $user->shipped_at,
        ];
    }
}
