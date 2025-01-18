<?php

namespace App\Models;

use CodeIgniter\Model;

class ProfileModel extends Model
{
    public function getProfile($email, $user_id): ?array
    {
        $query = $this->db->table('users');
        $query->select('users.name, users.email, roles.name as role, users.id, users.role_id, users.client_id, users.password, users.login_attempts');
        if ($email != null) {
            $query->where('users.email', $email);
        }
        if ($user_id != null) {
            $query->where('users.id', $user_id);
        }
        $query->join('roles', 'roles.id = users.role_id');
        $result = $query->get();
        return ($result->getResultArray()[0] ?? null);
    }

    public function emailTaken($email): bool
    {
        $query = $this->db->table('users');
        $query->select('email');
        $query->where('email', $email);
        $result = $query->get();
        return !empty($result->getResultArray());
    }

    public function changeProfile($user_id, $column, $value): void
    {
        $query = $this->db->table('users');
        $query->where('id', $user_id);
        $query->update(array($column => $value));
    }

    public function getClientName($client_id): string
    {
        $query = $this->db->table('clients');
        $query->select('name');
        $query->where('id', $client_id);
        $result = $query->get();
        $result = $result->getResultArray();
        return $result[0]['name'];
    }
}