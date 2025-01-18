<?php

namespace App\Controllers;

use CodeIgniter\HTTP\DownloadResponse;
use CodeIgniter\HTTP\RedirectResponse;

class Templates extends BaseController
{
    const FILEPATH = WRITEPATH . 'uploads/DOCX_Files/';

    public function index(): string|RedirectResponse
    {
        if (!$this->accessGranted(2)) {
            return redirect()->to('login');
        }
        if (session()->get('template_id') == 0) {
            $this->setNewTemplate();
        }
        $data['additional_delete_text'] = 'Alle Daten der Vorlage werden gelöscht. Darunter fallen auch alle verknüpften Elemente. Dieser Schritt ist unwiderruflich.';
        $data['template'] = $this->TemplateModel->getTemplateName(session()->get('template_id'));
        $data['items'] = $this->TemplateModel->getTemplates(session()->get('client_id'));
        $data['item_name_sg'] = 'Vorlage';
        $data['item_name_pl'] = 'Vorlagen';
        $data['href'] = "Template";
        $data['headline'] = 'Vorlagen von ' . $this->ProfileModel->getClientName(session()->get('client_id'));
        $data['table_body'] = APPPATH . "/Views/templates/templates_loop.php";
        $data['columns'] = [
            ['title' => 'Name', 'name' => 'Name'],
            ['title' => 'Herunterladen', 'name' => 'Herunterladen'],
            ['title' => 'Löschen', 'name' => 'Löschen']
        ];
        return $this->viewMod('Vorlagen', 'partials/table', $data);
    }

    public function setActiveTemplate(): bool
    {
        if (!$this->accessGranted(2)) {
            return false;
        }
        $template_id = $this->request->getPost('template');
        $template = $this->TemplateModel->getTemplate($template_id, session()->get('client_id'));
        if ($template != null) {
            session()->set('template_id', $template['id']);
            session()->set('template_name', $template['name']);
            return true;
        }
        return false;
    }

    function deleteFolder($path): bool
    {
        if (!is_dir($path)) {
            return false;
        }
        $files = scandir($path);
        foreach ($files as $file) {
            if ($file != '.' && $file != '..') {
                $full_path = $path . DIRECTORY_SEPARATOR . $file;
                if (is_dir($full_path)) {
                    $this->deleteFolder($full_path);
                } else {
                    unlink($full_path);
                }
            }
        }
        return rmdir($path);
    }

    public function deleteTemplate(): RedirectResponse
    {
        if (!$this->accessGranted(2)) {
            return redirect()->to('login');
        }
        $template = $this->TemplateModel->getTemplate($this->request->getPost('id'), session()->get('client_id'));
        $folder_path = self::FILEPATH . $template['client_id'] . '/' . $template['id'];
        $this->deleteFolder($folder_path);
        $this->TemplateModel->deleteTemplate($this->request->getPost('id'), session()->get('client_id'));
        $this->setNewTemplate();
        return redirect()->to('templates');
    }

    public function setNewTemplate(): void
    {
        $templates = $this->TemplateModel->getTemplates(session()->get('client_id'));
        if (count($templates) > 0) {
            session()->set('template_id', $templates[0]['id']);
        } else {
            session()->set('template_id', 0);
        }
    }

    public function insertTemplate(): string|RedirectResponse
    {
        if (!$this->accessGranted(2)) {
            return redirect()->to('login');
        }
        $data['js_arrays'] = $this->TemplateModel->getTakenTemplateNamesForJS(session()->get('client_id'));
        $data['aboard_link'] = 'templates';
        $data['headline'] = 'Vorlage hinzufügen';
        $data['body'] = 'templates/template.php';
        $data['form_link'] = 'insertTemplateSubmit';
        return $this->viewMod('Vorlagen', 'partials/card', $data);
    }

    public function getTemplateFile(): DownloadResponse|RedirectResponse|null
    {
        if (!$this->accessGranted(2)) {
            return redirect()->to('login');
        }
        $template = $this->TemplateModel->getTemplate($this->request->getPost('id'), session()->get('client_id'));
        $path = self::FILEPATH . $template['client_id'] . '/' . $template['id'] . '/' . $template['filename'];
        return $this->response->download($path, null);
    }

