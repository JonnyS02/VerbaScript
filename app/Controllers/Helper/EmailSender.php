<?php

namespace App\Controllers\Helper;

use App\Controllers\BaseController;
use App\Models\ProfileModel;
use CodeIgniter\Config\Services;
use CodeIgniter\Email\Email;

class EmailSender extends BaseController
{

    public function sendEmail($content, $type): void
    {
        if(session()->get('client_id') !== null){
            $client_name = (new ProfileModel)->getClientName(session()->get('client_id'));
        } else {
            $client_name = 'VerbaScript';
        }
        $email = $this->setUpContent($content, $type,$client_name);
        $email->setMailType('html');
        $email->setFrom($client_name . '@' . getenv('DOMAIN'), $client_name);
        $email->setTo($content['email']);
        $email->send();
    }

    public function setUpContent($content, $type,$client_name): Email
    {
        $email = Services::email();
        if ($type == 'invitation') {
            $email->setSubject('Einladung zu VerbaScript von ' . $client_name);
            $content['headline'] = 'Willkommen bei VerbaScript!';
            $content['action'] = 'Einladung annehmen';
            $content['link'] = '/insertUser?email=' . $content['email'] . '&code=' . $content['code'];
        } else if ($type == 'reset_password') {
            $email->setSubject('Passwort zurücksetzen');
            $content['headline'] = 'Passwort zurücksetzen';
            $content['action'] = 'Passwort zurücksetzen';
            $content['link'] = '/resetPassword?email=' . $content['email'] . '&code=' . $content['code'];
        }
        $email->setMessage(view('email_template', $content));
        return $email;
    }
}