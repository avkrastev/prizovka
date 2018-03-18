<?php

use Phalcon\Mvc\Controller;

class AppControllerBase extends CommonController
{

    protected function initialize()
    {
        $this->view->setTemplateAfter('app');
        $this->view->setMainView('app');

        parent::initialize();
    }
}
