<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $validator = Validator::make($request->all(), [
        'name' => 'required',
        'email' => 'required|unique:users|email',
        'password' => 'required|string|min:5'
        ]);

        if ($validator->fails()){
            return response()->json([
                'message' => 'Invalid field',
                'errors' => $validator->errors()
            ],422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        $token = $user->createToken('ApiToken')->plainTextToken;

        $response = [
            'status' => 'Success',
            'message' => 'Register User Berhasil !',
            'user' => $user,
            'accessToken' => $token
        ];

        return response($response,201);
    }
}
