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
            $loggedUser = Users::findFirst($this->session->get('auth')['id']);
            
            $address = new Addresses();
            $address->case_number = $this->request->getPost('case_number');
            $address->reference_number = $this->request->getPost('reference_number');
            $address->address = $this->request->getPost('address');
            $address->latitude = $this->request->getPost('latitude');
            $address->longitude = $this->request->getPost('longitude');
            $address->assigned_to = $this->request->getPost('assigned_to');
            $address->created_by = $this->session->get('auth')['id'];
            $address->created_at = new Phalcon\Db\RawValue('now()');

            if ($address->save() == false) {
                $this->flash->error("Възникна грешки повреме на запазването на данните!");

                return $this->dispatcher->forward(
                    [
                        "controller" => "addresses",
                        "action"     => "index",
                    ]
                );
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

    public function qrAction() 
    {
        $subpoena = $this->request->get('subpoena');
        if (!empty($subpoena)) {
            $subpoena = explode('&', base64_decode($subpoena));
            $coords = explode(',', $subpoena[0]);

            $existingSubpoena = Addresses::query()
                                        ->where('latitude = :lat: and longitude = :lng: and case_number = :case: and reference_number = :refNumber:')
                                        ->bind(['lat' => $coords[0], 'lng' => $coords[1], 'case' => $subpoena[1], 'refNumber' => $subpoena[2]])
                                        ->execute()
                                        ->getFirst();

            if (!$existingSubpoena) {
                $address = new Addresses();
                $address->case_number = $subpoena[1];
                $address->reference_number = $subpoena[2];
                $address->address = $this->getAddressByCoords($coords[0], $coords[1]);
                $address->latitude = $coords[0];
                $address->longitude = $coords[1];
                $address->assigned_to = $this->session->get('auth')['id'];
                $address->created_by = $this->session->get('auth')['id'];
                $address->created_at = new Phalcon\Db\RawValue('now()');

                if ($address->save() == false) {
                    $this->flash->error("Възникна грешки повреме на запазването на данните!");
    
                    return $this->dispatcher->forward(
                        [
                            "controller" => "addresses",
                            "action"     => "index",
                        ]
                    );
                } else {
                    $this->flash->success('Призовката беше зачислена успешно!');
    
                    $addresses = Addresses::find();
                    $lastPage = intval(ceil(count($addresses)*0.1));
                    $this->view->pick('subpoenas/index')->setVar('addressId', $address->id);
    
                    return $this->response->redirect('/subpoenas/index?page='.$lastPage.'&addressid='.$address->id);
                }
            } else {
                $existingSubpoena->assigned_to = $this->session->get('auth')['id'];
                $existingSubpoena->updated_by = Users::findFirst($this->session->get('auth')['id'])->id;
                $existingSubpoena->updated_at = new Phalcon\Db\RawValue('now()');

                if ($existingSubpoena->save() == false) {
                    $this->flash->error("Възникна грешки повреме на запазването на данните!");
        
                    return $this->dispatcher->forward(
                        [
                            "controller" => "subpoenas",
                            "action"     => "edit",
                            "params"     => [$id]
                        ]
                    );
                }

                $this->flash->success("Информацията беше редактирана успешно!");
        
                return $this->dispatcher->forward(
                    [
                        "controller" => "subpoenas",
                        "action"     => "index",
                    ]
                );
            }
        }     
    }

    private function getAddressByCoords($lat, $lng) 
    {
        $url = "http://maps.googleapis.com/maps/api/geocode/json?latlng=".$lat.",".$lng."&sensor=true";
        $json_result = json_decode(file_get_contents($url));

        return $json_result->results[0]->formatted_address;
    }
}
