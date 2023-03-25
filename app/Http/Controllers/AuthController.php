<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
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
}
