<?php

namespace App\Controllers\Helper;

use App\Controllers\BaseController;
use App\Controllers\Forms;
use App\Models\FormModel;
use OpenAI;

class AI_API extends BaseController
{

    public function getFormContent($ai_section): array
    {
        $form_model = new FormModel();
        $form = $form_model->getActualForm(session()->get('form_id'), session()->get('client_id'), session()->get('user_id'), $ai_section);
        $extracted_input = [];
        $form['elements'] = (new Forms)->removeGroups($form['elements']);
        foreach ($form['elements'] as $element) {
            $type = "text";
            if ($element['element_type'] === 'select') {
                $type = "option";
            } else if ($element['element_type'] === 'number') {
                $type = "number";
            }
            $entry = [
                'attribute' => $element['name'],
                'type' => $type,
                'value' => $element['value']
            ];
            if (!empty($element['ai_info'])) {
                $entry['additional_info'] = $element['ai_info'];
            }
            if ($element['element_type'] === 'select') {
                $options = [];
                foreach ($element['options'] as $option) {
                    $options[] = [
                        'name' => $option['name']
                    ];
                }
                $entry['options'] = $options;
            }
            $extracted_input['elements'][] = $entry;
        }
        $extracted_input['template_name'] = $form['template'];
        return $extracted_input;
    }

    function filterResponse($response): array
    {
        $data = json_decode($response, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return [];
        }
        $formatted_response = [];
        foreach ($data as $item) {
            if (!empty($item['attribute']) && !empty($item['value'])) {
                $formatted_response[] = [
                    'attribute' => $item['attribute'],
                    'value' => $item['value']
                ];
            }
        }
        return $formatted_response;
    }


    public function getResponse($user_input, $ai_section): array
    {
        /*$user_input =
            "Ich möchte eine Rechnung für Frau Anna Müller erstellen.
            Sie bezahlt mit PayPal und die Kosten betragen 150 Euro.
            Die Rechnung sollte an folgende Adresse geschickt werden:
            Hauptstraße 10 in Berlin, 10115.
            Ihre E-Mail-Adresse ist anna.mueller@example.com und sie hat die Telefonnummer 030-1234567.";
        */
        $extracted_input = $this->getFormContent($ai_section);
        $system_prompt = "
            You are an AI assistant whose task is to format German user input. 
            Ultimately, your formatted data should be used to fill out templates automatically. 
            The name of the form is: " . $extracted_input['template_name'] . ".
            There are the following fields to fill out: " . json_encode($extracted_input['elements'], JSON_PRETTY_PRINT) . "
            Look at your previous answer to keep the formatting. If no options are given, you can freely choose a value, else choose one of the options.
            But dont give the number of the option, just the value, even though you received the number.
            You should change field values that have already been filled out if new information is given.
            If the user input is short, but rational for some of the fields, you can ignore the other fields and fill out the ones that are most likely.
            If you have insufficient information from the user input for a field, ignore this field.";
        $assistant_prompt = '[
                                {
                                    "attribute": "Geschlecht",
                                    "value": "männlich",
                                },
                                {
                                    "attribute": "Vorname",
                                    "value": "John",
                                },
                                {
                                    "attribute": "Bezahlmethode",
                                    "value": "Bar",
                                }
                            ]';

        $api_key = getenv('OPEN_AI_API_KEY');
        $client = OpenAI::client($api_key);
        $result = $client->chat()->create([
            'model' => 'gpt-4o-mini-2024-07-18',
            'messages' => [
                ['role' => 'system', 'content' => $system_prompt],
                ['role' => 'assistant', 'content' => $assistant_prompt],
                ['role' => 'user', 'content' => $user_input],
            ],
        ]);
        return $this->filterResponse($result->choices[0]->message->content);
    }
}