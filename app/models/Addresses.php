<?php

use Phalcon\Mvc\Model;
use Phalcon\Db\RawValue;
use Phalcon\Mvc\Model\Query;

class Addresses extends Model
{

	public $id;

	public $assigned_to;

	public $created_at;

	public function initialize()
    {
		$this->belongsTo('updated_by', 'Users', 'id', [
			'alias' => 'updated_by'
		]);

		$this->belongsTo('created_by', 'Users', 'id', [
			'alias' => 'created_by'
		]);

		$this->hasMany('id', 'Subpoenas', 'address', [
            'alias' => 'address',
            'reusable' => true
        ]);
    }

	public function beforeCreate()
	{
		$this->created_at = new RawValue('now()');
	}

	public function getAddressesWithDetails($id) 
	{
		$query = $this->modelsManager->createQuery('SELECT a.*, s.*
													FROM addresses a
													JOIN subpoenas s ON (a.id = s.address)
													LEFT OUTER JOIN subpoenas s2 ON (a.id = s2.address AND (s.id < s2.id))
													WHERE s2.id IS NULL AND a.id = '.$id.'');
		return $query->execute();
	}

}
