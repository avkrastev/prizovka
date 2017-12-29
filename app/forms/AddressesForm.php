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
            $this->add(new Hidden('id'));
            $this->add(new Hidden('employee'));
        }

        // Number
        $number = new Text('case_number');
        $number->setLabel('Номер на дело*');
        if (isset($options['history'])) {
            $number->setLabel('Номер на дело');
        }
        if (isset($options['search'])) {
            $number->setAttributes([
                'placeholder' => 'Номер на дело'
            ]);
        }
        $this->add($number);

        // Reference number
        $refNumber = new Text('reference_number');
        $refNumber->setLabel('Изходящ номер*');
        if (isset($options['history'])) {
            $refNumber->setLabel('Изходящ номер');
        }
        if (isset($options['search'])) {
            $refNumber->setAttributes([
                'placeholder' => 'Изходящ номер'
            ]);
        }
        $this->add($refNumber);

        // Address
        if (!isset($options['history'])) {
            $address = new Text('address');
            $address->setLabel('Адрес*');
            if (isset($options['search'])) {
                $address->setAttributes([
                    'placeholder' => 'Адрес'
                ]);
            }
            $address->addValidators(array(
                new PresenceOf(array(
                    'message' => 'Адресът е задължително поле'
                ))
            ));
            $this->add($address);
        }

        if (!isset($options['history'])) {
            // Latitude
            $this->add(new Hidden('latitude'));

            // Longitude
            $this->add(new Hidden('longitude'));
        }

        $employee_params =  [
            'conditions'  => 'type = "'.Users::SUMMON.'"',
            'columns'     => 'id, CONCAT(first_name, " ", last_name) AS name',
            'order'       => 'name'
        ];  

        if (!isset($options['search'])) {
            // Assigned employee
            $assign = new Select('assigned_to',  Users::find($employee_params), array(
                'using' => array('id', 'name'),
                'useEmpty'   => true,
                'emptyText'  => 'Изберете...',
                'emptyValue' => 0
            ));
            $assign->setLabel('Призовкар');
            $assign->addValidators(array(
                new PresenceOf(array(
                    'message' => 'Служителят е задължително поле'
                ))
            ));
            $this->add($assign);
        }
    }
}