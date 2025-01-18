<?php

namespace App\Controllers\Elements;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\RedirectResponse;

class ElementBaseController extends BaseController
{
    protected string $elementType;
    protected string $elementViewPath;
    protected string $redirectPath;
    protected string $elementNameSG;
    protected string $elementNamePL;

    public function __construct(string $element_type, string $element_name_sg, string $element_name_pl)
    {
        $this->elementType = $element_type;
        $this->elementViewPath = strtolower($element_type);
        $this->redirectPath = strtolower($element_type) . 's';
        $this->elementNameSG = $element_name_sg;
        $this->elementNamePL = $element_name_pl;
    }

    public function index(): string|RedirectResponse
    {
        if (!$this->accessGranted(2)) {
            return redirect()->to('login');
        }
        $data['select_templates'] = $this->TemplateModel->getTemplates(session()->get('client_id'));
        $data['template_name'] = $this->TemplateModel->getTemplateName(session()->get('template_id'));
        $data['use_template_select'] = true;
        $data['groups'] = $this->GroupModel->getGroupNames(session()->get('template_id'));
        $data['items'] = $this->ElementBaseModel->getElements(session()->get('template_id'), $this->elementType);
        $data['item_name_sg'] = $this->elementNameSG;
        $data['item_name_pl'] = $this->elementNamePL;
        $data['href'] = $this->elementType;
        $templateName = $this->TemplateModel->getTemplateName(session()->get('template_id'));
        if ($templateName == "#0") {
            $data['headline'] = $this->elementNamePL . ' nicht verfügbar, keine Vorlage ausgewählt';
            $data['disable_add'] = true;
        } else {
            $data['headline'] = $this->elementNamePL . ' von ' . $templateName;
        }
        $data['table_body'] = APPPATH . "/Views/partials/element_parts/table_parts/element_loop.php";
        $data['columns'] = [
            ['title' => 'Name', 'name' => 'Name'],
            ['title' => 'Gruppe', 'name' => 'Gruppe'],
            ['title' => 'Löschen', 'name' => 'Löschen'],
        ];
        $data['filter'] = $this->setupFilter();
        return $this->viewMod($this->elementNamePL, 'partials/table', $data);
    }

    private function setupFilter(): array
    {
        $filter['column'] = 1;
        $filter['default'] = array('name' => 'Gruppe');
        $filter['items'] = $this->GroupModel->getGroups(session()->get('template_id'));
        return $filter;
    }

    public function deleteElement(): RedirectResponse
    {
        if (!$this->accessGranted(2)) {
            return redirect()->to('login');
        }
        $this->ElementBaseModel->deleteElement($this->request->getPost('id'), session()->get('template_id'), 'elements');
        return redirect()->to($this->redirectPath);
    }

    public function editElement(): string|RedirectResponse
    {
        if (!$this->accessGranted(2)) {
            return redirect()->to('login');
        }
        $data['check_name_for_valid_ending'] = true;
        if ($this->elementType == 'Variable') {
            $data['types'] = $this->VariableModel->getInputTypes();
        }
        if ($this->request->getPost('to_order') == 'true') {
            $data['aboard_link'] = 'order';
            session()->setFlashdata('order_id', $this->request->getPost('id'));
        } else {
            $data['aboard_link'] = "{$this->redirectPath}";
        }
        $data['object'] = $this->{$this->elementType . "Model"}->{"get{$this->elementType}"}($this->request->getPost('id'), session()->get('template_id'));
        if (!isset($data['object']['name'])) {
            return redirect()->to($this->redirectPath);
        }
        $data['groups'] = $this->GroupModel->getGroups(session()->get('template_id'));
        $data['headline'] = "{$this->elementNameSG} bearbeiten";
        $data['body'] = "elements/{$this->elementViewPath}.php";
        $data['form_link'] = "edit{$this->elementType}Submit";
        $data['js_arrays'] = $this->ElementBaseModel->getTakenElementNamesForJS(session()->get('template_id'), $data['object']['name']);
        return $this->viewMod($this->elementNamePL, 'partials/card', $data);
    }

    public function editElementSubmit(): RedirectResponse
    {
        if (!$this->accessGranted(2)) {
            return redirect()->to('login');
        }
        $this->{$this->elementType . "Model"}->{"edit{$this->elementType}"}($this->request->getPost(), session()->get('template_id'));
        if (session()->get('order_id') != null) {
            session()->getFlashdata('order_id');
            return redirect()->to(base_url(index_page() . "/order"));
        }
        return redirect()->to($this->redirectPath);
    }

    public function insertElement(): string|RedirectResponse
    {
        if (!$this->accessGranted(2)) {
            return redirect()->to('login');
        }
        $data['check_name_for_valid_ending'] = true;
        if ($this->elementType == 'Variable') {
            $data['types'] = $this->VariableModel->getInputTypes();
        }
        $data['groups'] = $this->GroupModel->getGroups(session()->get('template_id'));
        $data['headline'] = "{$this->elementNameSG} hinzufügen";
        $data['body'] = "elements/{$this->elementViewPath}.php";
        $data['aboard_link'] = "{$this->redirectPath}";
        $data['form_link'] = "insert{$this->elementType}Submit";
        $data['js_arrays'] = $this->ElementBaseModel->getTakenElementNamesForJS(session()->get('template_id'), null);
        return $this->viewMod($this->elementNamePL, 'partials/card', $data);
    }

    public function insertElementSubmit(): RedirectResponse
    {
        if (!$this->accessGranted(2)) {
            return redirect()->to('login');
        }
        $this->{$this->elementType . "Model"}->{"insert{$this->elementType}"}($this->request->getPost(), session()->get('template_id'));
        return redirect()->to($this->redirectPath);
    }
}
