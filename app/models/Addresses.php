<?php

use Phalcon\Mvc\Model;
use Phalcon\Db\RawValue;
use Phalcon\Mvc\Model\Query;
use Phalcon\Validation;
use Phalcon\Validation\Validator\Uniqueness as UniquenessValidator;

class Addresses extends Model
{

	public $id;

	public $case_number;
	
	public $reference_number;

	public $address;

	public $longitude;

	public $latitude;

	public $delivered;

	public $updated_at;

	public $updated_by;

	public $created_at;

	public $created_by;
	

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
	
	public function validation()
    {
        $validator = new Validation();
        
        $validator->add(
            'reference_number',
            new UniquenessValidator([
            'message' => 'Изходящият номер е уникално поле!'
        ]));
        
        return $this->validate($validator);
    }

	public function beforeCreate()
	{
		$this->created_at = new RawValue('now()');
	}

	public function getAddressesWithDetails($id) 
	{
		$query = $this->modelsManager->createQuery('SELECT a.*, s.*
													FROM Addresses a
													JOIN Subpoenas s ON (a.id = s.address)
													LEFT OUTER JOIN subpoenas s2 ON (a.id = s2.address AND (s.id < s2.id))
													WHERE s2.id IS NULL AND a.id = '.$id.'');
		return $query->execute();
	}

	public function getAddressesPerEmployee($id) 
	{
		$query = $this->modelsManager->createQuery('SELECT a.*, s.*
													FROM Addresses a
													JOIN Subpoenas s ON (a.id = s.address)
													LEFT OUTER JOIN subpoenas s2 ON (a.id = s2.address AND (s.id < s2.id))
													WHERE s2.id IS NULL AND s.assigned_to = '.$id.' AND a.delivered = "N"');
		return $query->execute();
	}

	public function getNotAssignedAddresses($case_number, $reference_number) 
	{
		$query = $this->modelsManager->createQuery('SELECT a.*
													FROM Addresses a
													LEFT JOIN Subpoenas s ON (a.id = s.address)
													WHERE s.assigned_to IS NULL 
													AND a.case_number like "%'.$case_number.'%" 
													AND a.reference_number like "%'.$reference_number.'%"');
		return $query->execute();
	}

	public function getAllAddresses($delivered = 'N') 
	{
		$query = $this->modelsManager->createQuery('SELECT a.*, s.*
													FROM Addresses a
													LEFT JOIN Subpoenas s ON (a.id = s.address)
													WHERE a.delivered = "'.$delivered.'"
													GROUP BY a.id');
		return $query->execute();
	}

	public function getAddressesHistory($criteria) 
	{
		$where = '';
		foreach ($criteria as $k=>$v) {
			if (!empty($v)) {
				if ($k == 'start') {
					$where.= ' AND date > "'.date('Y-m-d', strtotime($v)).'"';
				} elseif ($k == 'end') {
					$where.= ' AND date < "'.date('Y-m-d', strtotime($v)).'"';
				} else {
					$where.= ' AND '.$k.' = "'.$v.'"';
				}
			}
		}

		$query = $this->modelsManager->createQuery('SELECT a.*, s.*
													FROM Addresses a
													LEFT JOIN Subpoenas s ON (a.id = s.address)
													WHERE s.action = 3 '.$where.'
													GROUP BY a.id');
		return $query->execute();
	}


}
