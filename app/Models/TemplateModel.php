<?php

namespace App\Models;

use App\Models\ElementModels\ElementBaseModel;
use CodeIgniter\Model;

class TemplateModel extends Model
{
    public function getTemplate($template_id, $client_id): array
    {
        $query = $this->db->table('templates');
        $query->select();
        $query->where('id', $template_id);
        $query->where('client_id', $client_id);
        $result = $query->get();
        if ($result->getNumRows() == 0) {
            return [];
        }
        return $result->getResultArray()[0];
    }

    public function getTemplateName($template_id)
    {
        $query = $this->db->table('templates');
        $query->select('name');
        $query->where('id', $template_id);
        $result = $query->get();
        if ($result->getNumRows() == 0) {
            return '#0';
        }
        return $result->getResultArray()[0]['name'];
    }

    public function getTemplates($client_id, $display_filter = false): array
    {
        $result = $this->db->table('templates');
        $result->select();
        $result->where('client_id', $client_id);
        if ($display_filter) {
            $result->where('display', 1);
        }
        $result->orderBy('name');
        return $result->get()->getResultArray();
    }

    public function deleteTemplate($template_id, $client_id): void
    {
        $result = $this->db->table('templates');
        $result->where('id', $template_id);
        $result->where('client_id', $client_id);
        $result->delete();
    }

    public function nameExists($name, $client_id): bool
    {
        $result = $this->db->table('templates');
        $result->select('name');
        $result->where('name', $name);
        $result->where('client_id', $client_id);
        $result = $result->get()->getResultArray();
        return count($result) > 0;
    }

    public function insertTemplate($name, $client_id, $filename, $display, $global_ai): int|string
    {
        $data = [
            'name' => $name,
            'client_id' => $client_id,
            'filename' => $filename,
            'display' => $display,
            'global_ai' => $global_ai
        ];
        $this->db->table('templates')->insert($data);
        return $this->db->insertID();
    }

    public function getTakenTemplateNamesForJS($client_id, $exclude = null): string
    {
        $result = $this->getTemplates($client_id);
        $generalModel = new ElementBaseModel();
        return $generalModel->getTakenNamesForJS($result, $exclude);
    }

    public function updateTemplate($template_id, $client_id, $name, $filename, $display, $global_ai): void
    {
        $data = [
            'name' => $name,
            'display' => $display,
            'global_ai' => $global_ai
        ];
        if ($filename != null) {
            $data['filename'] = $filename;
        }
        $result = $this->db->table('templates');
        $result->where('id', $template_id);
        $result->where('client_id', $client_id);
        $result->update($data);
    }
}