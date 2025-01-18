<?php

namespace App\Models\ElementModels;

use CodeIgniter\Model;

class NumberModel extends Model
{

    public function insertNumber($get_post, $template_id): void
    {
        $general_model = new ElementBaseModel();
        $number_id = $general_model->insertElement($get_post, 'number', $template_id);
        if ($number_id === 0) {
            return;
        }
        $data = [
            'element_id' => $number_id,
        ];
        $this->db->table('numbers')->insert($data);
        $this->insertPercentages($get_post, $number_id);
    }

    public function editNumber($get_post, $template_id): void
    {
        $general_model = new ElementBaseModel();
        if (!$general_model->editElement($get_post, $template_id)) {
            return;
        }
        $query = $this->db->table('percentages');
        $query->where('number_id', $get_post['id']);
        $query->delete();
        $this->insertPercentages($get_post, $get_post['id']);
    }

    public function insertPercentages($get_post, $number_id): void
    {
        $percentages = [];
        foreach ($get_post as $key => $value) {
            if (str_starts_with($key, 'percentage')) {
                if (empty($value)) {
                    continue;
                }
                $percentages[] = [
                    'number_id' => $number_id,
                    'value' => $value,
                ];
            }
        }
        if (empty($percentages)) {
            return;
        }
        $this->db->table('percentages')->insertBatch($percentages);
    }

    public function getNumber($number_id, $template): ?array
    {
        $general_model = new ElementBaseModel();
        $number = $general_model->getElement($number_id, $template);
        if ($number === null) {
            return null;
        }
        $number['percentages'] = $this->getPercentages($number_id);
        return $number;
    }

    public function getPercentages($number_id): ?array
    {
        $query = $this->db->table('percentages');
        $query->select();
        $query->where('number_id', $number_id);
        $result = $query->get();
        return $result->getResultArray();
    }
}