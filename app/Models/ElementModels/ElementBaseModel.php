<?php

namespace App\Models\ElementModels;

use CodeIgniter\Model;

class ElementBaseModel extends Model
{
    public function deleteElement($element_id, $template_id, $table): void
    {
        $query = $this->db->table($table);
        $query->where('id', $element_id);
        $query->where('template_id', $template_id);
        $query->delete();
    }

    public function insertElement($get_post, $element_type, $template_id): int
    {
        if ($this->checkNameAndTemplate($get_post['name'], $template_id)) {
            return 0;
        }
        $data = [
            'name' => $get_post['name'],
            'info' => $get_post['info'],
            'ai_info' => $get_post['ai_info'],
            'required' => isset($get_post['required']) ? 1 : 0,
            'omit' => isset($get_post['omit']) ? 1 : 0,
            'position' => $this->getNextPosition($template_id, 'elements'),
            'display' => 1,
            'separate_after' => 0,
            'field_length' => 12,
            'template_id' => $template_id,
            'element_type' => $element_type
        ];

        $group_model = new GroupModel();
        if ($group_model->checkValidGroup($get_post['group'], $template_id)) {
            $data['group_id'] = $get_post['group'];
        }
        $query = $this->db->table('elements');
        $query->insert($data);
        return $this->db->insertID();
    }

    public function editElement($get_post, $template_id): bool
    {
        if (!$this->elementExists($get_post['id'], $template_id, 'elements')) {
            return false;
        }
        $data = [
            'info' => $get_post['info'],
            'ai_info' => $get_post['ai_info'],
            'required' => isset($get_post['required']) ? 1 : 0,
            'omit' => isset($get_post['omit']) ? 1 : 0,
        ];
        if (!$this->checkNameAndTemplate($get_post['name'], $template_id)) {
            $data['name'] = $get_post['name'];
        }
        $group_model = new GroupModel();
        if ($group_model->checkValidGroup($get_post['group'], $template_id)) {
            $data['group_id'] = $get_post['group'];
        } else {
            $data['group_id'] = null;
        }
        $query = $this->db->table('elements');
        $query->where('id', $get_post['id']);
        $query->where('template_id', $template_id);
        $query->update($data);
        return true;
    }

    public function getElements($template_id, $element_type): ?array
    {
        $query = $this->db->table('elements');
        $query->select('elements.id, elements.name, groups.name as group');
        $query->join('groups', 'elements.group_id = groups.id', 'left');
        $query->where('elements.template_id', $template_id);
        $query->where('elements.element_type', $element_type);
        $query->orderBy('elements.position', 'ASC');
        $result = $query->get();
        return $result->getResultArray();
    }


    public function getElement($element_id, $template_id): ?array
    {
        $query = $this->db->table('elements');
        $query->select('elements.id, elements.name, elements.group_id, groups.name as group, elements.info, elements.ai_info, elements.required, elements.omit, elements.element_type');
        $query->join('groups', 'elements.group_id = groups.id', 'left');
        $query->where('elements.id', $element_id);
        $query->where('elements.template_id', $template_id);
        $result = $query->get();
        return $result->getRowArray();
    }

    /**
     * @param string $table The table in which the elements are stored. It can be either 'elements' or 'groups'.
     */
    public function getElementNames($template_id, string $table): array
    {
        $query = $this->db->table($table);
        $query->select('name');
        $query->where('template_id', $template_id);
        $result = $query->get();
        return $result->getResultArray();
    }

    public function elementExists($element_id, $template_id, $table): bool
    {
        $query = $this->db->table($table);
        $query->select();
        $query->where('id', $element_id);
        $query->where('template_id', $template_id);
        $result = $query->get();
        return !empty($result->getResultArray());
    }

    public function templateExists($template_id): bool
    {
        $query = $this->db->table('templates');
        $query->select();
        $query->where('id', $template_id);
        $result = $query->get();
        return !empty($result->getResultArray());
    }

    /** Returns true if any of the following conditions are met:
     * 1. The name is already in use within the template.
     * 2. The name is an empty string.
     * 3. The template does not exist.
     * 4. The name is not from a group but is listed in the blacklist.
     */
    public function checkNameAndTemplate($name, $template_id, bool $is_group = false): bool
    {
        if (empty($name) || !$this->templateExists($template_id)) {
            return true;
        }
        $tables = $is_group ? array('groups') : array('elements', 'blacklist');
        foreach ($tables as $table) {
            $query = $this->db->table($table);
            $query->select();
            if ($table !== 'blacklist') {
                $query->where('template_id', $template_id);
            }
            $query->where('name', $name);
            $result = $query->get();
            if (!empty($result->getResultArray())) {
                return true;
            }
        }
        return false;
    }

    public function getBlacklist(): array
    {
        $groups = $this->db->table('blacklist');
        $groups->select();
        $result = $groups->get();
        return $result->getResultArray();
    }

    /**
     * Generates a JavaScript array of objects with the names and error messages of the blacklist. Elements with these names are not allowed, except for groups.
     * It's used for clientside name validation.
     */
    public function getBlacklistForJS(): string
    {
        $array = $this->getBlacklist();
        if (empty($array)) {
            $content = '[]';
        } else {
            $jsArray = [];
            foreach ($array as $item) {
                if (isset($item['name']) && isset($item['error_message'])) {
                    $name = addslashes($item['name']);
                    $errorMessage = addslashes($item['error_message']);
                    $jsArray[] = "{\"name\":\"$name\",\"errorMessage\":\"$errorMessage\"}";
                }
            }
            $content = '[' . implode(',', $jsArray) . ']';
        }
        return "let specialNames = $content;";
    }

    /**
     * Generates a JavaScript array of the taken element-names within a template. It's used for clientside name validation.
     * @param ?string $exclude The name that should be excluded from the array. It's used when editing an element.
     */
    public function getTakenElementNamesForJS($template_id, ?string $exclude, $table = 'elements'): string
    {
        $array = $this->getElementNames($template_id, $table);
        $use_blacklist = $table == 'elements';
        return $this->getTakenNamesForJS($array, $exclude, $use_blacklist);
    }

    /**
     * Generates a JavaScript array of object-names based on the given query-result. It's used for clientside name validation.
     * @param array $result The query-result containing the names.
     * @param ?string $exclude The name that should be excluded from the array. It's used when editing an element.
     */
    public function getTakenNamesForJS(array $result, ?string $exclude, $use_blacklist = false): string
    {
        $js_array = 'let existingNames = [  ';
        foreach ($result as $name) {
            if ($name['name'] == $exclude) {
                continue;
            }
            $js_array .= '"' . $name['name'] . '",';
        }
        $js_array = substr($js_array, 0, -1);
        $js_array .= '];';
        $js_array .= $use_blacklist ? $this->getBlacklistForJS() : 'let specialNames = "";';
        return $js_array;
    }

    /**
     * Returns the next position for a new element within a template.
     * @param string $table The table in which the elements are stored. It can be either 'elements' or 'groups'.
     */
    public function getNextPosition($template_id, string $table): int
    {
        $query = $this->db->table($table);
        $query->select('position');
        $query->where('template_id', $template_id);
        $query->orderBy('position', 'DESC');
        $query->limit(1);
        $result = $query->get();
        $result = $result->getResultArray();
        if (!empty($result)) {
            return $result[0]['position'] + 1;
        }
        return 1;
    }
}