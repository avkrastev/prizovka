<?php

use Phalcon\Mvc\Controller;

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
            $this->view->user = $user;
        }
    }
}
