<?php

use Phalcon\Mvc\Controller;
use Phalcon\Config\Adapter\Ini as ConfigIni;

class CommonController extends Controller
{

    protected function initialize()
    {
        $auth = $this->session->get('auth');

        if (!isset($auth)) {
            $this->response->redirect('login'); 
            return;
        } else {
            $user = Users::findFirst($auth['id']);

            $menus = new ConfigIni(APP_PATH . 'app/config/menus/menu.ini');

            foreach($menus as $menu) {
                if (isset($menu['submenu'])) {
                    foreach($menu['submenu'] as $sub) {
                        $menu['submenu'] = new ConfigIni(APP_PATH . 'app/config/menus/'.$menu->controller.'.ini');   
                    }
                }
            }

            $this->view->userData = $user;
            $this->view->menus = $menus;
        }
    }

    protected function assignSubpoena($addressId, $assigned_to, $action = Subpoenas::VISITED) 
    {
        $subpoena = new Subpoenas();
      
        $subpoena->address = $addressId;
        $subpoena->assigned_to = $assigned_to;
        $subpoena->date = new Phalcon\Db\RawValue('now()');
        $subpoena->action = $action;
        $subpoena->created_by = $this->session->get('auth')['id'];
        $subpoena->created_at = new Phalcon\Db\RawValue('now()');

        if ($subpoena->save() == false) {
            return false;
        } else {
            return true;
        }
    }

    protected function isLogged() 
    {
        $auth = $this->session->get('auth');

        return isset($auth) ? true : false;
    }
}
