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
    /**
     * Faz o login para receber o token para acesso da api
     *
     * @OA\Post(
     *   path="/api/login",
     *   tags={"Login"},
     *   @OA\Response(
     *     response=200,
     *     description="Response Successful",
     *     @OA\MediaType(
     *       mediaType="application/json",
     *       @OA\Schema(
     *         @OA\Property(
     *         property="status",
     *         type="string",
     *         example="true"
     *         ),
     *         @OA\Property(
     *           property="message",
     *           type="string",
     *           example="Generated token"
     *         ),
     *         @OA\Property(
     *           property="token",
     *           type="string",
     *           example = "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbG9jYWxob3N0Ojg5ODkvYXBpL2xvZ2luIiwiaWF0IjoxNjg3MzcxMDc5LCJleHAiOjE2ODczNzQ2NzksIm5iZiI6MTY4NzM3MTA3OSwianRpIjoiS2dJZ0lnTmoxckU0cFVubSIsInN1YiI6IjMiLCJwcnYiOiIyM2JkNWM4OTQ5ZjYwMGFkYjM5ZTcwMWM0MDA4NzJkYjdhNTk3NmY3In0.r2BDPWHjzTpsM6GAL2_hDPS7dw_XnxaeSJDGr-zfXCs"
     *         ),
     *         @OA\Property(
     *           property="expires",
     *           type="integer",
     *           example="3600"
     *         )
     *       )
     *     )
     *   ),
     *   @OA\Response(
     *     response=422,
     *     description="Credências invalidas",
     *     @OA\MediaType(
     *       mediaType="application/json",
     *       @OA\Schema(
     *         @OA\Property(
     *           property="message",
     *           type="string",
     *           example="The provided credentials are incorrect."
     *         ),
     *         @OA\Property(
     *           property="errors",
     *           type="object",
     *           example = "{email: [The provided credentials are incorrect.]}"
     *         ),
     *         @OA\Property(
     *           property="expires",
     *           type="integer",
     *           example="3600"
     *         )
     *       )
     *     )
     *   ),
     *   @OA\RequestBody(
     *     @OA\MediaType(
     *       mediaType="application/json",
     *       @OA\Schema(
     *         @OA\Property(
     *           property="email",
     *           type="string",
     *           description="E-mail do usuário",
     *           example="john@doe.com"
     *         ),
     *         @OA\Property(
     *           property="password",
     *           type="string",
     *           description="Senha do usuário",
     *           example="291089"
     *         )
     *       )
     *     )
     *   )
     * )
    **/

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

    /**
     * Cria um usuário da api.
     *
     * @OA\Post(
     *   path="/api/register",
     *   tags={"Registro"},
     *   @OA\Response(
     *     response=200,
     *     description="Response Successful",
     *     @OA\MediaType(
     *       mediaType="application/json",
     *       @OA\Schema(
     *         @OA\Property(
     *         property="status",
     *         type="string",
     *         example="true"
     *         ),
     *         @OA\Property(
     *           property="message",
     *           type="string",
     *           example="Generated token"
     *         ),
     *         @OA\Property(
     *           property="token",
     *           type="string",
     *           example = "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbG9jYWxob3N0Ojg5ODkvYXBpL2xvZ2luIiwiaWF0IjoxNjg3MzcxMDc5LCJleHAiOjE2ODczNzQ2NzksIm5iZiI6MTY4NzM3MTA3OSwianRpIjoiS2dJZ0lnTmoxckU0cFVubSIsInN1YiI6IjMiLCJwcnYiOiIyM2JkNWM4OTQ5ZjYwMGFkYjM5ZTcwMWM0MDA4NzJkYjdhNTk3NmY3In0.r2BDPWHjzTpsM6GAL2_hDPS7dw_XnxaeSJDGr-zfXCs"
     *         ),
     *         @OA\Property(
     *           property="expires",
     *           type="integer",
     *           example="3600"
     *         )
     *       )
     *     )
     *   ),
     *   @OA\Response(
     *     response=422,
     *     description="Já existe um usuário cadastrado com esse e-mail",
     *     @OA\MediaType(
     *       mediaType="application/json",
     *       @OA\Schema(
     *         @OA\Property(
     *           property="status",
     *           type="string",
     *           example="true"
     *         ),
     *         @OA\Property(
     *           property="message",
     *           type="string",
     *           example="validation error"
     *         ),
     *         @OA\Property(
     *           property="errors",
     *           type="object",
     *           example = "{email: [The email has already been taken.]}"
     *         )
     *       )
     *     )
     *   ),
     *   @OA\RequestBody(
     *     @OA\MediaType(
     *       mediaType="application/json",
     *       @OA\Schema(
     *         @OA\Property(
     *           property="name",
     *           type="string",
     *           description="Nome do usuário",
     *           example="José da Silva"
     *         ),
     *         @OA\Property(
     *           property="email",
     *           type="string",
     *           description="E-mail do usuário",
     *           example="john@doe.com"
     *         ),
     *         @OA\Property(
     *           property="password",
     *           type="string",
     *           description="Senha do usuário",
     *           example="291089"
     *         )
     *       )
     *     )
     *   )
     * )
    **/

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
