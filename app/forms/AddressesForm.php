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
        if (isset($options['edit'])) {
            $this->add(new Hidden("id"));
        }
        // Number
        $number = new Text('case_number');
        $number->setLabel('Номер на дело');
        $number->setAttributes([
            'placeholder' => 'Номер на дело'
        ]);
        $this->add($number);

        // Reference number
        $refNumber = new Text('reference_number');
        $refNumber->setLabel('Изходящ номер');
        $refNumber->setAttributes([
            'placeholder' => 'Изходящ номер'
        ]);
        $this->add($refNumber);

        // Address
        $address = new Text('address');
        $address->setLabel('Адрес*');
        $address->setAttributes([
            'placeholder' => 'Въведете адрес'
        ]);
        $address->addValidators(array(
            new PresenceOf(array(
                'message' => 'Адресът е задължително поле'
            ))
        ));
        $this->add($address);

        // Latitude
        $this->add(new Hidden('latitude'));

        // Longitude
        $this->add(new Hidden('longitude'));

        $employee_params =  [
            'conditions'  => 'type = "'.Users::SUMMON.'"',
            'columns'     => 'id, CONCAT(first_name, " ", last_name) AS name',
            'order'       => 'name'
        ];  

        // Assigned employee
        $assign = new Select('assigned_to',  Users::find($employee_params), array(
            'using' => array('id', 'name'),
            'useEmpty'   => true,
            'emptyText'  => 'Изберете...',
            'emptyValue' => ''
        ));
        $assign->setLabel('Призовкар*');
        $assign->addValidators(array(
            new PresenceOf(array(
                'message' => 'Служителят е задължително поле'
            ))
        ));
        $this->add($assign);
    }
}