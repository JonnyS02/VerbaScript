<?php

namespace App\Models\ElementModels;

use CodeIgniter\Model;

class SelectModel extends Model
{

    public function insertSelect($get_post, $template_id): void
    {
        $general_model = new ElementBaseModel();
        $select_id = $general_model->insertElement($get_post, 'select', $template_id);
        if ($select_id === 0) {
            return;
        }
        $data = [
            'element_id' => $select_id,
            'allow_individual' => isset($get_post['allow_individual']) ? 1 : 0,
            'standard_option' => $this->validateStandardOption($get_post['options'], $get_post['standard_option']),
        ];
        $this->db->table('selects')->insert($data);
        $this->insertOptions($get_post['options'], $select_id);
    }

    public function editSelect($get_post, $template_id): void
    {
        $generalModel = new ElementBaseModel();
        if (!$generalModel->editElement($get_post, $template_id)) {
            return;
        }
        $data = [
            'element_id' => $get_post['id'],
            'allow_individual' => isset($get_post['allow_individual']) ? 1 : 0,
            'standard_option' => $this->validateStandardOption($get_post['options'], $get_post['standard_option']),
        ];
        $query = $this->db->table('selects');
        $query->where('element_id', $get_post['id']);
        $query->update($data);
        $query = $this->db->table('options');
        $query->where('select_id', $get_post['id']);
        $query->delete();
        $this->insertOptions($get_post['options'], $get_post['id']);
    }

    public function validateStandardOption($options,$standard_option): float|int|string
    {
        if (isset($standard_option) && is_numeric($standard_option) && $standard_option <= count($options) && $standard_option > 0) {
            return $standard_option;
        }
        return 0;
    }

    public function insertOptions($options, $select_id): void
    {
        $query = $this->db->table('options');
        foreach ($options as $option) {
            $data = [
                'select_id' => $select_id,
                'name' => $option['name'],
                'content' => $option['content'],
            ];
            $query->insert($data);
        }
    }

    public function getSelect($select_id, $template_id): ?array
    {
        $generalModel = new ElementBaseModel();
        $element = $generalModel->getElement($select_id, $template_id);
        if ($element === null) {
            return null;
        }
        $query = $this->db->table('selects');
        $query->select();
        $query->where('element_id', $select_id);
        $element['additional'] = $query->get()->getRowArray();
        $element['options'] = $this->getOptions($select_id);
        return $element;
    }

    public function getOptions($select_id): ?array
    {
        $query = $this->db->table('options');
        $query->select();
        $query->where('select_id', $select_id);
        $result = $query->get();
        return $result->getResultArray();
    }
}