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

    public function listAction() 
    {
        
    }

    public function createQRAction() 
    {
        $this->view->setRenderLevel(View::LEVEL_NO_RENDER);
        $number = $this->request->getPost('number');
        $address = $this->request->getPost('address');
        $date = $this->request->getPost('date');

        $this->getAddressCoords($address);
        
        $data = 'latlng='.$this->lat.','.$this->lng.',date='.strtotime($date).',number='.$number;

        $image = 'https://api.qrserver.com/v1/create-qr-code/?data="'.$data.'"&amp;size=150x150';

        $imageData = base64_encode(file_get_contents($image));
        $src = 'data: image/x-png; base64,'.$imageData;

        echo json_encode(['data' => $data, 'src' => $src]);
    }

    public function assignAction() 
    {
        $form = new AddressesForm;
        if ($this->request->isPost()) {
            $loggedUser = Users::findFirst($this->session->get('auth')['id']); // TODO set firm to session

            $address = $this->request->getPost('address');
            $this->getAddressCoords($address);

            $address = new Addresses();
            $address->firm = $loggedUser->firm;
            $address->number = $this->request->getPost('number');
            $address->date = date('Y-m-d', strtotime($this->request->getPost('date')));
            $address->latitude = $this->lat;
            $address->longitude = $this->lng;
            $address->assigned_to = $this->request->getPost('assign');
            $address->created_by = $this->session->get('auth')['id'];

            if ($address->save() == false) {
                /*if (!$form->isValid($data, $address)) {
                    foreach ($form->getMessages() as $message) {
                        $this->view->setVar($message->getField(), $message);
                    }
                }*/
            } else {
                $this->flash->success('Призовката беше зачислена успешно!');
                
                return $this->dispatcher->forward(
                    [
                        "controller" => "addresses",
                        "action"     => "list",
                    ]
                );
            }
        }
    }

    private function getAddressCoords($address) 
    {
        $address = preg_replace('/\s+/', '+', $address);

        $url = "https://maps.googleapis.com/maps/api/geocode/json?address=".$address."&key=AIzaSyAtCnmwX45uhYbzCjNI7a5FRl4PbthO2LU";
        $json_result = json_decode(file_get_contents($url));

        $this->lat = $json_result->results[0]->geometry->location->lat;
        $this->lng = $json_result->results[0]->geometry->location->lng;
    }
}