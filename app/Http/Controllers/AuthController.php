<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request){

        // validate user data
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // check to see user exist in database
        if(!Auth::attempt($validated)){
            return response()->json([
                'message' => 'Login information invalid'
            ], 401);
        }

        $user = User::where('email', $validated['email'])->first();
        return response()->json([
            'access_token' => $user->createToken('api_token')->plainTextToken,
            'token_type' => 'Bearer'
        ], 200);
    }

    // api/register
    // body{ "name", "email", "password", "password_confirmation" }
    public function register(Request $request){

        $validated = $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|max:255|email|unique:users,email',
            'password' => 'required|confirmed|min:6'
        ]);

        // hash password
        $validated['password'] = Hash::make($validated['password']);

        // create user
        $user = User::create($validated);

        return response()->json([
            'data' => $user,
            'access_token' => $user->createToken('api_token')->plainTextToken,
            'token_type' => 'Bearer'
        ], 201);
    }
}
