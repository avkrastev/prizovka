<?php

use Phalcon\Flash;
use Phalcon\Session;
use Phalcon\Mvc\View;
use Phalcon\Paginator\Adapter\Model as Paginator;

class AddressesController extends ControllerBase
{
    public $lat = null;
    public $lng = null;

    public function initialize()
    {
        $this->tag->setTitle('Адреси');
        parent::initialize();
    }

    /**
    * Address form for creating QR codes
    */
    public function indexAction()
    {
        $form = new AddressesForm;
        $this->view->form = $form;
    }

    public function createQRAction() 
    {
        $this->view->setRenderLevel(View::LEVEL_NO_RENDER);
        $url = $this->request->getPost('url');

        $imageData = base64_encode(file_get_contents($url));
        $src = 'data: image/x-png; base64,'.$imageData;

        echo json_encode($src);
    }

    public function assignAction() 
    {
        $form = new AddressesForm;
        if ($this->request->isPost()) {
            $loggedUser = Users::findFirst($this->session->get('auth')['id']); // TODO set firm to session
            

            $address = new Addresses();
            $address->firm = $loggedUser->firm;
            $address->case_number = $this->request->getPost('case_number');
            $address->reference_number = $this->request->getPost('reference_number');
            $address->address = $this->request->getPost('address');
            $address->latitude = $this->request->getPost('latitude');
            $address->longitude = $this->request->getPost('longitude');
            $address->assigned_to = $this->request->getPost('assign');
            $address->created_by = $this->session->get('auth')['id'];
            $address->created_at = new Phalcon\Db\RawValue('now()');

            if ($address->save() == false) {
                $this->flash->error("Възникна грешки повреме на запазването на данните!");
            } else {
                $this->flash->success('Призовката беше зачислена успешно!');

                $addresses = Addresses::find();
                $lastPage = intval(ceil(count($addresses)*0.1));
                $this->view->pick('subpoenas/index')->setVar('addressId', $address->id);

                return $this->response->redirect('/subpoenas/index?page='.$lastPage.'&addressid='.$address->id);
            }
        }

        $this->view->form = $form;
    }
}
