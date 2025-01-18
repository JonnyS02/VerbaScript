<?php

namespace App\Models;

use App\Controllers\Forms;
use App\Models\ElementModels\ElementBaseModel;
use App\Models\ElementModels\GroupModel;
use CodeIgniter\Model;

class FormModel extends Model
{
    public function getForms($user_id, $get_is_ready = false): array
    {
        $query = $this->db->table('forms');
        $query->select('forms.id, forms.name, templates.name as template, forms.last_edit, templates.id as template_id');
        $query->join('templates', 'templates.id = forms.template_id');
        $query->where('templates.display', 1);
        $query->where('forms.user_id', $user_id);
        $query->orderBy('forms.last_edit', 'DESC');
        $result = $query->get();
        if ($get_is_ready) {
            $forms = $result->getResultArray();
            foreach ($forms as &$form) {
                $form['is_ready'] = $this->formIsReady($form['id'], $form['template_id']);
            }
            return $forms;
        }
        return $result->getResultArray();
    }

    public function getActualForm($form_id, $client_id, $user_id, $ai_section): array
    {
        $data = $this->getForm($form_id, $user_id);
        $template_model = new TemplateModel();
        $data['global_ai'] = $template_model->getTemplate($data['template_id'], $client_id)['global_ai'];
        $data['elements'] = $this->getFormElements($data['template_id'], $form_id);
        if ($ai_section != 0) { // If the AI-section is 0 the whole form is returned, since it is a global language input, dealing with the whole form.
            $data['elements'] = $this->aiSectionFilter($data['elements'], $ai_section);
        }
        return $data;
    }

    /**
     * Filters the elements array to only include the elements of the specified AI-section, based on the ai_marker column in the elements-table.
     */
    public function aiSectionFilter($elements, $ai_section): array
    {
        $elements = (new Forms)->removeGroups($elements);
        $counter = 0;
        $filtered_elements = [];
        $index = 0;
        foreach ($elements as $element) {
            if ($element['ai_marker'] == 1) {
                $counter++;
                if ($counter == $ai_section) {
                    while ($index < count($elements) && $elements[$index]['ai_marker'] != -1) {
                        if ($elements[$index]['display']) {
                            $filtered_elements[] = $elements[$index];
                        }
                        $index++;
                    }
                    if ($index < count($elements) && $elements[$index]['display']) {
                        $filtered_elements[] = $elements[$index];
                    }
                    break;
                }
            }
            $index++;
        }
        return $filtered_elements;
    }

    public function getForm($form_id, $user_id)
    {
        $query = $this->db->table('forms');
        $query->select('forms.name, templates.name as template, templates.id as template_id');
        $query->join('templates', 'templates.id = forms.template_id');
        $query->where('forms.id', $form_id);
        $query->where('forms.user_id', $user_id);
        $result = $query->get();
        if ($result->getNumRows() == 0) {
            return [];
        }
        return $result->getResultArray()[0];
    }

    public function getSelectOptions($select_id): array
    {
        $query = $this->db->table('options');
        $query->select();
        $query->where('select_id', $select_id);
        $result = $query->get();
        return $result->getResultArray();
    }

    public function getFormElements($template_id, $form_id = null): array
    {
        $query = $this->db->table('elements');
        $base_query = 'elements.*, selects.standard_option, selects.allow_individual, input_types.type,';
        if ($form_id == null) {
            $query->select($base_query . ' null as value, null as custom_select');
        } else {
            $query->select($base_query . ' entries.value, entries.custom_select');
        }
        $query->where('template_id', $template_id);
        $query->orderBy('position', 'ASC');
        $query->join('selects', 'selects.element_id = elements.id', 'left');
        $query->join('variables', 'variables.element_id = elements.id', 'left');
        $query->join('input_types', 'input_types.id = variables.input_type_id', 'left');
        if ($form_id != null) {
            $query->join('entries', 'entries.element_id = elements.id AND entries.form_id = ' . $this->db->escape($form_id), 'left');
        }
        $query = $query->get();
        $result = $query->getResultArray();
        foreach ($result as $key => $element) {
            if ($element['element_type'] == 'select') {
                $result[$key]['options'] = $this->getSelectOptions($element['id']);
            }
        }
        return $this->addGroups($template_id, $result);
    }

    public function checkNameAndTemplate($client_id, $user_id, $name, $template_id): bool
    {
        if ($name == '') {
            return false;
        }
        $query = $this->db->table('templates');
        $query->select();
        $query->where('client_id', $client_id);
        $query->where('id', $template_id);
        $query->where('display', 1);
        $result = $query->get();
        if ($result->getNumRows() == 0) {
            return false;
        }
        return !$this->nameExists($user_id, $name);
    }

    public function nameExists($user_id, $name): bool
    {
        $query = $this->db->table('forms');
        $query->select();
        $query->where('user_id', $user_id);
        $query->where('name', $name);
        $result = $query->get();
        return $result->getNumRows() != 0;
    }

