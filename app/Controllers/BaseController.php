<?php

namespace App\Controllers;

use App\Models\TemplateModel;
use App\Models\ElementModels\ElementBaseModel;
use App\Models\ElementModels\GroupModel;
use App\Models\ElementModels\NumberModel;
use App\Models\ElementModels\SelectModel;
use App\Models\ElementModels\VariableModel;
use App\Models\FormModel;
use App\Models\InvitationsModel;
use App\Models\LoginModel;
use App\Models\OrderModel;
use App\Models\ProfileModel;
use App\Models\UsersModel;
use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Services;
use Psr\Log\LoggerInterface;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{
    /**
     * Instance of the main Request object.
     *
     * @var CLIRequest|IncomingRequest
     */
    protected $request;

    /**
     * An array of helpers to be loaded automatically upon
     * class instantiation. These helpers will be available
     * to all other controllers that extend BaseController.
     *
     * @var list<string>
     */
    protected $helpers = [];

    /**
     * Be sure to declare properties for any property fetch you initialized.
     * The creation of dynamic property is deprecated in PHP 8.2.
     */
    // protected $session;

    /**
     * @return void
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger): void
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        // Preload any models, libraries, etc, here.

        $this->session = Services::session();
        $this->validation = Services::validation();

        $this->GroupModel = new GroupModel();
        $this->NumberModel = new NumberModel();
        $this->SelectModel = new SelectModel();
        $this->VariableModel = new VariableModel();

        $this->TemplateModel = new TemplateModel();
        $this->ElementBaseModel = new ElementBaseModel();
        $this->LoginModel = new LoginModel();
        $this->OrderModel = new OrderModel();
        $this->ProfileModel = new ProfileModel();
        $this->UsersModel = new UsersModel();
        $this->InvitationsModel = new InvitationsModel();
        $this->FormModel = new FormModel();
    }

    public function viewMod($chosen_menu_item, $view, $data = null): string|RedirectResponse
    {
        $data['session_username'] = session()->get('username');
        $data['session_role'] = session()->get('role_id');
        $data['chosen_menu_item'] = $chosen_menu_item;
        $data['view'] = $view . '.php';
        return view('base_view', $data);
    }

    public function accessGranted($authorization_level): bool
    {
        if (!session()->get('login')) {
            return false;
        }
        if($authorization_level && session()->get('role_id') < $authorization_level){
            return false;
        }
        return true;
    }
}
