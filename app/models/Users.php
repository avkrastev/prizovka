<?php

use Phalcon\Mvc\Model;
use Phalcon\Validation;
use Phalcon\Validation\Validator\Email as EmailValidator;
use Phalcon\Validation\Validator\Uniqueness as UniquenessValidator;

class Users extends Model
{
    const ADMIN = 1;
    const COADMIN = 2;
	const EMPLOYEE = 3;
    const SUMMON = 4;
    const CLERK = 5;
    
    public function validation()
    {
        $validator = new Validation();
        
        $validator->add(
            'email',
            new EmailValidator([
            'message' => 'Invalid email given'
        ]));
        $validator->add(
            'email',
            new UniquenessValidator([
            'message' => 'Sorry, The email was registered by another user'
        ]));
        
        return $this->validate($validator);
    }

    public static function getUserTypes() {
		return [
			self::ADMIN => "ЧСИ",
			self::COADMIN => "ПЧСИ",
			self::EMPLOYEE => "Служител",
            self::SUMMON => "Призовкар",
            self::CLERK => "Деловодител"
        ];  
	}
}