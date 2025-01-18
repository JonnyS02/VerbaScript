<?php

namespace App\Models;

use CodeIgniter\Model;

class InvitationsModel extends Model
{
    public function getInvitations($client_id): array
    {
        $query = $this->db->table('invitations');
        $query->where('client_id', $client_id);
        $query->select('invitations.id, invitations.name, email, roles.name as role');
        $query->join('roles', 'roles.id = invitations.role_id');
        return $query->get()->getResultArray();
    }

    public function getInvitation($invitation_id, $client_id): array
    {
        $query = $this->db->table('invitations');
        $query->where('invitations.id', $invitation_id);
        $query->where('client_id', $client_id);
        $query->select('invitations.id, invitations.name, email, roles.id as role_id, message');
        $query->join('roles', 'roles.id = invitations.role_id');
        return $query->get()->getRowArray();
    }

    public function deleteInvitation($invitation_id, $client_id): void
    {
        $query = $this->db->table('invitations');
        $query->where('id', $invitation_id);
        $query->where('client_id', $client_id);
        $query->delete();
    }

    public function insertInvitation($invitation): void
    {
        $query = $this->db->table('invitations');
        $query->insert($invitation);
    }

    public function invitationIsValid($code, $email): bool|array
    {
        $query = $this->db->table('invitations');
        $query->select('id, client_id');
        $query->where('code', $code);
        $query->where('email', $email);
        $result = $query->get()->getRow();
        if ($result) {
            return [
                'client_id' => $result->client_id,
                'invitation_id' => $result->id
            ];
        }
        return false;
    }
}