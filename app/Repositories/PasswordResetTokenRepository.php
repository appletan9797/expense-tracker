<?php

namespace App\Repositories;

use App\Models\PasswordResetToken;

class PasswordResetTokenRepository
{
    protected $passwordResetToken;

    public function __construct(PasswordResetToken $passwordResetToken)
    {
        $this->passwordResetToken = $passwordResetToken;
    }

    public function createNewToken($token, $email)
    {
        $newTokenRecord = $this->passwordResetToken;
        $newTokenRecord->email= $email;
        $newTokenRecord->token = $token;
        $newTokenRecord->save();
        return $newTokenRecord;
    }

    public function getRecordByEmailAndToken($email,$token)
    {
        return $this->passwordResetToken
                ->where([
                        "email" => $email,
                        "token" => $token
                ])->latest()
                ->first();
    }
}
