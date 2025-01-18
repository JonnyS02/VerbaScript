<?php

namespace App\Controllers;

use App\Controllers\Helper\AI_API;
use App\Controllers\Helper\FileGenerator;
use CodeIgniter\HTTP\RedirectResponse;

class Forms extends BaseController
{
    public function index(): string|RedirectResponse
    {
        $profile = $this->ProfileModel->getProfile(null, session()->get('user_id'));
        if (!$this->accessGranted(1) || $profile == null) {
            return redirect()->to('login');
        }
        $data['is_forms_table'] = true;
        $data['items'] = $this->FormModel->getForms(session()->get('user_id'), true);
        $data['items'] = $this->germanDate($data['items']);
        $data['item_name_sg'] = 'Formular';
        $data['item_name_pl'] = 'Formulare';
        $data['href'] = "Form";
        $data['headline'] = 'Formulare von ' . $profile['name'];
        $data['table_body'] = APPPATH . "/Views/forms/forms_loop.php";
        $data['columns'] = [
            ['title' => 'Name', 'name' => 'Name'],
            ['title' => 'Vorlage', 'name' => 'Vorlage'],
            ['title' => 'Zeitpunkt der letzten Änderung.', 'name' => 'Stand'],
            ['title' => 'Weiteres', 'name' => 'Weiteres'],
        ];
        $data['js_arrays'] = $this->FormModel->getTakenFormNamesForJS(session()->get('user_id'));
        $data['filter'] = $this->setupFilter();
        if ($data['filter']['items'] == null) {
            $data['headline'] = 'Formulare nicht verfügbar, keine Vorlagen vorhanden';
            $data['disable_add'] = true;
        }
        return $this->viewMod('Formulare', "partials/table", $data);
    }

    private function setupFilter(): array
    {
        $filter['column'] = 1;
        $filter['default'] = array('name' => 'Vorlage');
        $filter['items'] = $this->TemplateModel->getTemplates(session()->get('client_id'), true);
        return $filter;
    }

    private function germanDate($array): array
    {
        foreach ($array as $key => $value) {
            $array[$key]['last_edit'] = date('d.m.Y H:i', strtotime($value['last_edit']));
            if (date('d.m.Y') == date('d.m.Y', strtotime($value['last_edit']))) {
                $array[$key]['last_edit'] = date('H:i', strtotime($value['last_edit']));
            }
        }
        return $array;
    }

    public function fillForm($form_id = null): string|RedirectResponse
    {
        if (!$this->accessGranted(1)) {
            return redirect()->to('login');
        }
        if ($form_id == null) {
            $form_id = $this->request->getPost('form_id');
        }
        $data['form'] = $this->FormModel->getActualForm($form_id, session()->get('client_id'), session()->get('user_id'),0);
        $data['item_name_sg'] = 'Formular';
        $data['is_form_card'] = true;
        $data['href'] = "Form";
        $data['headline'] = $data['form']['name'] . ' ausfüllen';
        $data['body'] = 'forms/actual_form.php';
        session()->set('form_id', $form_id);
        session()->set('form_template_id', $data['form']['template_id']);
        return $this->viewMod('Formulare', 'forms/form_holder', $data);
    }

    public function updateForm(): string|RedirectResponse
    {
        if (!$this->accessGranted(1)) {
            return redirect()->to('login');
        }
        $form_id = session()->get('form_id');
        $template_id = session()->get('form_template_id');
        foreach ($this->request->getPost()['elements'] as $element) {
            if (empty($element['value'])) {
                $element['value'] = "";
            }
            $custom = ($element['value'] == 'custom') && isset($element['custom-value']);
            if ($custom) {
                $element['value'] = $element['custom-value'];
            }
            $this->FormModel->updateForm($form_id, $template_id, $element['id'], $element['value'], $custom);
        }
        if ($this->request->getGet('ajax') == 'true') {
            return "success";
        }
        return $this->fillForm($form_id);
    }

