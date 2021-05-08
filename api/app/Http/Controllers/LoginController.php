<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Passport\RefreshTokenRepository;
use Laravel\Passport\TokenRepository;

class LoginController extends Controller
{
    /**
     * Login api
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        if (!Auth::attempt(['email' => $request->email, 'password' => $request->password])){
            return response()->json([
                'message' => 'Unauthorised, bad login info.',
            ]);
        }

        $user = Auth::user();

        return response()->json([
            'message' => 'User login successfully.',
            'token'   => $user->createToken('MyApp')->accessToken,
            'name'    => $user->name,
        ]);
    }

    public function logout(Request $request){
        $token = $request->user()->token();

        $tokenRepository = app(TokenRepository::class);
        $refreshTokenRepository = app(RefreshTokenRepository::class);


        $tokenRepository->revokeAccessToken($token->id);
        $refreshTokenRepository->revokeRefreshTokensByAccessTokenId($token->id);

        return response()->json([
            'message' => 'Goodbye'
        ]);
    }
}
