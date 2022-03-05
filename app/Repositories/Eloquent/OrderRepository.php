<?php

namespace App\Repositories\Eloquent;

use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class OrderRepository extends BaseRepository
{
    public function __construct(Order $model)
    {
        parent::__construct($model);
    }

    public function getUserOrders(User $user)
    {
        return $this->all()->where('user', $user);
    }
}
