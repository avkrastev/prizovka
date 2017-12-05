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
			'alias' => 'address'
		]);
    }

	public function beforeCreate()
	{
		$this->created_at = new RawValue('now()');
	}

}
