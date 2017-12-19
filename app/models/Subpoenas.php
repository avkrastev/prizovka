<?php

use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Query\Builder;

class Subpoenas extends Model
{
    const ISSUED = 1;
    const VISITED = 2;
    const DELIVERED = 3;
    
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

    public static function getSubpoenaActions() {
		return [
			self::ISSUED => "Издадена",
			self::VISITED => "Посетен адрес",
			self::DELIVERED => "Връчена",
        ];  
	}
}
