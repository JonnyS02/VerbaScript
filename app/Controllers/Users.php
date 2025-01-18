<?php

namespace App\Controllers;

use App\Models\UsersModel;
use CodeIgniter\HTTP\RedirectResponse;

class Users extends BaseController
{

    public function index(): string|RedirectResponse
    {
        if (!$this->accessGranted(3)) {
            return redirect()->to('login');
        }
        $data['items'] = $this->UsersModel->getUsers(session()->get('client_id'));
        $data['hide_add_button'] = true;
        foreach ($data['items'] as $key => $item) {
            if ($item['id'] == session()->get('user_id')) {
                $data['items'][$key]['self'] = true;
            }
        }
        $data['item_name_sg'] = 'Nutzer';
        $data['item_name_pl'] = 'Nutzer';
        $data['href'] = "User";
        $data['headline'] = 'Nutzer von ' . $this->ProfileModel->getClientName(session()->get('client_id'));
        $data['table_body'] = APPPATH . "/Views/users/users_loop.php";
        $data['columns'] = [
            ['title' => 'Name', 'name' => 'Name'],
            ['title' => 'E-Mail', 'name' => 'E-Mail'],
            ['title' => 'Rolle', 'name' => 'Rolle'],
            ['title' => 'Entfernen', 'name' => 'Entfernen']
        ];
        $data['filter'] = $this->setupFilter();
        return $this->viewMod('Nutzer', 'partials/table', $data);
    }

    public function setupFilter(): array
    {
        $filter['column'] = 2;
        $filter['default'] = array('name' => 'Rolle');
        $filter['items'] = (new UsersModel())->getRoles();
        return $filter;
    }

    public function deleteUser(): RedirectResponse
    {
        if (!$this->accessGranted(3)) {
            return redirect()->to('login');
        }
        $user_id = $this->request->getPost('id');
        $this->UsersModel->deleteUser($user_id, session()->get('client_id'));
        if ($user_id == session()->get('user_id')) {
            return redirect()->to('login');
        }
        return redirect()->to('users');
    }

    public function editUser(): string|RedirectResponse
    {
        if (!$this->accessGranted(3)) {
            return redirect()->to('login');
        }
        $data['object'] = $this->UsersModel->getUser($this->request->getPost('id'), session()->get('client_id'));
        $data['form_link'] = 'editUserSubmit';
        $data['js_arrays'] = $this->UsersModel->getTakenUserNamesForJS(session()->get('client_id'), $data['object']['name']);
        $data['headline'] = 'Nutzer bearbeiten';
        $data['body'] = 'users/user.php';
        $data['roles'] = $this->UsersModel->getRoles();
        $data['aboard_link'] =  'users';
        return $this->viewMod('Nutzer', 'partials/card', $data);
    }

    public function editUserSubmit(): RedirectResponse
    {
        if (!$this->accessGranted(3)) {
            return redirect()->to('login');
        }
        $name = $this->request->getPost('name');
        $role_id = $this->request->getPost('role');
        $user['id'] = $this->request->getPost('id');
        $user['client_id'] = session()->get('client_id');
        if ($this->UsersModel->usernameIsValid($name, session()->get('client_id'))) {
            $user['name'] = $name;
        }
        $role_exists = $this->roleExists($role_id);
        if ($role_exists) {
            $user['role_id'] = $role_id;
            $this->UsersModel->updateUser($user);
        }
        return redirect()->to('users');
    }

    public function roleExists($role_id): bool
    {
        $roles = (new UsersModel)->getRoles();
        $role_exists = false;
        foreach ($roles as $role) {
            if ($role['id'] == $role_id) {
                $role_exists = true;
                break;
            }
        }
        return $role_exists;
    }
}

