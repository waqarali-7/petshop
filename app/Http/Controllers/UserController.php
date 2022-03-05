<?php

namespace App\Http\Controllers;

use App\Repositories\Eloquent\UserRepository;
use App\Transformers\UserTransformer;
use Illuminate\Http\Response;
use League\Fractal\TransformerAbstract;

class UserController extends BaseController
{
    /**
     * @var TransformerAbstract
     */
    protected TransformerAbstract $transformer;

    /**
     * @var UserRepository
     */
    private UserRepository $repository;

    public function __construct(UserTransformer $transformer, UserRepository $repository)
    {
        $this->transformer = $transformer;
        $this->repository = $repository;

        parent::__construct();
    }

    /**
     * @return Response
     */
    public function getAllUsers(): Response
    {
        $users = $this->repository->getAllAdminUsers();

        return $this->withCollection($users, $this->transformer);
    }
}
