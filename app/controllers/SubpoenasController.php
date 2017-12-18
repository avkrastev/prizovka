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
        $numberPage = $this->request->getQuery('page','int');

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
    * Edits an address based on its id
    */
    public function editAction($id)
    {
        if (!$this->request->isPost()) {
            $address = Addresses::findFirstById($id);
            if (!$address) {
                $this->flash->error("Призовката не беше намерена!");

                return $this->dispatcher->forward(
                    [
                        "controller" => "subpoenas",
                        "action"     => "index",
                    ]
                );
            }
            $this->serviceFields($address, true);

            $this->view->form = new AddressesForm($address, array('edit' => true));
            $this->view->address = $address;
        }
    }

    /**
    * Saves current subpoena in screen
    *
    * @param string $id
    */
    public function saveAction()
    {
        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(
                [
                    "controller" => "subpoenas",
                    "action"     => "index",
                ]
            );
        }

        $id = $this->request->getPost("id", "int");

        $address = Addresses::findFirstById($id);
        if (!$address) {
            $this->flash->error("Призовката не съществува!");

            return $this->dispatcher->forward(
                [
                    "controller" => "subpoenas",
                    "action"     => "index",
                ]
            );
        }

        $form = new AddressesForm(null, array('edit' => true));
        $this->view->form = $form;

        $data = $this->request->getPost();
        $address->reference_number = $data['reference_number'];

        $address->updated_by = Users::findFirst($this->session->get('auth')['id'])->id;
        $address->updated_at = new Phalcon\Db\RawValue('now()');

        if ($address->save() == false) {
            $this->flash->error("Възникна грешки повреме на запазването на данните!");

            return $this->dispatcher->forward(
                [
                    "controller" => "subpoenas",
                    "action"     => "edit",
                    "params"     => [$id]
                ]
            );
        }

        $form->clear();

        $this->flash->success("Информацията беше редактирана успешно!");

        return $this->dispatcher->forward(
            [
                "controller" => "subpoenas",
                "action"     => "index",
            ]
        );
    }

    private function serviceFields(&$address, $edit = false) 
    {
        if ($edit === false) {
            $address->assigned_to = $address->getAssigned_to()->first_name.' '.$address->getAssigned_to()->last_name;
        }

        $address->updated_by = !empty($address->getUpdated_by()) ? $address->getUpdated_by()->first_name.' '.$address->getUpdated_by()->last_name : '-';
        $address->updated_at = !is_null($address->updated_at) ? date('d.m.Y H:i', strtotime($address->updated_at)) : '-';
        
        $address->created_by = !empty($address->getCreated_by()) ? $address->getCreated_by()->first_name.' '.$address->getCreated_by()->last_name : '-';
        $address->created_at = !is_null($address->created_at) ? date('d.m.Y H:i', strtotime($address->created_at)) : '-';
    }
}
