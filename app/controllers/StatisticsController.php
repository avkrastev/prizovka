<?php
use Phalcon\Mvc\View;

class StatisticsController extends ControllerBase
{
    public function initialize()
    {
        $this->tag->setTitle('Статистика');
        parent::initialize();
    }

    public function indexAction()
    {
        $months = ['Януари', 'Февруари', 'Март', 'Април', 'Май', 'Юни', 'Юли', 'Август', 'Септември', 'Октомври', 'Ноември', 'Декември'];

        $this->view->months = $months;
    }

    public function getStatsAction() 
    {
        $this->view->setRenderLevel(View::LEVEL_NO_RENDER);
        $subpoenasModel = new Subpoenas;

        $subpoenasCountCurrentMonth = [];
        foreach ($subpoenasModel->getSubpoenasCountCurrentMonth() as $stc) {
            $subpoenasCountCurrentMonth['count'][] = $stc->delivered;
            $subpoenasCountCurrentMonth['name'][] = $stc->name;
        } 

        $subpoenasCountPrevMonth = [];
        foreach ($subpoenasModel->getSubpoenasCountPrevMonth() as $stp) {
            $subpoenasCountPrevMonth['count'][] = $stp->delivered;
            $subpoenasCountPrevMonth['name'][] = $stp->name;
        } 

        $allDeliveredByMonths = array_fill(1, 12, 0);

        foreach ($subpoenasModel->getDeliveredByMonths() as $count) {
            $allDeliveredByMonths[$count->month] = $count->delivered;
        }

        $allDeliveredByMonths = array_values($allDeliveredByMonths);

        $allSubpoenasActionPerMonths['delivered'] = array_fill(1, 12, 0);
        $allSubpoenasActionPerMonths['visited'] = array_fill(1, 12, 0);
        $allSubpoenasActionPerMonths['not_delivered'] = array_fill(1, 12, 0);

        foreach ($subpoenasModel->getSubpoenasActions() as $action) {
            $allSubpoenasActionPerMonths['delivered'][$action->month] = $action->delivered;
            $allSubpoenasActionPerMonths['visited'][$action->month] = $action->visited;
            $allSubpoenasActionPerMonths['not_delivered'][$action->month] = $action->not_delivered;
        }

        $allSubpoenasActionPerMonths['delivered'] = array_values($allSubpoenasActionPerMonths['delivered']);
        $allSubpoenasActionPerMonths['visited'] = array_values($allSubpoenasActionPerMonths['visited']);
        $allSubpoenasActionPerMonths['not_delivered'] = array_values($allSubpoenasActionPerMonths['not_delivered']);

        echo json_encode(['subpoenasCountCurrentMonth' => $subpoenasCountCurrentMonth, 
                          'subpoenasCountPrevMonth' => $subpoenasCountPrevMonth, 
                          'allDeliveredByMonths' => $allDeliveredByMonths,
                          'allSubpoenasActionPerMonths' => $allSubpoenasActionPerMonths], JSON_NUMERIC_CHECK);
    }
}
