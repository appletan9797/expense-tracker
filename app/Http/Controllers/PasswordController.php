<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class PasswordController extends Controller
{
    public function updatePassword(Request $request, $userId){
        $user = $this->getUserById($userId);
        if(!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }

        try{
            $user->password = Hash::make($request->newPassword);
            $user->save();
        }
        catch(\Exception $e){
            return response()->json([
                'success' => false,
                'message' => 'Password update failed: ' . $e->getMessage()
            ], 400);
        }

        return response()->json([
            'success' => true,
            'message' => 'Password updated successfully',
            'user' => $user
        ], 200);
    }

    public function getUserById($userId){
        $user = User::where('user_id', $userId)->first();
        return $user;
    }
}
