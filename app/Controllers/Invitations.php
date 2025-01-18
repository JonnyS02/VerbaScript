<?php

namespace App\Controllers;

use App\Controllers\Helper\EmailSender;
use CodeIgniter\HTTP\RedirectResponse;

class Invitations extends BaseController
{
    public function index(): string|RedirectResponse
    {
        if (!$this->accessGranted(3)) {
            return redirect()->to('login');
        }
        $data['items'] = $this->InvitationsModel->getInvitations(session()->get('client_id'));
        $data['item_name_sg'] = 'Einladung';
        $data['item_name_pl'] = 'Einladungen';
        $data['href'] = "Invitation";
        $data['headline'] = 'Einladungen von ' . $this->ProfileModel->getClientName(session()->get('client_id'));
        $data['table_body'] = APPPATH . "/Views/invitations/invitations_loop.php";
        $data['columns'] = [
            ['title' => 'Name des zukünftigen Nutzers.', 'name' => 'Name'],
            ['title' => 'E-Mail', 'name' => 'E-Mail'],
            ['title' => 'Rolle', 'name' => 'Rolle'],
            ['title' => 'Löschen', 'name' => 'Löschen']
        ];
        $data['filter'] = (new Users())->setupFilter();
        return $this->viewMod('Einladungen', "partials/table", $data);
    }

    public function deleteInvitation(): RedirectResponse
    {
        if (!$this->accessGranted(3)) {
            return redirect()->to('login');
        }
        $this->InvitationsModel->deleteInvitation($this->request->getPost('id'), session()->get('client_id'));
        return redirect()->to('invitations');
    }

    public function getInvitation(): string|RedirectResponse
    {
        if (!$this->accessGranted(3)) {
            return redirect()->to('login');
        }
        $data['disable_role_select'] = true;
        $data['roles'] = $this->UsersModel->getRoles();
        $data['object'] = $this->InvitationsModel->getInvitation($this->request->getPost('id'), session()->get('client_id'));
        $data['headline'] = 'Einladung an ' . $data['object']['name'];
        $data['body'] = "invitations/invitation.php";
        return $this->viewMod('Nutzer', 'partials/card', $data);
    }

    public function insertInvitation($invitation = null): string|RedirectResponse
    {
        if (!$this->accessGranted(3)) {
            return redirect()->to('login');
        }
        $data['object'] = $invitation;
        $data['aboard_link'] = 'invitations';
        $data['roles'] = $this->UsersModel->getRoles();
        $data['headline'] = 'Einladung hinzufügen';
        $data['js_arrays'] = $this->UsersModel->getTakenUserNamesForJS(session()->get('client_id'));
        $data['body'] = "invitations/invitation.php";
        return $this->viewMod('Nutzer', 'partials/card', $data);
    }

    public function insertInvitationSubmit(): string|RedirectResponse
    {
        if (!$this->accessGranted(3)) {
            return redirect()->to('login');
        }
        $invitation['name'] = $this->request->getPost('name');
        $invitation['email'] = $this->request->getPost('email');
        $invitation['role_id'] = $this->request->getPost('role');
        $invitation['message'] = $this->request->getPost('message');
        $invitation['client_id'] = session()->get('client_id');
        $invitation['code'] = random_int(100000, 999999);
        if ($this->UsersModel->usernameIsValid($invitation['name'], session()->get('client_id'))) {
            if ((new  Users())->roleExists($invitation['role_id'])) {
                if (!$this->ProfileModel->emailTaken($invitation['email'])) {
                    if (filter_var($invitation['email'], FILTER_VALIDATE_EMAIL)) {
                        $this->InvitationsModel->insertInvitation($invitation);
                        (new EmailSender())->sendEmail($invitation, 'invitation');
                        return redirect()->to('invitations');
                    }
                }
                $invitation['email-error'] = 'E-Mail bereits vergeben oder ungültig.';
                return $this->insertInvitation($invitation);
            }
        }
        return redirect()->to('invitations');
    }

    public function insertUser($data = null): string|RedirectResponse
    {
        if (!$data) {
            $code = $this->request->getGet('code');
            $email = $this->request->getGet('email');
            $result = $this->InvitationsModel->invitationIsValid($code, $email);
            if ($result) {
                session()->set('invitation_id', $result['invitation_id']);
                session()->set('client_id', $result['client_id']);
            } else {
                $data['message'] = 'Die von Ihnen genutzte Einladung ist fehlerhaft oder veraltet.';
                return $this->viewMod('', 'general_error',$data);
            }
        }
        $data['remove_delete_profile_button'] = true;
        $data['change_password'] = true;
        $data['object'] = $this->InvitationsModel->getInvitation(session()->get('invitation_id'), session()->get('client_id'));
        $data['headline'] = 'Willkommen bei VerbaScript <br> - ' . $this->ProfileModel->getClientName(session()->get('client_id'));
        $data['body'] = "invitations/onboarding.php";
        return $this->viewMod('Nutzer', 'partials/card', $data);
    }

    public function insertUserSubmit(): string|RedirectResponse
    {
        $password = $this->request->getPost('new_password');
        $repeat_password = $this->request->getPost('repeat_password');
        if ($password === $repeat_password && !empty($password)) {
            $invitation = $this->InvitationsModel->getInvitation(session()->get('invitation_id'), session()->get('client_id'));
            $data['name'] = $invitation['name'];
            $data['email'] = $invitation['email'];
            $data['role_id'] = $invitation['role_id'];
            $data['password'] = password_hash($password, PASSWORD_DEFAULT);
            $data['client_id'] = session()->get('client_id');
            $data['login_attempts'] = 3;
            $this->UsersModel->insertUser($data);
            $this->InvitationsModel->deleteInvitation(session()->get('invitation_id'), session()->get('client_id'));
            return $this->viewMod('', 'invitations/invitation_success');
        } else {
            $data['new_password_status'] = 'Passwörter stimmen nicht überein.';
            return $this->insertUser($data);
        }
    }
}