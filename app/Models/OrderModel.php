<?php

namespace App\Models;

use App\Models\ElementModels\GroupModel;
use CodeIgniter\Model;

class OrderModel extends Model
{
    public function getOrder($template_id): array
    {
        $query = $this->db->table('elements');
        $query->select('elements.id, elements.name, elements.position, elements.display, elements.separate_after, elements.field_length, groups.name as group, elements.element_type, elements.ai_marker');
        $query->join('groups', 'groups.id = elements.group_id', 'left');
        $query->where('elements.template_id', $template_id);
        $query->orderBy('position', 'ASC');
        $result = $query->get();
        $items = $result->getResultArray();
        $items = $this->combineWithGroups($items, $template_id);
        return $this->addGermanType($items);
    }

    public function combineWithGroups($items, $template_id): array
    {
        $group_model = new GroupModel();
        $groups = $group_model->getGroups($template_id);
        $groups = array_map(function($group) {
            $group['element_type'] = 'group';
            return $group;
        }, $groups);
        $combined = array_merge($groups, $items);
        usort($combined, function($a, $b) {
            if ($a['position'] == $b['position']) {
                return $a['element_type'] === 'group' ? -1 : 1;
            }
            return $a['position'] <=> $b['position'];
        });
        return $combined;
    }

    public function addGermanType($items): array
    {
        foreach ($items as &$item) {
            if ($item['element_type'] == 'select') {
                $item['germanType'] = 'Select';
            } elseif ($item['element_type'] == 'variable') {
                $item['germanType'] = 'Variable';
            } elseif ($item['element_type'] == 'number') {
                $item['germanType'] = 'Zahl';
            }elseif ($item['element_type'] == 'group') {
                $item['germanType'] = 'Gruppe';
            }
        }
        return $items;
    }

    public function updateOrder($elements, $template_id): void
    {
        $query = $this->db->table('elements');
        $query->where('template_id', $template_id);
        $query->updateBatch($elements, 'id');
    }

    public function updateOderGroup($position, $display, $group_id, $template_id): void
    {
        $data = [
            'position' => $position,
            'display' => $display,
        ];
        $query = $this->db->table('groups');
        $query->where('id', $group_id);
        $query->where('template_id', $template_id);
        $query->update($data);
    }
}