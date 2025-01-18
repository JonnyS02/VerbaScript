<?php

namespace App\Models\ElementModels;

use CodeIgniter\Model;

class VariableModel extends Model
{
    public function insertVariable($get_post, $template_id): void
    {
        $general_model = new ElementBaseModel();
        $variable_id = $general_model->insertElement($get_post, 'variable', $template_id);
        if ($variable_id === 0) {
            return;
        }
        $type = $this->typeExists($get_post['input_type']) ? $get_post['input_type'] : 1;
        $data = [
            'element_id' => $variable_id,
            'input_type_id' => $type,
        ];
        $this->db->table('variables')->insert($data);
    }

    public function typeExists($input_type_id): bool
    {
        $query = $this->db->table('input_types');
        $query->select();
        $query->where('id', $input_type_id);
        $result = $query->get();
        return $result->getNumRows() > 0;
    }

    public function editVariable($get_post, $template_id): void
    {
        $general_model = new ElementBaseModel();
        if (!$general_model->editElement($get_post, $template_id)) {
            return;
        }
        $type = $this->typeExists($get_post['input_type']) ? $get_post['input_type'] : 1;
        $data = [
            'input_type_id' => $type,
        ];
        $query = $this->db->table('variables');
        $query->where('element_id', $get_post['id']);
        $query->update($data);
    }

    public function getVariable($variable_id, $template_id): ?array
    {
        $general_model = new ElementBaseModel();
        $element = $general_model->getElement($variable_id, $template_id);
        if ($element === null) {
            return null;
        }
        $query = $this->db->table('variables');
        $query->select();
        $query->where('element_id', $variable_id);
        $result = $query->get();
        $variable = $result->getRowArray();
        return array_merge($element, $variable);
    }

    public function getInputTypes(): ?array
    {
        $query = $this->db->table('input_types');
        $query->select();
        $result = $query->get();
        return $result->getResultArray();
    }
}