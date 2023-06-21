<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\AuthRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class AuthController extends Controller
{
    public function auth(AuthRequest $request)
    {
        $credentials = request(['email', 'password']);

        $user = User::where('email',  $credentials['email'])->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        if (!$token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return response()->json([
            'status' => true,
            'message' => "Generated token",
            'token' => $token,
            'expires' => auth()->factory()->getTTL() * 60
        ],200);
    }

    public function createUser(Request $request)
    {
        try {

            $validateUser = Validator::make($request->all(),
            [
                'name' => 'required',
                'email' => 'required|email|unique:users,email',
                'password' => 'required'
            ]);

            if ($validateUser->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }

            $password = Hash::make($request->password);

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => $password
            ]);

            return response()->json([
                'status' => true,
                'message' => 'User Created Successfully',
                'token' => $token = auth()->login($user),
                'expires' => auth()->factory()->getTTL() * 60
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function logout(Request $request)
    {
        auth()->logout();

        return response()->json([
            'message' => 'successfully logged out',
        ],200);
    }
}