    public function insertTemplateSubmit(): string|RedirectResponse
    {
        if (!$this->accessGranted(2)) {
            return redirect()->to('login');
        }
        $file = $this->request->getFile('file');
        $filename = $file->getName();
        $name = $this->request->getPost('name');
        $display = $this->request->getPost('display') == 'on' ? 1 : 0;
        $global_ai = $this->request->getPost('global_ai') == 'on' ? 1 : 0;
        $result = $this->templateValidator($name, $file);
        if (!$result['status']) {
            $data['aboard_link'] = 'templates';
            $data['headline'] = 'Vorlage hinzufügen';
            $data['body'] = 'templates/template.php';
            $data['name_error'] = $result['name_error'];
            $data['object']['name'] = $name ?? null;
            $data['object']['global_ai'] = $global_ai;
            $data['object']['display'] = $display;
            $data['file_error'] = $result['file_error'];
            $data['js_arrays'] = $this->TemplateModel->getTakenTemplateNamesForJS(session()->get('client_id'));
            $data['form_link'] = 'insertTemplateSubmit';
            return $this->viewMod('Vorlagen', 'partials/card', $data);
        }
        $template = $this->TemplateModel->insertTemplate($name, session()->get('client_id'), $filename, $display, $global_ai);
        $this->moveFile($template, $filename, $file);
        if (session()->get('template_id') == 0) {
            $this->setNewTemplate();
        }
        return redirect()->to('templates');
    }

    public function editTemplate($name_error = null, $file_error = null, $display = null, $global_ai = null): string|RedirectResponse
    {
        if (!$this->accessGranted(2)) {
            return redirect()->to('login');
        }
        $template = $this->request->getPost('id');
        $data['object'] = $this->TemplateModel->getTemplate($template, session()->get('client_id'));
        session()->setFlashdata('template_name', $data['object']['name']);
        session()->set('template_name', $data['object']['name']);
        $data['aboard_link'] = 'templates';
        $data['headline'] = 'Vorlage bearbeiten';
        $data['body'] = 'templates/template.php';
        $data['js_arrays'] = $this->TemplateModel->getTakenTemplateNamesForJS(session()->get('client_id'), $data['object']['name']);
        $data['form_link'] = 'editTemplateSubmit';
        $data['name_error'] = $name_error;
        $data['file_error'] = $file_error;
        $data['object']['display'] = $display ?? $data['object']['display'];
        $data['object']['global_ai'] = $global_ai ?? $data['object']['global_ai'];
        return $this->viewMod('Vorlagen', 'partials/card', $data);
    }

    public function editTemplateSubmit(): string|RedirectResponse
    {
        if (!$this->accessGranted(2)) {
            return redirect()->to('login');
        }
        $file = $this->request->getFile('file');
        $name = $this->request->getPost('name');
        $template_id = $this->request->getPost('id');
        $result = $this->templateValidator($name, $file, true);
        $display = $this->request->getPost('display') == 'on' ? 1 : 0;
        $global_ai = $this->request->getPost('global_ai') == 'on' ? 1 : 0;
        if (!$result['status']) {
            return $this->editTemplate($result['name_error'], $result['file_error'], $display, $global_ai);
        }
        if ($file->isValid() && !isset($result['file_error'])) {
            $filename = $file->getName();
            $template = $this->TemplateModel->getTemplate($template_id, session()->get('client_id'));
            $folder_path = self::FILEPATH . $template['client_id'] . '/' . $template['id'];
            $this->deleteFolder($folder_path);
            $this->moveFile($template_id, $filename, $file);
            $this->TemplateModel->updateTemplate($template_id, session()->get('client_id'), $name, $filename, $display, $global_ai);
        } else {
            $this->TemplateModel->updateTemplate($template_id, session()->get('client_id'), $name, null, $display, $global_ai);
        }
        return redirect()->to('templates');
    }

    public function moveFile($template_id, $filename, $file): void
    {
        $folder_path = self::FILEPATH . session()->get('client_id') . '/' . $template_id;
        if (!is_dir($folder_path)) {
            mkdir($folder_path, 0777, true);
        }
        $file->move($folder_path, $filename);
    }

    public function templateValidator($name, $file, $edit = false): array
    {
        $result['status'] = true;
        $result['name_error'] = null;
        $result['file_error'] = null;
        if ($file->isValid()) {
            if ($file->getClientExtension() != 'docx') {
                $result['file_error'] = 'Die Datei muss vom Typ DOCX sein.';
                $result['status'] = false;
            }
            if ($file->getSize() > 30000000) {
                $result['file_error'] = 'Die Datei darf nicht größer als 30MB sein.';
                $result['status'] = false;
            }
        } else if (!$file->isValid() && !$edit) {
            $result['file_error'] = 'Die Datei konnte nicht hochgeladen werden.';
            $result['status'] = false;
        }
        if ($file->hasMoved()) {
            $result['file_error'] = 'Die Datei konnte nicht hochgeladen werden.';
            $result['status'] = false;
        }
        if ($this->TemplateModel->nameExists($name, session()->get('client_id')) && !$edit) {
            $result['name_error'] = 'Es existiert bereits eine Vorlage mit diesem Namen.';
            $result['status'] = false;
        }
        if ($this->TemplateModel->nameExists($name, session()->get('client_id')) && $name != session()->get('template_name')) {
            $result['status'] = false;
        }
        if ($name == '') {
            $result['name_error'] = 'Der Name darf nicht leer sein.';
            $result['status'] = false;
        }
        return $result;
    }
}
