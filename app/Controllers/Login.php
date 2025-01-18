<?php

namespace App\Controllers;

use App\Controllers\Helper\EmailSender;
use CodeIgniter\HTTP\RedirectResponse;

class Login extends BaseController
{
    public function index(): string|RedirectResponse
    {
        session()->destroy();
        $data = [
            'headline' => 'Anmeldung',
            'body' => 'login/login.php',
            'is_login_form' => true
        ];
        return $this->viewMod('Anmeldung', 'partials/card', $data);
    }

    public function resetPasswordTrigger(): string|RedirectResponse
    {
        $email = $this->request->getVar('email');
        $code = $this->LoginModel->setAttemptsToResetCode($email);
        if (!$code) {
            $data['message'] = 'Das Password dieser E-Mail kann derzeit nicht zurückgesetzt werden.';
            return $this->viewMod('', 'general_error', $data);
        }
        session()->destroy();
        $content = [
            'email' => $email,
            'code' => $code,
            'message' => 'Sehr geehrter VerbaScript Nutzer, Ihr Passwort wurde drei mal falsch eingegeben. Klicken Sie auf den folgenden Link um Ihr Passwort zurückzusetzen.'
        ];
        (new EmailSender)->sendEmail($content, 'reset_password');
        return $this->viewMod('', 'login/reset_password_notification');
    }

    public function resetPassword($data = null): string|RedirectResponse
    {
        $email = $this->request->getVar('email');
        $code = $this->request->getVar('code');
        $login_attempts = $this->LoginModel->getLoginAttempts(null, $email);
        if ($login_attempts && $login_attempts == $code) {
            session()->destroy();
            session()->start();
            $user_id = $this->ProfileModel->getProfile($email, null)['id'];
            session()->set('reset_user_id', $user_id);
            $data['remove_delete_profile_button'] = true;
            $data['change_password'] = true;
            $data['headline'] = 'Passwort zurücksetzen';
            $data['body'] = 'login/reset_password_insert.php';
            return $this->viewMod('Nutzer', 'partials/card', $data);
        }
        $data['message'] = 'Die E-Mail zum zurücksetzen des Passworts ist fehlerhaft oder veraltet.';
        return $this->viewMod('', 'general_error', $data);
    }

    public function resetPasswordSubmit()
    {
        $password = $this->request->getPost('new_password');
        $repeat_password = $this->request->getPost('repeat_password');
        if (session()->get('reset_user_id') !== null) {
            if ($password === $repeat_password && !empty($password)) {
                $hash = password_hash($password, PASSWORD_DEFAULT);
                $this->ProfileModel->changeProfile(session()->get('reset_user_id'), 'password', $hash);
                $this->LoginModel->resetLoginAttempts(session()->get('reset_user_id'));
                session()->remove('reset_user_id');
                return $this->viewMod('', 'login/reset_password_success');
            }
        } else {
            $data['new_password_status'] = 'Passwörter stimmen nicht überein.';
            return $this->resetPassword($data);
        }
    }

    public function validateLogin(): string|RedirectResponse
    {
        $email = $this->request->getVar('email');
        $password = $this->request->getVar('password');
        $result = (new Profile())->validateUser($email, null, $password);
        $result['email'] = $email;
        $result['password'] = $password;
        $result['headline'] = 'Anmeldung';
        $result['body'] = 'login/login.php';
        $result['is_login_form'] = true;
        if (!$result['status']) {
            return $this->viewMod('Anmeldung', 'partials/card', $result);
        }
        session()->set('login', true);
        session()->set('user_id', $result['id']);
        session()->set('role_id', $result['role_id']);
        session()->set('username', $result['name']);
        session()->set('client_id', $result['client']);
        $template = $this->TemplateModel->getTemplates(session()->get('client_id'));
        if (count($template) > 0 && $result['role_id'] != 1) {
            session()->set('template_id', $template[0]['id']);
        } else {
            session()->set('template_id', 0);
        }
        return redirect()->to('forms');
    }
}
