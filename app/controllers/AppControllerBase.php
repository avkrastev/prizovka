<?php

use Phalcon\Mvc\Controller;

class AppControllerBase extends CommonController
{

    protected function initialize()
    {
        $this->view->setMainView('app');

        parent::initialize();
    }
}
