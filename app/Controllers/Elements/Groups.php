<?php

namespace App\Controllers\Elements;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\RedirectResponse;

class Groups extends BaseController
{

    public function index(): string|RedirectResponse
    {
        if (!$this->accessGranted(2)) {
            return redirect()->to('login');
        }

        $data['select_templates'] = $this->TemplateModel->getTemplates(session()->get('client_id'));
        $data['template_name'] = $this->TemplateModel->getTemplateName(session()->get('template_id'));
        $data['use_template_select'] = true;

        $data['group_table'] = true;
        $data['js_arrays'] = $this->ElementBaseModel->getTakenElementNamesForJS(session()->get('template_id'), null, 'groups');
        $data['items'] = $this->GroupModel->getGroups(session()->get('template_id'));
        $data['item_name_sg'] = 'Gruppe';
        $data['item_name_pl'] = 'Gruppen';
        $data['href'] = "Group";
        $templateName = $this->TemplateModel->getTemplateName(session()->get('template_id'));
        if ($templateName == "#0") {
            $data['headline'] = 'Gruppen nicht verfügbar, keine Vorlage ausgewählt';
            $data['disable_add'] = true;
        } else {
            $data['headline'] = 'Gruppen von ' . $templateName;
        }
        $data['table_body'] = APPPATH . "/Views/partials/element_parts/table_parts/element_loop.php";
        $data['columns'] = [
            ['title' => 'Name', 'name' => 'Name'],
            ['title' => 'Umbenennen', 'name' => 'Umbenennen'],
            ['title' => 'Löschen', 'name' => 'Löschen'],
        ];
        return $this->viewMod('Gruppen', 'partials/table', $data);
    }

    public function editGroup(): RedirectResponse
    {
        if (!$this->accessGranted(2)) {
            return redirect()->to('login');
        }
        if ($this->request->getPost('task') == "insert") {
            $this->GroupModel->insertGroup($this->request->getPost('name'), session()->get('template_id'));
        }
        if ($this->request->getPost('task') == "update") {
            $this->GroupModel->updateGroup($this->request->getPost('name'), $this->request->getPost('id'), session()->get('template_id'));
        }
        return redirect()->to('groups');
    }

    public function deleteGroup(): RedirectResponse
    {
        if (!$this->accessGranted(2)) {
            return redirect()->to('login');
        }
        $this->ElementBaseModel->deleteElement($this->request->getPost('id'), session()->get('template_id'), 'groups');
        return redirect()->to('groups');
    }
}
