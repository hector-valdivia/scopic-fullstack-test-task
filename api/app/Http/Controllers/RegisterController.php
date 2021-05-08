<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserCreateRequest;
use App\Models\User;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    /**
     * Register api
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(UserCreateRequest $request)
    {
        $data = $request->validated();

        $data['password'] = bcrypt($data['password']);
        $user = User::create($data);

        return response()->json([
            'message' => 'User register successfully.',
            'token'   => $user->createToken('MyApp')-> accessToken,
            'name'    => $user->name,
        ]);
    }

}
