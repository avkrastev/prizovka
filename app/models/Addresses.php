<?php

use Phalcon\Mvc\Model;
use Phalcon\Db\RawValue;

class Addresses extends Model
{

	public $id;

	public $created_at;

	public function beforeCreate()
	{
		$this->created_at = new RawValue('now()');
	}

}
