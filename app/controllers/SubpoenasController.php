<?php

use Phalcon\Flash;
use Phalcon\Session;
use Phalcon\Mvc\View;
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;

class SubpoenasController extends ControllerBase
{
    public function initialize()
    {
        $this->tag->setTitle('Призовки');
        parent::initialize();
    }

    public function indexAction() 
    {
        $numberPage = $this->request->getQuery('page','int', 1);
        $addressesModel = new Addresses;
        $addresses = $addressesModel->getAllAddresses();

        $paginator = new Paginator(array(
            "data"  => $addresses,
            "limit" => 10,
            "page"  => $numberPage
        ));

        $this->view->address = $addresses;
        $this->view->page = $paginator->getPaginate();
        $this->view->form = new AddressesForm($addresses, array('search' => true));
    }

    public function searchAction() 
    {
        $addressesModel = new Addresses;
        $numberPage = 1;
        $parameters = [];
        if ($this->request->isPost()) {
            $parameters = $this->request->getPost();
        } else {
            $numberPage = $this->request->getQuery('page','int');
        }

        $order = $this->request->get('order', 'string', 'case_number');
        $direction = $this->request->get('direction', 'string', 'asc');

        $addresses = $addressesModel->getNotDeliveredAddressesByCriteria($parameters, $order.' '.$direction);

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

        $this->view->address = $addresses;
        $this->view->page = $paginator->getPaginate();
        $this->view->order = [$order => $direction == 'asc' ? 'desc' : 'asc'];
        $this->view->form = new AddressesForm($addresses, array('search' => true));
    }

    public function viewAction()
    {
        $this->view->setRenderLevel(View::LEVEL_NO_RENDER);

        $addressId = $this->request->getPost('addressId');

        $addresses = new Addresses();
        $address = $addresses->getAddressesWithDetails($addressId);

        if (count($address) > 0) {
            $user = Users::findFirstById($address[0]->s->assigned_to);
            $assigned_to = $user->first_name.' '.$user->last_name;
            $address = $address[0]->a;    
        } else {
            $address = Addresses::findFirstById($addressId);
            $assigned_to = '';
        }

        if (!$address) {
            echo json_encode(['error' => 'Адресът не беше намерен!']);
            return;
        }

        $this->serviceFields($address);

        echo json_encode(['address' => $address, 'assigned_to' => $assigned_to]);
    }

    /**
    * Edits an address based on its id
    */
    public function editAction($id)
    {
        if (!$this->request->isPost()) {
            $addresses = new Addresses();
            $address = $addresses->getAddressesWithDetails($id);

            if (count($address) > 0) {
                $address[0]->a->assigned_to = $address[0]->s->assigned_to;
                $address = $address[0]->a;
            } else {
                $address = Addresses::findFirstById($id);
                $address->assigned_to = null;
            }

            if (!$address) {
                $this->flash->error("Призовката не беше намерена!");

                return $this->dispatcher->forward(
                    [
                        "controller" => "subpoenas",
                        "action"     => "index",
                    ]
                );
            }

            $this->serviceFields($address);

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

        $address->case_number = $data['case_number'];
        $address->reference_number = $data['reference_number'];
        $address->address = $data['address'];
        $address->latitude = $data['latitude'];
        $address->longitude = $data['longitude'];

        $address->updated_by = Users::findFirst($this->session->get('auth')['id'])->id;
        $address->updated_at = new Phalcon\Db\RawValue('now()');

        if (!empty($data['assigned_to']) && $data['old_assignment'] != $data['assigned_to']) {
            $subpoena = $this->assignSubpoena($id, $data['assigned_to'], Subpoenas::CHANGED);
            if (!$subpoena) {
                $this->flash->error("Възникна грешки повреме на запазването на данните!");

                return $this->dispatcher->forward(
                    [
                        "controller" => "addresses",
                        "action"     => "index",
                    ]
                );
            }
        }

        if ($address->save() == false) {
            foreach ($address->getMessages() as $message) {
                $this->flash->error((string) $message);
            }

            return $this->dispatcher->forward(
                [
                    "controller" => "addresses",
                    "action"     => "index",
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

    public function detailsAction($id)
    {
        if (!$this->request->isPost()) {
            $numberPage = 1;
            $numberPage = $this->request->getQuery('page','int');
            
            $subpoenas = Subpoenas::query()
                                    ->where('address = :id:')
                                    ->bind(['id' => $id])
                                    ->execute();

            if (!$subpoenas || count($subpoenas) == 0) {
                $this->flash->error("Няма детайли за избраната призовка!");

                return $this->dispatcher->forward(
                    [
                        "controller" => "subpoenas",
                        "action"     => "index",
                    ]
                );
            }

            $paginator = new Paginator(array(
                "data"  => $subpoenas,
                "limit" => 5,
                "page"  => $numberPage
            ));

            $daysOfWeek = ['Неделя', 'Понеделник', 'Вторник', 'Сряда', 'Четвъртък', 'Петък', 'Събота'];
    
            $this->view->subpoena = $subpoenas;
            $this->view->daysOfWeek = $daysOfWeek;
            $this->view->actions = Subpoenas::getSubpoenaActions();
            $this->view->page = $paginator->getPaginate();
        }
    }

    private function serviceFields(&$address) 
    {
        $address->updated_by = !empty($address->getUpdated_by()) ? $address->getUpdated_by()->first_name.' '.$address->getUpdated_by()->last_name : '-';
        $address->updated_at = !is_null($address->updated_at) ? date('d.m.Y H:i', strtotime($address->updated_at)) : '-';
        
        $address->created_by = !empty($address->getCreated_by()) ? $address->getCreated_by()->first_name.' '.$address->getCreated_by()->last_name : '-';
        $address->created_at = !is_null($address->created_at) ? date('d.m.Y H:i', strtotime($address->created_at)) : '-';
    }
}
