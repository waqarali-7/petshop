<?php

namespace App\Http\Controllers;

use App\Http\Requests\SignupRequest;
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
        $users = $this->repository->getAllAdminUsers();

        return $this->withCollection($users, $this->transformer);
    }

    /**
     * @param SignupRequest $request
     * @return JsonResponse
     */
    public function createAdminUser(SignupRequest $request): JsonResponse
    {
        $input = $request->all();

        $input['password'] = Hash::make($request->password);
        $input['uuid'] = Str::uuid();;
        $input['is_admin'] = true;

        try {
            $user = User::create($input);
            $successResponse ['user'] = "$user";
            $successResponse ['message'] = "User Created Successfully!";

            return $this->withItem($user, $this->transformer);

        } catch (\Exception $exception) {

            return $this->withException([
                'message' => $exception->getMessage()
            ]);
        }
    }
}
