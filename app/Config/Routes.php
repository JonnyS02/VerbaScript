<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Login::index');
$routes->get('/login', 'Login::index');
$routes->post('/validateLogin', 'Login::validateLogin');
$routes->get('/resetPasswordTrigger', 'Login::resetPasswordTrigger');
$routes->get('/resetPassword', 'Login::resetPassword');
$routes->post('/resetPasswordSubmit', 'Login::resetPasswordSubmit');

$routes->get('/groups', 'Elements\Groups::index');
$routes->post('/editGroup', 'Elements\Groups::editGroup');
$routes->post('/deleteGroup', 'Elements\Groups::deleteGroup');

$routes->get('/variables', 'Elements\Variables::index');
$routes->post('/deleteVariable', 'Elements\Variables::deleteElement');
$routes->post('/editVariable', 'Elements\Variables::editElement');
$routes->post('/editVariableSubmit', 'Elements\Variables::editElementSubmit');
$routes->get('/insertVariable', 'Elements\Variables::insertElement');
$routes->post('/insertVariableSubmit', 'Elements\Variables::insertElementSubmit');

$routes->get('/selects', 'Elements\Selects::index');
$routes->post('/deleteSelect', 'Elements\Selects::deleteElement');
$routes->post('/editSelect', 'Elements\Selects::editElement');
$routes->post('/editSelectSubmit', 'Elements\Selects::editElementSubmit');
$routes->get('/insertSelect', 'Elements\Selects::insertElement');
$routes->post('/insertSelectSubmit', 'Elements\Selects::insertElementSubmit');

$routes->get('/numbers', 'Elements\Numbers::index');
$routes->post('/deleteNumber', 'Elements\Numbers::deleteElement');
$routes->post('/editNumber', 'Elements\Numbers::editElement');
$routes->post('/editNumberSubmit', 'Elements\Numbers::editElementSubmit');
$routes->get('/insertNumber', 'Elements\Numbers::insertElement');
$routes->post('/insertNumberSubmit', 'Elements\Numbers::insertElementSubmit');

$routes->get('/profile', 'Profile::index');
$routes->post('/editProfile', 'Profile::editProfile');

$routes->get('/templates', 'Templates::index');
$routes->post('/deleteTemplate', 'Templates::deleteTemplate');
$routes->post('/editTemplate', 'Templates::editTemplate');
$routes->post('/editTemplateSubmit', 'Templates::editTemplateSubmit');
$routes->get('/insertTemplate', 'Templates::insertTemplate');
$routes->post('/insertTemplateSubmit', 'Templates::insertTemplateSubmit');
$routes->post('/getTemplateFile', 'Templates::getTemplateFile');
$routes->post('/setActiveTemplate', 'Templates::setActiveTemplate');

$routes->get('/order', 'Order::index');
$routes->post('/updateOrder', 'Order::updateOrder');
$routes->get('/formPreview', 'Order::formPreview');

$routes->get('/users', 'Users::index');
$routes->post('/deleteUser', 'Users::deleteUser');
$routes->post('/editUser', 'Users::editUser');
$routes->post('/editUserSubmit', 'Users::editUserSubmit');

$routes->get('/invitations', 'Invitations::index');
$routes->get('/insertInvitation', 'Invitations::insertInvitation');
$routes->post('/insertInvitationSubmit', 'Invitations::insertInvitationSubmit');
$routes->post('/deleteInvitation', 'Invitations::deleteInvitation');
$routes->post('/getInvitation', 'Invitations::getInvitation');
$routes->get('/insertUser', 'Invitations::insertUser');
$routes->post('/insertUserSubmit', 'Invitations::insertUserSubmit');

$routes->get('/forms', 'Forms::index');
$routes->post('/editForm', 'Forms::editForm');
$routes->post('/editFormSubmit', 'Forms::editFormSubmit');
$routes->post('/deleteForm', 'Forms::deleteForm');
$routes->get('/insertForm', 'Forms::insertForm');
$routes->post('/insertFormSubmit', 'Forms::insertFormSubmit');
$routes->post('/fillForm', 'Forms::fillForm');
$routes->post('/updateForm', 'Forms::updateForm');
$routes->post('/generateDocument', 'Forms::generateDocument');

$routes->post('/AI_API', 'Forms::AI_API');

$routes->get('/debugger','Forms::debugger');

$routes->set404Override('App\Controllers\Error404::index');
