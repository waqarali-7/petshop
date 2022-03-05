<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserRequest;
use App\Models\User;
use App\Repositories\Eloquent\UserRepository;
use App\Transformers\UserTransformer;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
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
     * @return JsonResponse
     */
    public function getAllAdminUsers(): JsonResponse
    {
        $users = $this->repository->getAllUsers(true);

        return $this->withCollection($users, $this->transformer);
    }

    /**
     * @param User $user
     * @return JsonResponse
     */
    public function getUser(User $user): JsonResponse
    {
        if ($user && $user->is_admin) {
            return $this->withException(['Unauthorized']);
        }
        return $this->withItem($user, $this->transformer);
    }

    /**
     * @param User $user
     * @return JsonResponse
     */
    public function remove(User $user): JsonResponse
    {
        if ($user && $user->is_admin) {
            return $this->withException(['Unauthorized']);
        }
        $userDeleted = $this->repository->deleteById($user);

        return $this->withArray([
            $userDeleted ? 'User deleted!' : 'User could not be deleted!'
        ]);
    }

    /**
     * @param CreateUserRequest $request
     * @return JsonResponse
     */
    public function createAdminUser(CreateUserRequest $request): JsonResponse
    {
        $input = $request->all();

        $input['password'] = Hash::make($request->password);
        $input['uuid'] = Str::uuid();;
        $input['is_admin'] = true;

        try {
            $user = User::create($input);

            return $this->withItem($user, $this->transformer);

        } catch (\Exception $exception) {

            return $this->withException([
                'message' => $exception->getMessage()
            ]);
        }
    }

    /**
     * @param CreateUserRequest $request
     * @return JsonResponse
     */
    public function createUser(CreateUserRequest $request): JsonResponse
    {
        $input = $request->all();

        $input['password'] = Hash::make($request->password);
        $input['uuid'] = Str::uuid();;
        $input['is_admin'] = false;

        try {
            $user = User::create($input);

            return $this->withItem($user, $this->transformer);

        } catch (\Exception $exception) {

            return $this->withException([
                'message' => $exception->getMessage()
            ]);
        }
    }
}
