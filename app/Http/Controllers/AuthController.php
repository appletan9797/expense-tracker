<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request){
        try{
            $validated = $request->validate([
                'username' => 'required|unique:users,user_name'
            ]);
        }
        catch(Exception $e){
            return response()->json([
                'status' => 'failed',
                'code' => 422,
                'message' => 'Username already exists'
            ],422);
        }

        $user = new User();
        $user->user_name = $validated['username'];
        $user->password = Hash::make($request->password);
        $user->save();

        return response()->json([
            'status' => 'success',
            'message' => 'User created'
        ],200);
    }

    public function login(Request $request){
        $login = Auth::attempt([
            User::USERNAME_FIELD => $request->username,
            'password' => $request->password
        ]);

        if($login){
            $user = Auth::user();
            $token = $user->createToken('My Token')->plainTextToken;
            $cookie = cookie('expense_tracker_login',$token, 60 * 24 *30);
            return response([
                'statusText'=>'success'
            ])->withCookie($cookie);
        }
        else{
            return response()->json([
                'statusText' => 'failed',
                'message'=> 'Invalid credentials',
            ],401);
        }
    }
}
