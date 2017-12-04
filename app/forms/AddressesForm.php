<?php

use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\Password;
use Phalcon\Forms\Element\Select;
use Phalcon\Forms\Element\Check;
use Phalcon\Forms\Element\Hidden;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Email;

class AddressesForm extends Form
{

    public function initialize($entity = null, $options = null)
    {
        // Number
        $number = new Text('number');
        $number->setLabel('Номер на дело');
        $number->setAttributes([
            'placeholder' => 'Номер на дело'
        ]);
        $this->add($number);

        // Date
        $date = new Text('date');
        $date->setLabel('Дата');
        $date->setAttributes([
            'value' => date('d.m.Y'),
            'placeholder' => 'Дата на издаване'
        ]);
        $this->add($date);

        // Address
        $address = new Text('address');
        $address->setLabel('Адрес');
        $address->setAttributes([
            'placeholder' => 'Въведете адрес'
        ]);
        $this->add($address);

        $employee_params =  [
            'conditions'  => 'type = "'.Users::SUMMON.'"',
            'columns'     => 'id, CONCAT(first_name, " ", last_name) AS name',
            'order'       => 'name'
        ];  

        // Assigned employee
        $assign = new Select('assign',  Users::find($employee_params), array(
            'using' => array('id', 'name'),
            'useEmpty'   => true,
            'emptyText'  => 'Изберете...',
            'emptyValue' => ''
        ));
        $assign->setLabel('Призовкар');
        $this->add($assign);
    }
}