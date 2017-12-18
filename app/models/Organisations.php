<?php

use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Query\Builder;

class Organisations extends Model
{
    public function initialize()
    {
        $this->hasMany('id', 'Users', 'org', [
            'alias' => 'user',
            'reusable' => true
        ]);
    }
}
