<?php

declare(strict_types=1);


namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate(
            [
                'email'    => 'required|email',
                'password' => 'required',
            ]
        );

        $user = null;

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
        } else {
            $user = new User();

            $user->name     = $credentials['email'];
            $user->email    = $credentials['email'];
            $user->password = bcrypt($credentials['password']);
            $user->save();

            $user->createToken('Auth_Token');
        }

        $userData = [
            'id'    => $user->id,
            'name'  => $user->name,
            'email' => $user->email,
            'token' => $user->tokens()->first()->token,
            'roles'  => $user->roles,
        ];

        return response()->json(['message' => 'OK', 'user' => $userData]);
    }
}
