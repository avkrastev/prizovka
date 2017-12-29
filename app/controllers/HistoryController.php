<?php
use Phalcon\Mvc\View;
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;

class HistoryController extends ControllerBase
{
    public function initialize()
    {
        $this->tag->setTitle('История');
        parent::initialize();
    }

    /**
     * Shows the index action
     */
    public function indexAction()
    {
        $this->session->conditions = null;
        $this->view->form = new AddressesForm(null, array('history' => true));
    }

    /**
     * Search subpoenas based on current criteria
     */
    public function searchAction()
    {
        $numberPage = 1;
        $history = [];
        if ($this->request->isPost()) {
            $addressesModel = new Addresses();
            $history = $addressesModel->getAddressesHistory($this->request->getPost());
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        if (count($history) == 0) {
            $this->flash->notice("Няма открити резултати по зададените критерии");

            return $this->dispatcher->forward(
                [
                    "controller" => "history",
                    "action"     => "index",
                ]
            );
        }

        $paginator = new Paginator(array(
            "data"  => $history,
            "limit" => 10,
            "page"  => $numberPage
        ));

        $this->view->page = $paginator->getPaginate();
    }
}