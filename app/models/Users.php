<?php

use Phalcon\Mvc\Model;
use Phalcon\Validation;
use Phalcon\Validation\Validator\Email as EmailValidator;
use Phalcon\Validation\Validator\Uniqueness as UniquenessValidator;

class Users extends Model
{
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
}
