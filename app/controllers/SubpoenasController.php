<?php

use Phalcon\Flash;
use Phalcon\Session;
use Phalcon\Mvc\View;
use Phalcon\Paginator\Adapter\Model as Paginator;

class SubpoenasController extends ControllerBase
{
    public $lat = null;
    public $lng = null;

    public function initialize()
    {
        $this->tag->setTitle('Призовки');
        parent::initialize();
    }

    public function indexAction() 
    {
        $numberPage = 1;
        $numberPage = $this->request->getQuery("page", "int");

        $parameters['order'] = 'id ASC'; //TODO get order dinamically
        $addresses = Addresses::find($parameters);

        if (count($addresses) == 0) {
            $this->flash->notice("Няма намерени адреси по зададените критерии!");

            return $this->dispatcher->forward(
                [
                    "controller" => "subpoenas",
                    "action"     => "index"
                ]
            );
        }

        $paginator = new Paginator(array(
            "data"  => $addresses,
            "limit" => 10,
            "page"  => $numberPage
        ));

        $this->view->users = $addresses;
        $this->view->page = $paginator->getPaginate();
    }

    public function viewAction()
    {
        $this->view->setRenderLevel(View::LEVEL_NO_RENDER);

        $addressId = $this->request->getPost('addressId');

        $address = Addresses::findFirstById($addressId);

        if (!$address) {
            echo json_encode(['error' => 'Адресът не беше намерен!']);
            return;
        }

        $this->serviceFields($address);

        echo json_encode($address);
    }

    /**
    * Edits an user based on its id
    */
    public function editAction($id)
    {
        /*if (!$this->request->isPost()) {
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
        }*/
    }

    private function serviceFields(&$address) 
    {
        $address->assigned_to = $address->getAssigned_to()->first_name.' '.$address->getAssigned_to()->last_name;

        $address->updated_by = !empty($address->getUpdated_by()) ? $address->getUpdated_by()->first_name.' '.$address->getUpdated_by()->last_name : '-';
        $address->updated_at = !is_null($address->updated_at) ? date('d.m.Y H:i', strtotime($address->updated_at)) : '-';
        
        $address->created_by = !empty($address->getCreated_by()) ? $address->getCreated_by()->first_name.' '.$address->getCreated_by()->last_name : '-';
        $address->created_at = !is_null($address->created_at) ? date('d.m.Y H:i', strtotime($address->created_at)) : '-';
    }
}
