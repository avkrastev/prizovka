<?php

use Phalcon\Mvc\Model;
use Phalcon\Db\RawValue;

class Addresses extends Model
{

	public $id;

	public $assigned_to;

	public $created_at;

	public function initialize()
    {
        $this->belongsTo('assigned_to', 'Users', 'id', [
			'alias' => 'assigned_to'
		]);

		$this->belongsTo('updated_by', 'Users', 'id', [
			'alias' => 'updated_by'
		]);

		$this->belongsTo('created_by', 'Users', 'id', [
			'alias' => 'created_by'
		]);
    }

	public function beforeCreate()
	{
		$this->created_at = new RawValue('now()');
	}

}
