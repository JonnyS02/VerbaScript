<?php

namespace App\Controllers;

use App\Models\LoginModel;
use App\Models\ProfileModel;
use CodeIgniter\HTTP\RedirectResponse;

class Profile extends BaseController
{
    public function index(): string|RedirectResponse
    {
        $profile = $this->getProfile();
        if (!$this->accessGranted(1) || $profile == null) {
            return redirect()->to('login');
        }
        return $this->viewMod('Profil', 'partials/card', $profile);
    }

    public function validateUser($email, $user_id, $password): array
    {
        $user = (new ProfileModel)->getProfile($email, $user_id);
        if(!$email){
            $email = $user['email'];
        }
        if (!$user) {
            return array('status' => false, 'email_error' => 'E-Mail-Adresse wurde nicht gefunden.');
        }
        $user_id = $user['id'];
        $login_attempts = $user['login_attempts'];
        $login_model = new LoginModel();
        if (!password_verify($password, $user['password']) || $login_attempts <= 0) {
            $login_model->decreaseLoginAttempts($user_id);
            $login_attempts = $login_attempts - 1;
            if ($login_attempts <= 0) {
                return array('status' => false, 'password_error' => 'Passwort wurde zu oft falsch eingegeben. <a href="resetPasswordTrigger?email=' . $email . '" >Passwort zurücksetzen</a>');
            } else if ($login_attempts == 1) {
                return array('status' => false, 'password_error' => 'Passwort ist falsch. Noch 1 Versuch übrig.');
            } else {
                return array('status' => false, 'password_error' => 'Passwort ist falsch. Noch ' . $login_attempts . ' Versuche übrig.');
            }
        }
        $login_model->resetLoginAttempts($user_id);
        return array(
            'status' => true,
            'id' => $user_id,
            'role_id' => $user['role_id'],
            'name' => $user['name'],
            'client' => $user['client_id']
        );
    }

    public function getProfile(): ?array
    {
        $profile = $this->ProfileModel->getProfile(null, session()->get('user_id'));
        if ($profile == null) {
            return null;
        }
        $profile['client'] = $this->ProfileModel->getClientName(session()->get('client_id'));
        $profile['headline'] = ' Profil von ' . $profile['name'];
        $profile['body'] = 'profile/profile.php';
        unset($profile['password']);
        return $profile;
    }

    public function editProfile(): string|RedirectResponse
    {
        if (!$this->accessGranted(1)) {
            return redirect()->to('login');
        }
        $result = $this->validateUser(null, session()->get('user_id'), $this->request->getPost('password'));
        $profile = $this->getProfile();
        $result = array_merge($result, $profile);
        if ($this->request->getPost('task') == 'edit_profile') {
            $result = $this->changeProfile($result);
        } else if (($this->request->getPost('task') == 'delete_profile') and $result['status']) {
            $this->UsersModel->deleteUser(session()->get('user_id'), session()->get('client_id'));
            session()->destroy();
            return redirect()->to('login');
        }
        return $this->viewMod('Profil', 'partials/card', $result);
    }

    public function changeProfile($result)
    {

        if ($this->request->getPost('change_password') == 'on') {
            $result = $this->changePassword($result);
        }
        if ($this->request->getPost('change_email') == 'on') {
            $result = $this->changeEmail($result);
        }
        return $result;
    }

    public function changeEmail($result)
    {
        $result['change_email'] = 'checked';
        $new_email = $this->request->getPost('email');
        $user = $this->ProfileModel->getProfile(null, session()->get('user_id'));
        $email = $user['email'];
        $id = $user['id'];
        if (!filter_var($new_email, FILTER_VALIDATE_EMAIL)) {
            $result['email_status'] = 'Bitte geben Sie eine gültige E-Mail-Adresse ein.';
        }
        if ($this->ProfileModel->emailTaken($new_email)) {
            $result['email_status'] = 'Die E-Mail-Adresse ist bereits vergeben.';
        }
        if ($new_email == $email) {
            $result['email_status'] = 'Bitte geben Sie eine neue E-Mail-Adresse ein.';
        }
        $result['new_email'] = $new_email;
        if (!isset($result['email_status']) && $result['status']) {
            unset($result['new_email']);
            unset($result['change_email']);
            $this->ProfileModel->changeProfile($id, 'email', $new_email);
            $result['success_email'] = 'Die E-Mail-Adresse wurde geändert.';
            $result['email'] = $new_email;
        }
        return $result;
    }

    public function changePassword($result)
    {
        $result['change_password'] = 'checked';
        $new_password = $this->request->getPost('new_password');
        $repeat_password = $this->request->getPost('repeat_password');
        if ($new_password == '') {
            $result['new_password_status'] = 'Bitte geben Sie ein neues Passwort ein.';
        }
        if ($repeat_password == '') {
            $result['repeat_password_status'] = 'Bitte wiederholen Sie das neue Passwort.';
        }
        if ($new_password != $repeat_password) {
            $result['repeat_password_status'] = 'Die Passwörter stimmen nicht überein.';
        }
        if (!isset($result['new_password_status']) && !isset($result['repeat_password_status'])) {
            if ($new_password == $repeat_password && $result['status']) {
                $hash = password_hash($new_password, PASSWORD_DEFAULT);
                $this->ProfileModel->changeProfile(session()->get('user_id'), 'password', $hash);
                $result['success_password'] = 'Das Passwort wurde geändert.';
                unset($result['change_password']);
            }
        }
        return $result;
    }
}
