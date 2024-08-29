<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    // public function __construct(){
    //     $this->middleware("auth:api", ["except"=> ["login","register"]]);
    // }

    public function login(Request $request){
        $request->validate([
            'email'=>'required|email',
            'password'=> 'required|string|min:6',
        ]);

        $credentials = request(['email','password']);
        if (!$token = Auth::attempt($credentials)) {
            return response()->json([
                'error'=>'Unauthorized'
            ], 401);
        }

        return response()->json([
            'access_token' =>  $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::factory()->getTTL()
        ], 200);
    }

    public function me(){
        return response()->json(Auth::user(),200);
    }

    public function logout(){
        JWTAuth::invalidate(JWTAuth::getToken());
        return response()->json(['message'=> 'Successfully Logout'],200);
    }

    public function Register(Request $request){
        $request->validate([
            'name'=>'required|string',
            'email'=> 'required|email|unique:users',
            'password'=> 'required|confirmed|string|min:6',
        ]);

        $user = User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=> Hash::make($request->password),
        ]);

        return response()->json(['message'=>'Register Sukses'], 200);
    }
}
