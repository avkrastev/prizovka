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
        $order = $this->request->get('order', 'string', 'first_name');
        $direction = $this->request->get('direction', 'string', 'asc');

        $parameters['order'] = $order.' '.$direction;
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
        $this->view->order = [$order => $direction == 'asc' ? 'desc' : 'asc'];
        $this->view->userTypes = Users::getUserTypes();
        $this->view->page = $paginator->getPaginate();
    }

    /**
     * Creates a new employee (if the user has rights)
     */
    public function createAction() {
        $form = new EmployeesForm;

        if ($this->request->isPost()) {
            $data['first_name'] = $this->request->getPost('first_name', array('string', 'striptags'));
            $data['last_name'] = $this->request->getPost('last_name', array('string', 'striptags'));
            $data['email'] = $this->request->getPost('email', 'email');
            $data['password'] = $this->request->getPost('password');
            $data['type'] = $this->request->getPost('type');
            $data['active'] = $this->request->getPost('active');

            $auth = $this->session->get('auth');

            $user = new Users();
            $user->org = $auth['org'];
            $user->first_name = $data['first_name'];
            $user->last_name = $data['last_name'];
            $user->password = password_hash($data['password'], PASSWORD_DEFAULT);
            $user->email = $data['email'];
            $user->type = $data['type'];
            $user->active = $active = isset($data['active']) ? 1 : 0;
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

        $this->serviceFields($user);

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
            $user->password = '';
            $this->serviceFields($user, true);

            $this->view->form = new EmployeesForm($user, array('edit' => true));
            $this->view->user = $user;
        }
    }

    private function serviceFields(&$user, $edit = false) 
    {
        $user->type = $edit == false ? Users::getUserTypes()[$user->type] : $user->type;
        if (!$edit) $user->active = $user->active ? 'Активен' : 'Неактивен';

        $updated = Users::findFirstById($user->updated_by);
        $user->updated_by = !is_null($user->updated_by) ? $updated->first_name.' '.$updated->last_name : '-';
        $user->updated_at = !is_null($user->updated_at) ? date('d.m.Y H:i', strtotime($user->updated_at)) : '-';
        $created = Users::findFirstById($user->created_by);
        $user->created_by = !is_null($user->created_by) ? $created->first_name.' '.$created->last_name : '-';
        $user->created_at = !is_null($user->created_at) ? date('d.m.Y H:i', strtotime($user->created_at)) : '-';
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

        $form = new EmployeesForm(null, array('edit' => true));
        $this->view->form = $form;

        $data = $this->request->getPost();

        if (isset($data['password']) && !empty($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        } else {
            unset($data['password']);
        }
        $data['active'] = isset($data['active']) ? 1 : 0;

        $user->updated_by = $this->session->get('auth')['id'];
        $user->updated_at = new Phalcon\Db\RawValue('now()');

        if (!$form->isValid($data, $user)) {
            foreach ($form->getMessages() as $message) {
                $this->view->setVar($message->getField(), $message);
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
}
