<?php

use Phalcon\Flash;
use Phalcon\Session;
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
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, "Users", $this->request->getPost());
            $this->persistent->searchParams = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = array();
        if ($this->persistent->searchParams) {
            $parameters = $this->persistent->searchParams;
        }

        $parameters['order'] = 'first_name ASC'; //TODO get order dinamically
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
            $user->active = $active;
            $user->created_at = new Phalcon\Db\RawValue('now()');
            $user->created_by = $auth['id'];
            
            if ($user->save() == false) {
                foreach ($user->getMessages() as $message) {
                    $this->flash->error((string) $message);
                }
            } else {
                $this->flash->success('Your profile information was updated successfully');
                
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
