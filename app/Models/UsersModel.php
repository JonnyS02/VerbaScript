<?php

namespace App\Models;

use App\Models\ElementModels\ElementBaseModel;
use CodeIgniter\Model;

class UsersModel extends Model
{
    public function getUsers($client_id): array
    {
        $query = $this->db->table('users');
        $query->select('users.id, users.name, users.email, roles.name as role');
        $query->where('client_id', $client_id);
        $query->join('roles', 'roles.id = users.role_id');
        $query->orderBy('users.name');
        $result = $query->get();
        return $result->getResultArray();
    }

    public function insertUser($data): void
    {
        $query = $this->db->table('users');
        $query->insert($data);
    }

    public function usernameIsValid($name, $client_id): bool
    {
        if (empty($name)) {
            return false;
        }
        $query = $this->db->table('users');
        $query->select('name');
        $query->where('client_id', $client_id);
        $query->where('name', $name);
        $result = $query->get();
        return ! count($result->getResultArray()) > 0;
    }

    public function getRoles(): array
    {
        $query = $this->db->table('roles');
        $query->select();
        $result = $query->get();
        return $result->getResultArray();
    }

    public function updateUser($user): void
    {
        $query = $this->db->table('users');
        $query->where('id', $user['id']);
        $query->where('client_id', $user['client_id']);
        $query->update($user);
    }

    public function deleteUser($user_id, $client_id): void
    {
        $query = $this->db->table('users');
        $query->where('id', $user_id);
        $query->where('client_id', $client_id);
        $query->delete();
    }

    public function getTakenUserNamesForJS($client_id, $exclude = null): string
    {
        $result = $this->getUsers($client_id);
        $generalModel = new ElementBaseModel();
        return $generalModel->getTakenNamesForJS($result, $exclude);
    }

    public function getUser($user_id, $client_id): array
    {
        $query = $this->db->table('users');
        $query->select('users.id, users.name, users.email, users.role_id');
        $query->where('client_id', $client_id);
        $query->where('users.id', $user_id);
        $result = $query->get();
        return $result->getResultArray()[0];
    }
}