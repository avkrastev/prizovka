<?php

use Phalcon\Mvc\Controller;

class ControllerBase extends CommonController
{

    protected function initialize()
    {
        $this->tag->prependTitle('Призовка | ');
        $this->view->setMainView('index');
        $this->view->setTemplateAfter('main');

        parent::initialize();
    }
}
