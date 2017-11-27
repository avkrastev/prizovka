<?php

use Phalcon\Mvc\Controller;
use Phalcon\Config\Adapter\Ini as ConfigIni;

class ControllerBase extends Controller
{

    protected function initialize()
    {
        $this->tag->prependTitle('Призовка | ');
        $this->view->setTemplateAfter('main');

        $auth = $this->session->get('auth');

        if (!isset($auth)) {
            $this->response->redirect("session/index"); 
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

            $this->view->user = $user;
            $this->view->menus = $menus;
        }
    }
}
