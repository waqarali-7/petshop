<?php

namespace App\Http\Controllers;

use App\Repositories\Eloquent\OrderRepository;
use App\Transformers\OrderTransformer;
use Illuminate\Support\Facades\Auth;

class OrderController extends BaseController
{
    private OrderRepository $repository;

    public function __construct(OrderTransformer $transformer, OrderRepository $repository)
    {
        $this->transformer = $transformer;
        $this->repository = $repository;

        parent::__construct();
    }

    public function getUserOrders()
    {
        $currentUser = Auth::user();
        $orders = $this->repository->getUserOrders($currentUser);

        return $this->withCollection($orders, $this->transformer);

    }
}
