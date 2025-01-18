<?php

namespace App\Controllers;

use CodeIgniter\HTTP\RedirectResponse;

class Order extends BaseController
{
    public function index(): string|RedirectResponse
    {
        if (!$this->accessGranted(2)) {
            return redirect()->to('login');
        }

        $data['select_templates'] = $this->TemplateModel->getTemplates(session()->get('client_id'));
        $data['template_name'] = $this->TemplateModel->getTemplateName(session()->get('template_id'));
        $data['use_template_select'] = true;

        $data['is_order_table'] = true;
        $data['template_id'] = session()->get('template_id');
        $data['items'] = $this->OrderModel->getOrder(session()->get('template_id'));
        $data['item_name_sg'] = 'Element';
        $data['item_name_pl'] = 'Elemente';
        $data['href'] = "Order";
        $template_name = $this->TemplateModel->getTemplateName(session()->get('template_id'));
        if ($template_name == "#0") {
            $data['headline'] = 'Anordnung nicht verfügbar, keine Vorlage ausgewählt';
        } else {
            $data['headline'] = 'Anordnung von ' . $template_name;
        }
        $data['table_body'] = APPPATH . "/Views/order/order_loop.php";
        $data['columns'] = [
            ['title' => 'Element', 'name' => 'Element'],
            ['title' => 'Gruppe', 'name' => 'Gruppe'],
            ['title' => 'Legt die Länge des Feldes im Formular fest. In eine Zeile passen Felder mit einer Länge von insgesamt 12.', 'name' => 'Länge'],
            ['title' => 'Das darauffolgende Element soll in der nächsten Zeile stehen.', 'name' => 'Umbruch'],
            ['title' => 'Legt fest, von wo bis wo eine lokale Spracheingabe möglich ist.', 'name' => 'Spracheingabe'],
            ['title' => 'Ob das Element im Formular angezeigt werden soll.', 'name' => 'Anzeigen'],
        ];
        $data['filter'] = $this->setupFilter();
        return $this->viewMod('Anordnung', "order/order", $data);
    }

    private function setupFilter(): array
    {
        $filter['column'] = 1;
        $filter['default'] = array('name' => 'Gruppe');
        $filter['items'] = $this->GroupModel->getGroups(session()->get('template_id'));
        return $filter;
    }

    public function updateOrder(): string|RedirectResponse
    {
        $position = 0;
        $elements = [];
        $post_data = $this->request->getPost();
        unset($post_data['template']);
        $start_ai = true;
        foreach ($post_data as $post_object) {
            $position++;
            $display = isset($post_object['display']) ? 1 : 0;
            if (isset($post_object['isGroup'])) {
                $this->OrderModel->updateOderGroup($position,$display,$post_object['id'],session()->get('template_id'));
                continue;
            }
            $element['field_length'] = $this->isValidFieldLength($post_object['field_length']) ? $post_object['field_length'] : 12;
            $element['separate_after'] = isset($post_object['separate']) ? 1 : 0;
            $element['display'] = $display;
            $element['position'] = $position;
            $element['id'] = $post_object['id'];
            if(isset($post_object['ai_marker'])) { // ai_marker is set. 1 represents the start of a new local language input and -1 the end of it.
                $element['ai_marker'] = $start_ai ? 1 : -1;
                $start_ai = !$start_ai;
            } else {
                $element['ai_marker'] = 0;
            }
            $elements[] = $element;
        }
        $this->OrderModel->updateOrder($elements, session()->get('template_id'));
        if ($this->request->getGet('ajax') == 'true') {
            return "success";
        }
        return redirect()->to('order');
    }

    public function isValidFieldLength($field_length): bool
    {
        return is_numeric($field_length) && $field_length >= 1 && $field_length <= 12;
    }

    public function formPreview(): string|RedirectResponse
    {
        if (!$this->accessGranted(2)) {
            return redirect()->to('login');
        }
        $template_id = session()->get('template_id');
        $data['form']['elements'] = $this->FormModel->getFormElements($template_id);
        $data['body'] = 'forms/form_preview.php';
        $data['headline'] = '<span class="fs-6 position-absolute top-0 start-1 translate-middle badge rounded-pill bg-danger" style="text-shadow: 0 0 0">LIVE</span>
                                Vorschau-Formular von ' . $this->TemplateModel->getTemplateName($template_id);
        $data['item_name_sg'] = 'Formular';
        $data['is_form_card'] = true;
        $data['href'] = "Form";
        return $this->viewMod('Anordnung', 'partials/card', $data);
    }
}
