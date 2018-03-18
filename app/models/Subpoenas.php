<?php

use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Query;

class Subpoenas extends Model
{
    const ISSUED = 1;
    const VISITED = 2;
    const DELIVERED = 3;
    const CHANGED = 4;
    const NOT_DELIVERED = 5;
    
    public function initialize()
    {
        $this->belongsTo('assigned_to', 'Users', 'id', [
            'alias' => 'assigned_to'
        ]);

        $this->belongsTo('address', 'Addresses', 'id', [
			'alias' => 'address'
        ]);

        $this->belongsTo('updated_by', 'Users', 'id', [
            'alias' => 'updated_by'
        ]);

        $this->belongsTo('created_by', 'Users', 'id', [
            'alias' => 'created_by'
        ]);
    }

    public function getSubpoenasCountCurrentMonth() 
	{
		$query = $this->modelsManager->createQuery('SELECT COUNT(*) AS delivered, CONCAT(first_name, " ", last_name) AS name
													FROM Subpoenas
                                                    LEFT JOIN Users ON Subpoenas.assigned_to = Users.id 
													WHERE action = 3 AND (date BETWEEN DATE_FORMAT(NOW() ,"%Y-%m-01") AND NOW())
                                                    GROUP BY assigned_to');
		return $query->execute();
    }
    
    public function getSubpoenasCountPrevMonth() 
	{
        $lastMonthFirstDay = date('Y-m-d H:i:s', strtotime(date('Y-m')." -1 month"));
        $lastMonthLastDay = date('Y-m-d H:i:s', strtotime('-1 second',strtotime(date('m').'/01/'.date('Y'))));

		$query = $this->modelsManager->createQuery("SELECT COUNT(*) AS delivered, CONCAT(first_name, ' ', last_name) AS name
													FROM Subpoenas
                                                    LEFT JOIN Users ON Subpoenas.assigned_to = Users.id 
													WHERE action = 3 AND (date BETWEEN '".$lastMonthFirstDay."' AND '".$lastMonthLastDay."')
                                                    GROUP BY assigned_to");

		return $query->execute();
    }
    
    public function getDeliveredByMonths()
    {
        $oneYearAgo = date('Y-m-d', strtotime('-1 year', time()));

		$query = $this->modelsManager->createQuery("SELECT count(*) as delivered, MONTH(date) as month
                                                    FROM Subpoenas
                                                    WHERE action = 3 AND (date BETWEEN '".$oneYearAgo."' AND NOW())
                                                    GROUP BY MONTH(date)");
        return $query->execute();
    }

    public function getSubpoenasActions() 
    {
        $oneYearAgo = date('Y-m-d', strtotime('-1 year', time()));

        $query = $this->modelsManager->createQuery("SELECT
                                                        MONTH(date) as month, 
                                                        SUM(action = 2) AS visited,
                                                        SUM(action = 3) AS delivered,
                                                        SUM(action = 5) AS not_delivered
                                                    FROM Subpoenas
                                                    WHERE date BETWEEN '".$oneYearAgo."' AND NOW()
                                                    GROUP BY MONTH(date)");
        return $query->execute();
    }

    public static function getSubpoenaActions() {
		return [
            self::ISSUED => "Издадена",
            self::VISITED => "Посетен адрес",
            self::DELIVERED => "Връчена",
            self::CHANGED => "Редактирана",
            self::NOT_DELIVERED => "Невръчена",
        ];  
	}
}
