<?php

namespace App\Models\ElementModels;

use CodeIgniter\Model;

class GroupModel extends Model
{
    public function getGroups($template_id): array
    {
        $query = $this->db->table('groups');
        $query->select();
        $query->where('template_id', $template_id);
        $query->orderBy('name');
        $result = $query->get();
        return $result->getResultArray();
    }

    public function insertGroup($name, $template_id): void
    {
        $general_model = new ElementBaseModel();
        if ($general_model->checkNameAndTemplate($name, $template_id,true)) {
            return;
        }
        $data = [
            'name' => $name,
            'template_id' => $template_id,
            'position' => $general_model->getNextPosition($template_id, 'groups'),
        ];
        $this->db->table('groups')->insert($data);
    }

    public function updateGroup($name, $group_id, $template_id): void
    {
        $general_model = new ElementBaseModel();
        if ($general_model->checkNameAndTemplate($name, $template_id,true)) {
            return;
        }
        $data = [
            'name' => $name,
            'template_id' => $template_id
        ];
        $query = $this->db->table('groups');
        $query->where('id', $group_id);
        $query->update($data);
    }

    public function getGroupNames($template_id): array
    {
        $groups = $this->getGroups($template_id);
        $group_names = [];
        foreach ($groups as $group) {
            $group_names[] = $group['name'];
        }
        return $group_names;
    }

    public function checkValidGroup($group_id, $template_id): bool
    {
        $query = $this->db->table('groups');
        $query->select();
        $query->where('template_id', $template_id);
        $query->where('id', $group_id);
        $result = $query->get();
        return $result->getNumRows() > 0;
    }
}