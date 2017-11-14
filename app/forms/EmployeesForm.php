<?php

use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\Password;
use Phalcon\Forms\Element\Select;
use Phalcon\Forms\Element\Check;
use Phalcon\Forms\Element\Hidden;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Email;

class EmployeesForm extends Form
{

    public function initialize($entity = null, $options = null)
    {
        if (isset($options['edit'])) {
            $this->add(new Hidden("id"));
        }
        // First name
        $first_name = new Text('first_name');
        $first_name->setLabel('Име');
        $first_name->setFilters(array('striptags', 'string'));
        $this->add($first_name);

        // Last name
        $last_name = new Text('last_name');
        $last_name->setLabel('Фамилия');
        $last_name->setFilters(array('striptags', 'string'));
        $this->add($last_name);

        // Email
        $email = new Text('email');
        $email->setLabel('Електронна поща');
        $email->setFilters('email');
        $email->addValidators(array(
            new PresenceOf(array(
                'message' => 'E-mail is required'
            )),
            new Email(array(
                'message' => 'E-mail is not valid'
            ))
        ));
        $this->add($email);

        // Password
        $password = new Password('password');
        $password->setLabel('Парола');
        $password->addValidators(array(
            new PresenceOf(array(
                'message' => 'Password is required'
            ))
        ));
        $this->add($password);

        $type = new Select('type', Users::getUserTypes(), array(
            'using'      => array('id', 'name'),
            'useEmpty'   => true,
            'emptyText'  => '',
            'emptyValue' => ''
        ));
        $type->setLabel('Тип потребител');
        $this->add($type);

        $active = new Check('active', array(
            'checked'  => 1
        ));
        $active->setLabel('Активен профил');
        $this->add($active);
    }
}