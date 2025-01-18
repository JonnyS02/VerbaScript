<?php

namespace App\Models;

use CodeIgniter\Model;

class LoginModel extends Model
{
    public function decreaseLoginAttempts($user_id): void
    {
        $login_attempts = $this->getLoginAttempts($user_id, null);
        if ($login_attempts > 0) {
            $query = $this->db->table('users');
            $query->where('id', $user_id);
            $query->set('login_attempts', 'login_attempts - 1', FALSE);
            $query->update();
        }
    }

    public function getLoginAttempts($user_id, $email): int
    {
        $query = $this->db->table('users');
        $query->select('login_attempts');
        if ($user_id == null) {
            $query->where('email', $email);
        } else {
            $query->where('id', $user_id);
        }
        $result = $query->get()->getRow();
        if($result == null) {
            return -1;
        }
        return $result->login_attempts;
    }


    public function resetLoginAttempts($user_id): void
    {
        $query = $this->db->table('users');
        $query->where('id', $user_id);
        $query->update(array('login_attempts' => 3));
    }

    public function setAttemptsToResetCode($email): int|bool
    {
        $login_attempts = $this->getLoginAttempts(null, $email);
        if($login_attempts == -1) {
            return false;
        }
        if ($login_attempts <= 0) {
            $code = -random_int(100000, 999999);
            $query = $this->db->table('users');
            $query->where('email', $email);
            $query->set('login_attempts', $code);
            $query->update();
            return $code;
        }
        return false;
    }
}