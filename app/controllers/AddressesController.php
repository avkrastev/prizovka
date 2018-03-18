<?php

use Phalcon\Flash;
use Phalcon\Session;
use Phalcon\Mvc\View;
use Phalcon\Paginator\Adapter\Model as Paginator;

class AddressesController extends ControllerBase
{
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
            $address = new Addresses();
            
            $address->case_number = $this->request->getPost('case_number');
            $address->reference_number = $this->request->getPost('reference_number');
            $address->address = $this->request->getPost('address');
            $address->latitude = $this->request->getPost('latitude');
            $address->longitude = $this->request->getPost('longitude');
            $address->created_by = $this->session->get('auth')['id'];
            $address->created_at = new Phalcon\Db\RawValue('now()');

            if ($address->save() == false) {
                foreach ($address->getMessages() as $message) {
                    $this->flash->error((string) $message);
                } 
            } else {
                $assigned_to = $this->request->getPost('assigned_to');
                if (!empty($assigned_to)) {
                    $subpoena = $this->assignSubpoena($address->id, $assigned_to, Subpoenas::ISSUED);
                    
                    if ($subpoena !== false) {
                        $this->flash->success('Призовката беше зачислена успешно!');
                        
                        $addresses = Addresses::find();
                        $lastPage = intval(ceil(count($addresses)*0.1));
        
                        return $this->response->redirect('/subpoenas/index?page='.$lastPage.'&addressid='.$address->id);
                    } else {
                        $this->flash->error("Възникна грешки повреме на запазването на данните!");
                    }
                } else {
                    $this->flash->success('Призовката беше създадена успешно, но не е зачислена към служител!');

                    return $this->response->redirect('/addresses/index');
                }
            }

            return $this->dispatcher->forward(
                [
                    "controller" => "addresses",
                    "action"     => "index",
                ]
            );
        }

        $this->view->form = $form;
    }

    public function qrAction() 
    {
        if (!$this->isLogged()) {
            return $this->dispatcher->forward(
                [
                    "controller" => "session",
                    "action"     => "start",
                ]
            );
        } 

        $subpoena = $this->request->get('subpoena');
        $this->view->error = '';
        if (!empty($subpoena)) {
            $subpoena = explode('&', base64_decode($subpoena));
            $coords = explode(',', $subpoena[0]);

            $existingSubpoena = Addresses::query()
                                            ->where('case_number = :case: and reference_number = :refNumber:')
                                            ->bind(['case' => $subpoena[1], 'refNumber' => $subpoena[2]])
                                            ->execute()
                                            ->getFirst();
            // Start a transaction
            $this->db->begin();

            if (!$existingSubpoena) {
                $address = new Addresses();
                $address->case_number = $subpoena[1];
                $address->reference_number = $subpoena[2];
                $address->address = $this->getAddressByCoords($coords[0], $coords[1]);
                $address->latitude = $coords[0];
                $address->longitude = $coords[1];
                $address->created_by = $this->session->get('auth')['id'];
                $address->created_at = new Phalcon\Db\RawValue('now()');

                try {
                    if ($address->save() === false) {
                        throw new Exception('Възникна грешки повреме на запазването на данните!');
                    }
                    
                    if ($this->assignSubpoena($address->id, $this->session->get('auth')['id'], Subpoenas::ISSUED) === false) {
                        throw new Exception('Възникна грешки повреме на запазването на данните!'); 
                    }
                } catch (Exception $e) {
                    $this->db->rollback();
                    $this->view->error = $e->getMessage();

                    return $this->dispatcher->forward(
                        [
                            "controller" => "app",
                            "action"     => "index",
                        ]
                    );
                }

                $successId = $address->id;
            } else {
                $existingSubpoena->case_number = $subpoena[1];
                $existingSubpoena->reference_number = $subpoena[2];
                $existingSubpoena->latitude = $coords[0];
                $existingSubpoena->longitude = $coords[1];
                $existingSubpoena->address = $this->getAddressByCoords($coords[0], $coords[1]);
                $existingSubpoena->updated_by = $this->session->get('auth')['id'];
                $existingSubpoena->updated_at = new Phalcon\Db\RawValue('now()'); 

                try {
                    if ($existingSubpoena->save() === false) {
                        throw new Exception('Възникна грешки повреме на запазването на данните!');
                    }
                    
                    if ($this->assignSubpoena($existingSubpoena->id, $this->session->get('auth')['id'], Subpoenas::CHANGED) === false) {
                        throw new Exception('Възникна грешки повреме на запазването на данните!'); 
                    }
                } catch (Exception $e) {
                    $this->db->rollback();
                    $this->view->error = $e->getMessage();

                    return $this->dispatcher->forward(
                        [
                            "controller" => "app",
                            "action"     => "index",
                        ]
                    );
                }
                $successId = $existingSubpoena->id;
            }

            $this->db->commit();
            $this->view->successId = $successId;
            
            return $this->dispatcher->forward(
                [
                    "controller" => "app",
                    "action"     => "index",
                ]
            );
        }     
    }

    private function getAddressByCoords($lat, $lng) 
    {
        $url = "https://maps.googleapis.com/maps/api/geocode/json?latlng=".$lat.",".$lng."&sensor=true&language=bg&region=BG&key=AIzaSyDUImjLeDBrWYfZZ9WRKAPbsl1k5xo-3o0";
        
        $arrContextOptions=[
            "ssl"=>[
                "verify_peer"=>false,
                "verify_peer_name"=>false,
            ],
        ];
        
        $json_result = json_decode(file_get_contents($url, false, stream_context_create($arrContextOptions)));

        return $json_result->results[0]->formatted_address;
    }
}
