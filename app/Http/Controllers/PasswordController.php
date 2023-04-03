<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\PasswordResetTokenRepository;
use App\Repositories\UserRepository;
use Illuminate\Mail\SentMessage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;

class PasswordController extends Controller
{
    public function __construct(private UserRepository $userRepository, private PasswordResetTokenRepository $passwordResetTokenRepository)
    {

    }

    public function update(Request $request, $userId){
        $user = $this->getUserById($userId);
        if(!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }

        return $this->savePasswordToDB($user,$request->newPassword);
    }

    public function handleForgotPassword(Request $request){
        $validated = $this->validateIsEmailExistInUserTable($request);
        if($validated instanceof \Illuminate\Http\JsonResponse){
            return $validated;
        }

        $token = $this->saveNewPasswordResetToken($validated['email']);
        if($token instanceof \Illuminate\Http\JsonResponse){
            return $token;
        }

        return $this->sendEmail($token,$validated);
    }

    public function saveNewPasswordResetToken($email){
        $token = Str::random(64);
        try{
            $this->passwordResetTokenRepository->createNewToken($token,$email);
        }
        catch(\Exception $e){
            return response()->json([
                'success' => false,
                'message' => 'New password reset token record creation failed: ' . $e->getMessage()
            ], 400);
        }

        return $token;
    }

    public function sendEmail($token, $validated){
        $sendEmail = (Mail::send(
            'reset-password',
            ['token' => $token],
            function($message) use ($validated) {
                $message->to($validated['email'], '')
                    ->subject('Reset Password');
            }
            ));

        if ($sendEmail instanceof SentMessage){
            return response()->json([
                'success' => true,
                'message' => 'Email sent successfully'
            ],200);
        }
        else{
            return response()->json([
                'success' => false,
                'message' => 'Email not sent: '
            ],500);
        }
    }

    public function handleResetPassword(Request $request){
        $validated = $this->validateIsEmailExistInUserTable($request);
        if($validated instanceof \Illuminate\Http\JsonResponse){
            return $validated;
        }

        $tokenEmailRecord = $this->checkIsTokenEmailRecordExist($validated['email'],$request->token);
        if($tokenEmailRecord instanceof \Illuminate\Http\JsonResponse){
            return $tokenEmailRecord;
        }

        $userToUpdatePassword = $this->userRepository->getUserByEmail($validated['email']);
        return $this->savePasswordToDB($userToUpdatePassword, $request->password);
    }

    public function checkIsTokenEmailRecordExist($email,$token){
        $tokenEmailRecord = $this->passwordResetTokenRepository->getRecordByEmailAndToken($email,$token);

        if(!$tokenEmailRecord){
            return response()->json([
                'success' => false,
                'message' => 'Token record not found'
            ], 404);
        }
    }

    public function savePasswordToDB($user, $password){
        try{
            $user = $this->userRepository->updateUserPassword($user,$password);
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
        return $this->userRepository->getUserById($userId);
    }

    public function validateIsEmailExistInUserTable($request){
        try{
            $validated = $request->validate([
                'email'=>"required|email|exists:users,email",
            ]);
        }
        catch(\Exception $e){
            return response()->json([
                'success' => false,
                'message' => 'There is an error: '.$e->getMessage()
            ],422);
        }

        return $validated;
    }
}