    /**
     * Adds the groups to the elements array, sorted by their position.
     * Even though groups are considered elements, they are stored in a separate table, which is why they need to be added to the elements array manually.
     */
    public function addGroups($template_id, $elements): array
    {
        $group_model = new GroupModel();
        $groups = $group_model->getGroups($template_id);
        foreach ($groups as &$group) {
            $group['element_type'] = 'group';
        }
        $elements = array_merge($elements, $groups);
        usort($elements, function ($a, $b) {
            return $a['position'] <=> $b['position'];
        });
        return $elements;
    }

    public function getTakenFormNamesForJS($user_id, $exclude = null): string
    {
        $result = $this->getForms($user_id);
        $general_model = new ElementBaseModel();
        return $general_model->getTakenNamesForJS($result, $exclude);
    }

    public function insertForm($name, $template_id, $user_id): int
    {
        $query = $this->db->table('forms');
        $query->insert([
            'name' => $name,
            'template_id' => $template_id,
            'user_id' => $user_id,
            'last_edit' => date('Y-m-d H:i:s')
        ]);
        return $this->db->insertID();
    }

    public function updateForm($form_id, $template_id, $element_id, $value, $custom_select): void
    {
        $type = $this->getElementType($template_id, $element_id);
        if ($type == '' || ($type == 'number' && !is_numeric($value) && $value != '')) {
            return;
        }
        if ($custom_select && !$this->customOptionAllowed($element_id)) {
            return;
        }
        $query = $this->db->table('entries');
        $query->select();
        $query->where('form_id', $form_id);
        $query->where('element_id', $element_id);
        $result = $query->get();
        if ($result->getNumRows() == 0) {
            $query->insert([
                'form_id' => $form_id,
                'element_id' => $element_id,
                'value' => $value,
                'custom_select' => $custom_select
            ]);
        } else {
            $query->where('form_id', $form_id);
            $query->where('element_id', $element_id);
            $query->update([
                'value' => $value,
                'custom_select' => $custom_select
            ]);
        }
        $this->updateFormTime($form_id);
    }

    public function customOptionAllowed($element_id): bool
    {
        $query = $this->db->table('selects');
        $query->select('allow_individual');
        $query->where('element_id', $element_id);
        $result = $query->get();
        return $result->getResultArray()[0]['allow_individual'] == 1;
    }

    public function updateFormTime($form_id): void
    {
        $query = $this->db->table('forms');
        $query->where('id', $form_id);
        $query->update([
            'last_edit' => date('Y-m-d H:i:s')
        ]);
    }

    public function getElementType($template_id, $element_id): string
    {
        $query = $this->db->table('elements');
        $query->select();
        $query->where('template_id', $template_id);
        $query->where('id', $element_id);
        $result = $query->get();
        if ($result->getNumRows() == 0) {
            return '';
        }
        return $result->getResultArray()[0]['element_type'];
    }

    public function editForm($form_id, $name, $user_id): void
    {
        $query = $this->db->table('forms');
        $query->where('id', $form_id);
        $query->where('user_id', $user_id);
        $query->update([
            'name' => $name,
            'last_edit' => date('Y-m-d H:i:s')
        ]);
    }

    public function deleteForm($form_id, $user_id): void
    {
        $query = $this->db->table('forms');
        $query->where('id', $form_id);
        $query->where('user_id', $user_id);
        $query->delete();
    }

    public function formIsReady($form_id, $template_id): bool
    {
        $query = $this->db->table('elements');
        $query->select('elements.id');
        $query->where('elements.template_id', $template_id);
        $query->where('elements.display', 1);
        $query->where('elements.required', 1);
        $required_elements = $query->get()->getResultArray();
        foreach ($required_elements as $element) {
            $entryQuery = $this->db->table('entries');
            $entryQuery->select('entries.value');
            $entryQuery->where('entries.form_id', $form_id);
            $entryQuery->where('entries.element_id', $element['id']);
            $entry = $entryQuery->get()->getRowArray();
            if (!$entry || empty($entry['value'])) {
                return false;
            }
        }
        return true;
    }

    /**
     * Returns the values of all elements of the specified type for the specified form.
     */
    public function getElementValues($form_id, $type): array
    {
        $query = $this->db->table('elements');
        $query->select('elements.name,elements.omit, entries.value,elements.id,entries.custom_select');
        $query->where('elements.display', 1);
        $query->where('elements.element_type', $type);
        $query->join('entries', 'entries.element_id = elements.id ', 'left');
        $query->where('entries.form_id', $form_id);
        return $query->get()->getResultArray();
    }

    public function getVariableInputType($variable_id): string
    {
        $query = $this->db->table('variables');
        $query->select('input_types.type');
        $query->join('input_types', 'input_types.id = variables.input_type_id');
        $query->where('variables.element_id', $variable_id);
        $result = $query->get();
        return $result->getResultArray()[0]['type'];
    }

    public function getPercentages($element_id): array
    {
        $query = $this->db->table('percentages');
        $query->select('value');
        $query->where('number_id', $element_id);
        $result = $query->get();
        return $result->getResultArray();
    }
}