<?php

namespace App\Http\Controllers;

use App\Http\Requests\SignupRequest;
use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use JetBrains\PhpStorm\NoReturn;

class AuthUserController extends Controller
{
    /**
     * @param SignupRequest $request
     * @return JsonResponse
     */
    #[NoReturn] public function register(SignupRequest $request): JsonResponse
    {
        dd("hi");
        $input = $request->all();

        $input['password'] = Hash::make($request->password);

        $user = User::create($input);

        $accessToken = $user->createToken('authToken')->accessToken;

        $successResponse ['token'] = $accessToken;

        return response()->json($successResponse, 201);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function login(Request $request): JsonResponse
    {
        $user = User::where("email", $request->email)->first();

        if ($user && Hash::check($request->password, $user->password)) {
            $token = $user->createToken('user_token')->accessToken;
            return response()->json(['token' => $token]);
        } else {
            return response()->json(['error' => 'Unauthunticated'], 403);
        }
    }

    /**
     * @param Request $request
     * @return Response|Application|ResponseFactory
     */
    public function logout(Request $request)
    {
        $token = $request->user()->token();
        $token->revoke();
        $response = ['message' => 'You have been successfully logged out!'];

        return response($response, 200);
    }
}