    public function insertForm(): string|RedirectResponse
    {
        if (!$this->accessGranted(1)) {
            return redirect()->to('login');
        }
        $data['headline'] = 'Formular hinzufügen';
        $data['body'] = "forms/form.php";
        $data['form_link'] = 'insertFormSubmit';
        $data['aboard_link'] = 'forms';
        $data['js_arrays'] = $this->FormModel->getTakenFormNamesForJS(session()->get('user_id'));
        $data['templates'] = $this->TemplateModel->getTemplates(session()->get('client_id'), true);
        return $this->viewMod('Formulare', 'partials/card', $data);
    }

    public function insertFormSubmit(): string|RedirectResponse
    {
        if (!$this->accessGranted(1)) {
            return redirect()->to('login');
        }
        $post = $this->request->getPost();
        if ($this->FormModel->checkNameAndTemplate(session()->get('client_id'), session()->get('user_id'), $post['name'], $post['template'])) {
            $id = $this->FormModel->insertForm($post['name'], $post['template'], session()->get('user_id'));
            return $this->fillForm($id);
        }
        return redirect()->to('forms');
    }

    public function editFormSubmit(): RedirectResponse
    {
        if (!$this->accessGranted(1)) {
            return redirect()->to('login');
        }
        $name = $this->request->getPost('name');
        if ($name == '') {
            return redirect()->to('forms');
        }
        if (!$this->FormModel->nameExists(session()->get('user_id'), $this->request->getPost('name'))) {
            $this->FormModel->editForm($this->request->getPost('id'), $this->request->getPost('name'), session()->get('user_id'));
        }
        return redirect()->to('forms');
    }

    public function deleteForm(): RedirectResponse
    {
        if (!$this->accessGranted(1)) {
            return redirect()->to('login');
        }
        $this->FormModel->deleteForm($this->request->getPost('id'), session()->get('user_id'));
        return redirect()->to('forms');
    }

    public function generateDocument()
    {
        if (!$this->accessGranted(1)) {
            return redirect()->to('login');
        }
        if ($this->request->getPost('id') !== null) { //If generate request comes from the form-table.
            $form_id = $this->request->getPost('id');
            $form = $this->FormModel->getForm($form_id, session()->get('user_id'));
            if (empty($form)) {
                return redirect()->to('forms');
            } else {
                $template_id = $form['template_id'];
                session()->set('form_id', $form_id);
                session()->set('form_template_id', $template_id);
            }
        } else { //If generate request comes from the actual fill-form page.
            $form_id = session()->get('form_id');
            $template_id = session()->get('form_template_id');
        }
        if ($this->FormModel->formIsReady($form_id, $template_id)) {
            $docx_generator = new FileGenerator();
            $template = $this->TemplateModel->getTemplate($template_id, session()->get('client_id'));
            $template_filename = $template['filename'];
            $file_end = $this->request->getPost('task');
            $actual_file = $docx_generator->generateFile($template_filename, $file_end == '.pdf');
            $form_name = $this->FormModel->getForm($form_id, session()->get('user_id'))['name'];
            $this->response->download($actual_file, null)->setFileName($form_name . $file_end)->send();
            unlink($actual_file);
        } else {
            return $this->fillForm($form_id);
        }
    }

    public function AI_API(): bool|string
    {
        if (!$this->accessGranted(1)) {
            return "Access denied.";
        }
        $user_input = $this->request->getPost('user_input');
        $ai_section = $this->request->getPost('ai_section');
        $form_id = session()->get('form_id');
        $global_ai = $this->FormModel->getActualForm($form_id, session()->get('client_id'), session()->get('user_id'),0)['global_ai'];
        if (empty($user_input) || (!$global_ai && $ai_section == 0)) {
            sleep(1);
            return "Bad request";
        }
        $ai = new AI_API();
        return json_encode($ai->getResponse($user_input,$ai_section), true);
    }

    public function removeGroups($elements): array
    {
        $filtered_elements = [];
        foreach ($elements as $element) {
            if (in_array($element['element_type'], ['select', 'number', 'variable'])) {
                $filtered_elements[] = $element;
            }
        }
        return $filtered_elements;
    }
}
