<?php

namespace App\Controllers\Helper;

use App\Controllers\BaseController;
use App\Models\FormModel;
use ConvertApi\ConvertApi;
use PhpOffice\PhpWord\Settings;

require 'WriteGermanNumbers.php';

class FileGenerator extends BaseController
{

    public function generateFile($template_filename, $generate_pdf): string
    {
        $client_id = session()->get('client_id');
        $template_id = session()->get('form_template_id');
        $form_id = session()->get('form_id');

        $selects = $this->getSelectValues($form_id);
        $variables = $this->getVariableValues($form_id);
        $numbers = $this->getNumberValues($form_id);

        $converter = new WriteGermanNumbers();
        $written_numbers = [];
        foreach ($numbers as $number) {
            if (!empty($number['value']) && $number['value'] != "entfällt") {
                $written_numbers[] = [
                    'name' => $number['name'] . '-w',
                    'value' => $converter->convert($number['value'])
                ];
            }
        }
        foreach ($numbers as $key => $number) {
            $numbers[$key]['value'] = $this->numberFormater($number['value']);
        }

        $template_path = WRITEPATH . "uploads/DOCX_Files/$client_id/$template_id/";
        $template_file = $template_path . $template_filename;
        $docx_file = $template_path . rand(0, 9999999999999999) . ".docx";

        $template_processor = new TemplateProcessorMod($template_file);

        $values = array_merge($selects, $variables, $numbers, $written_numbers);
        foreach ($values as $value) {
            $this->setValueExtended($value['name'], $value['value'], $template_processor);
        }
        $this->setBlacklist($template_processor);

        $template_processor->saveAs($docx_file);
        if ($generate_pdf) { // Convert to pdf based on one of the two options.
            if (getenv('USE_API_FOR_PDF') == 'true') {
                return $this->convertToPdf_API($docx_file);
            } else {
                return $this->convertToPdf_LibreOffice($docx_file);
            }
        }
        return $docx_file;
    }

    function convertToPdf_LibreOffice($docx_file): array|string
    {
        $pdf_file = str_replace('.docx', '.pdf', $docx_file);
        $output_dir = dirname($pdf_file);
        $command = getenv('LIBREOFFICE_PATH') . " --headless --convert-to pdf --outdir " . escapeshellarg($output_dir) . " " . escapeshellarg($docx_file);
        exec($command);
        unlink($docx_file);
        return $pdf_file;
    }

    function convertToPdf_API($docx_file): array|string
    {
        $pdf_file = str_replace('.docx', '.pdf', $docx_file);
        $output_dir = dirname($pdf_file);
        ConvertApi::setApiCredentials(getenv('CONVERT_API_KEY'));
        $result = ConvertApi::convert('pdf', [
            'File' => $docx_file,
        ], 'docx'
        );
        unlink($docx_file);
        $result->saveFiles($output_dir);
        return $pdf_file;
    }


    function numberFormater($number): string
    {
        if (!is_numeric($number)) {
            return $number;
        }
        return number_format($number, 2, ',', '.');
    }

    /**
     * Can be used to set certain dynamic values in the document. That are out of the user's control.
     * In this case, the date is set in two different formats, if needed.
     */
    function setBlacklist($template_processor): void
    {
        $template_processor->setValue('Datum', date("d.m.Y"));
        $template_processor->setValue('Datum-en', date("d/m/Y"));
    }

    function setValueExtended($name, $value, $template_processor): void
    {
        if (!empty($value)) {
            $template_processor->setValue($name, $value);
            Settings::setOutputEscapingEnabled(false);
            $template_processor->setValue($name . '-l', '<w:t xml:space="preserve"> </w:t>'); //prints an empty space
            Settings::setOutputEscapingEnabled(true);
            $template_processor->setValue($name . '-b', "");
            $template_processor->setValue('/' . $name . '-b', "");
        } else {
            $template_processor->setValue($name, "");
            Settings::setOutputEscapingEnabled(false);
            $template_processor->setValue($name . '-l', '');
            Settings::setOutputEscapingEnabled(true);
            $template_processor->cloneBlock($name . '-b', 0, true, true);
        }
    }

    public function formatValues($entry): array
    {
        if (empty($entry['value'])) {
            $formated['value'] = $entry['omit'] == 1 ? 'entfällt' : '';
        } else {
            $formated['value'] = $entry['value'];
        }
        $formated['name'] = $entry['name'];
        return $formated;
    }

    public function getVariableValues($form_id): array
    {
        $form_model = new FormModel();
        $values = $form_model->getElementValues($form_id, 'variable');
        $values = $this->handleInputTypes($values);
        foreach ($values as &$value) {
            $value = $this->formatValues($value);
        }
        return $values;
    }

    /**
     * This function formats the different input types of the variables.
     * As of now, only date and datetime-local are supported.
     * But it can be easily extended to support more input types.
     * @param array $variables
     * @return array
     */
    public function handleInputTypes(array $variables): array
    {
        $form_model = new FormModel();
        foreach ($variables as &$variable) {
            $input_type = $form_model->getVariableInputType($variable['id']);
            if ($input_type == 'date') {
                $variable['value'] = date('d.m.Y', strtotime($variable['value']));
            } elseif ($input_type == 'datetime-local') {
                $variable['value'] = date('d.m.Y H:i', strtotime($variable['value']));
            }
        }
        return $variables;
    }

    public function getSelectValues($form_id): array
    {
        $form_model = new FormModel();
        $values = $form_model->getElementValues($form_id, 'select');
        foreach ($values as &$value) {
            if ($value['custom_select'] == 0) {
                if ($value['value'] != null) {
                    $value['value'] = $form_model->getSelectOptions($value['id'])[$value['value'] - 1]['content'];
                }
            }
            $value = $this->formatValues($value);
        }
        return $values;
    }

    public function getNumberValues($form_id): array
    {
        $form_model = new FormModel();
        $values = $form_model->getElementValues($form_id, 'number');
        $calculatedPercentages = [];
        foreach ($values as &$value) {
            if ($value['value'] != null) {
                $percentages = $form_model->getPercentages($value['id']);
                $index = 1;
                foreach ($percentages as $percentage) {
                    $calculatedPercentage['name'] = $value['name'] . '-p' . $index;
                    $calculatedPercentage['value'] = $value['value'] * $percentage['value'];
                    $calculatedPercentages[] = $calculatedPercentage;
                }
            }
            $value = $this->formatValues($value);
        }
        return array_merge($values, $calculatedPercentages);
    }
}