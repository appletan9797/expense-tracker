<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Repositories\UserRepository;
use App\Models\User;
class AuthController extends Controller
{
    public function __construct(private UserRepository $userRepository)
    {

    }

    public function show(Request $request)
    {
        return $request->user();
    }

    public function check($email)
    {
        return $this->userRepository->checkIsEmailExist($email);
    }

    public function register(Request $request)
    {
        try{
            $validated = $request->validate([
                'username' => 'required|unique:users,user_name',
                'email' => 'required|unique:users,email'
            ]);
        }
        catch(Exception $e){
            return response()->json([
                'status' => 'failed',
                'code' => 422,
                'message' => 'Username already exists'
            ],422);
        }

        $this->userRepository->createUser($validated['username'],$validated['email'], $request->password);

        return response()->json([
            'status' => 'success',
            'message' => 'User created'
        ],200);
    }

    public function login(Request $request)
    {
        $login = Auth::attempt([
            User::USERNAME_FIELD => $request->username,
            'password' => $request->password
        ]);

        if($login){
            $user = Auth::user();
            $token = $user->createToken('User Login Token')->plainTextToken;
            $cookie = cookie('expense_tracker_login',$token, 60 * 24 *30,null,null,false,true,'None');
            return response([
                'statusText'=>'success',
                'userid' => Auth::id(),
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
