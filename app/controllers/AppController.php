<?php
use Phalcon\Mvc\View;
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
    }

    public function indexAction()
    {
        $this->view->disableLevel([View::LEVEL_LAYOUT => true]);

        $auth = $this->session->get('auth');

        $addressesModel = new Addresses;
        $addresses = $addressesModel->getAddressesPerEmployee($auth['id']);

        $this->view->addresses = $addresses;
    }

    public function addressAction($id) 
    {
        $subpoena = Subpoenas::findFirstById($id);

        $this->view->subpoena = $subpoena;
    }

    public function routesAction()
    {
        $auth = $this->session->get('auth');
        $addressesModel = new Addresses;
        $addresses = $addressesModel->getAddressesPerEmployee($auth['id']);

        $this->view->addresses = $addresses;
    }

    public function logoutAction()
    {
        $this->session->remove('auth');
        
        return $this->dispatcher->forward(
            [
                "controller" => "session",
                "action"     => "index",
            ]
        );
    }

    public function scanAction()
    {

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
            return;
        } else {
            echo json_encode(false);
            return;
        }
    }
}
