<?php

use Phalcon\Mvc\View;
use Phalcon\Mvc\Model\Criteria;
/**
 * AppController
 *
 * Mobile application of the system
 */

class AppController extends AppControllerBase
{
    public function initialize()
    {
        $this->tag->setTitle('Призовка.бг');
        
        parent::initialize();
        
        $this->view->user = Users::findFirstById($this->session->get('auth')['id']);
    }

    public function indexAction()
    {  
        $this->view->addresses = $this->allAddressesPerEmployee();
    }

    public function statusAction()
    {
        $this->view->setRenderLevel(View::LEVEL_NO_RENDER);
        $auth = $this->session->get('auth');
        $addressId = $this->request->getPost('addressId');
        $status = $this->request->getPost('status');

        $subpoena = $this->assignSubpoena($addressId, $auth['id'], $status);

        if ($status == Subpoenas::DELIVERED) {
            $address = Addresses::findFirstById($addressId);

            $address->delivered = 'Y';
            $address->updated_at = new Phalcon\Db\RawValue('now()');
            $address->updated_by = $auth['id'];

            if ($address->save() == false) {
                echo json_encode(['error' => 'Възникна грешка повреме на запазването на данните. Моля, опитайте отновно!']);
                return;
            }
        }

        if ($subpoena !== false) {
            echo json_encode(['status' => $status]);
            return;
        } else {
            echo json_encode(['error' => 'Възникна грешка повреме на запазването на данните. Моля, опитайте отновно!']);
            return;
        }
    }

    public function routesAction()
    {
        $this->view->addresses = $this->allAddressesPerEmployee();
    }

    public function assignAction()
    {
        $postData = ['case_number' => '', 'reference_number' => ''];
        $addresses = [];
        if ($this->request->isPost()) {
            $addressesModel = new Addresses;
            $postData = $this->request->getPost();
            $addresses = $addressesModel->getNotAssignedAddresses($postData['case_number'], $postData['reference_number']);
        }
    
        $this->view->addresses = $addresses;
        $this->view->postData = $postData;
    }

    public function addMoreSubpoenasAction()
    {
        $this->view->setRenderLevel(View::LEVEL_NO_RENDER);
        $addressesModel = new Addresses;
        $data = $this->request->getPost();

        $addresses = $addressesModel->getNotAssignedAddresses($data['case_number'], $data['reference_number'], $data['limit']);

        echo json_encode($addresses);
    }
    
    public function assignSubpoenaAction()
    {
        $this->view->setRenderLevel(View::LEVEL_NO_RENDER);
        $auth = $this->session->get('auth');
        $id = $this->request->getPost('id');

        $addressDetails = $this->assignSubpoena($id, $auth['id'], Subpoenas::CHANGED);

        if ($addressDetails !== false) {
            echo json_encode(true);
        } else {
            echo json_encode(false);
        }
    }

    public function deliverAction() {
        $this->view->setRenderLevel(View::LEVEL_NO_RENDER);
        $auth = $this->session->get('auth');
        $id = $this->request->getPost('id');
        
        $address = Addresses::findFirstById($id);
        $address->delivered = 'Y';     
        $address->updated_by = $auth['id'];
        $address->updated_at = new Phalcon\Db\RawValue('now()');

        $addressDetails = $this->assignSubpoena($id, $auth['id'], $action = Subpoenas::DELIVERED);

        if ($address->save() !== false && $addressDetails !== false) {
            echo json_encode(true);
        } else {
            echo json_encode(false);
        }
    }

    private function allAddressesPerEmployee() {
        $auth = $this->session->get('auth');
        $addressesModel = new Addresses;
        $addresses = $addressesModel->getAddressesPerEmployee($auth['id']);

        return $addresses;
    }
}
