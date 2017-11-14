<?php

use Phalcon\Flash;
use Phalcon\Session;
use Phalcon\Mvc\View;
use Phalcon\Paginator\Adapter\Model as Paginator;

class EmployeesController extends ControllerBase
{
    public function initialize()
    {
        $this->tag->setTitle('Служители');
        parent::initialize();
    }

    /**
    * Search employees based on current criteria
    */
    public function indexAction()
    {
        $numberPage = 1;
        $numberPage = $this->request->getQuery("page", "int");

        $parameters = array();

        $parameters['order'] = 'type ASC'; //TODO get order dinamically
        $employees = Users::find($parameters);

        if (count($employees) == 0) {
            $this->flash->notice("Няма намерени служители по зададените критерии");

            return $this->dispatcher->forward(
                [
                    "controller" => "employee",
                    "action"     => "index"
                ]
            );
        }

        $paginator = new Paginator(array(
            "data"  => $employees,
            "limit" => 10,
            "page"  => $numberPage
        ));

        $this->view->users = $employees;
        $this->view->userTypes = Users::getUserTypes();
        $this->view->page = $paginator->getPaginate();
    }

    /**
     * Creates a new employee (if the user has rights)
     */
    public function createAction() {
        $form = new EmployeesForm;

        if ($this->request->isPost()) {
            $first_name = $this->request->getPost('first_name', array('string', 'striptags'));
            $last_name = $this->request->getPost('last_name', array('string', 'striptags'));
            $email = $this->request->getPost('email', 'email');
            $password = $this->request->getPost('password');
            $type = $this->request->getPost('type');
            $active = $this->request->getPost('active');

            $auth = $this->session->get('auth');
            $loggedUser = Users::findFirst($auth['id']);

            $user = new Users();
            $user->first_name = $first_name;
            $user->last_name = $last_name;
            $user->number = $loggedUser->number;
            $user->password = sha1($password);
            $user->email = $email;
            $user->type = $type;
            $user->active = $active = isset($active) ? 1 : 0;
            $user->created_at = new Phalcon\Db\RawValue('now()');
            $user->created_by = $auth['id'];
            
            if ($user->save() == false) {
                foreach ($user->getMessages() as $message) {
                    $this->flash->error((string) $message);
                }
            } else {
                $this->flash->success('Служителят беше добавен успешно!');
                $this->view->userId = $user->id; // TODO get record position
                
                return $this->dispatcher->forward(
                    [
                        "controller" => "employees",
                        "action"     => "index",
                    ]
                );
            }
        }

        $this->view->form = $form;
    }

    public function viewAction() 
    {
        $this->view->setRenderLevel(View::LEVEL_NO_RENDER);

        $userId = $this->request->getPost('userId');

        $user = Users::findFirstById($userId);

        if (!$user) {
            echo json_encode(['error' => 'Служителят не беше намерен!']);
            return;
        }

        echo json_encode($user);
    }

    /**
    * Edits an user based on its id
    */
    public function editAction($id)
    {
        if (!$this->request->isPost()) {
            $user = Users::findFirstById($id);
            if (!$user) {
                $this->flash->error("Служителят не беше намерен!");

                return $this->dispatcher->forward(
                    [
                        "controller" => "employees",
                        "action"     => "index",
                    ]
                );
            }

            $this->view->form = new EmployeesForm($user, array('edit' => true));
        }
    }

    /**
    * Saves current user in screen
    *
    * @param string $id
    */
    public function saveAction()
    {
        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(
                [
                    "controller" => "employees",
                    "action"     => "index",
                ]
            );
        }

        $id = $this->request->getPost("id", "int");

        $user = Users::findFirstById($id);
        if (!$user) {
            $this->flash->error("Служителят не съществува!");

            return $this->dispatcher->forward(
                [
                    "controller" => "employees",
                    "action"     => "index",
                ]
            );
        }

        $form = new EmployeesForm;
        $this->view->form = $form;

        $data = $this->request->getPost();
        $data['active'] = isset($data['active']) ? 1 : 0;

        if (!$form->isValid($data, $user)) {
            foreach ($form->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(
                [
                    "controller" => "employees",
                    "action"     => "edit",
                    "params"     => [$id]
                ]
            );
        }

        if ($user->save() == false) {
            foreach ($user->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(
                [
                    "controller" => "employees",
                    "action"     => "edit",
                    "params"     => [$id]
                ]
            );
        }

        $form->clear();

        $this->flash->success("Информацията беше редактирана успешно!");

        return $this->dispatcher->forward(
            [
                "controller" => "employees",
                "action"     => "index",
            ]
        );
    }

     /**
     * Deletes a user
     *
     * @param string $id
     */
    public function deleteAction($id)
    {
        $user = Users::findFirstById($id);
        if (!$user) {
            $this->flash->error("Служителят не беше намерен!");

            return $this->dispatcher->forward(
                [
                    "controller" => "employees",
                    "action"     => "index",
                ]
            );
        }

        if (!$user->delete()) {
            foreach ($user->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(
                [
                    "controller" => "employees",
                    "action"     => "index",
                ]
            );
        }

        $this->flash->success("Служителят беше изтрит успешно!");

        return $this->dispatcher->forward(
            [
                "controller" => "employees",
                "action"     => "index",
            ]
        );
    }

    /**
     * Edit the active user profile
     *
     */
    public function profileAction()
    {
        if (!$this->request->isPost()) {
            $this->tag->setDefault('name', $user->name);
            $this->tag->setDefault('email', $user->email);
        } else {

            $name = $this->request->getPost('name', array('string', 'striptags'));
            $email = $this->request->getPost('email', 'email');

            $user->name = $name;
            $user->email = $email;
            if ($user->save() == false) {
                foreach ($user->getMessages() as $message) {
                    $this->flash->error((string) $message);
                }
            } else {
                $this->flash->success('Your profile information was updated successfully');
            }
        }
    }
}
