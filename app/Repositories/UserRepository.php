<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserRepository
{
    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function createUser($username, $email, $password)
    {
        $user = $this->user;
        $user->user_name = $username;
        $user->email = $email;
        $user->password = Hash::make($password);
        $user->save();
    }

    public function getUserById($userId)
    {
        return $this->user->where('user_id',$userId)->first();
    }

    public function getUserByEmail($email)
    {
        return $this->user->where('email',$email)->first();
    }

    public function updateUserPassword($user, $password)
    {
        $user->password = Hash::make($password);
        $user->save();
        return $user;
    }

    public function checkIsEmailExist($email)
    {
        $emailRecord = $this->user->where('email',$email)->count();
        return $emailRecord == 1 ? 1 : 0;
    }
}
