<?php 

use Phalcon\Db\Column;
use Phalcon\Db\Index;
use Phalcon\Db\Reference;
use Phalcon\Mvc\Model\Migration;

/**
 * Class AddressesMigration_100
 */
class AddressesMigration_100 extends Migration
{
    /**
     * Define the table structure
     *
     * @return void
     */
    public function morph()
    {
        $this->morphTable('addresses', [
                'columns' => [
                    new Column(
                        'id',
                        [
                            'type' => Column::TYPE_INTEGER,
                            'unsigned' => true,
                            'notNull' => true,
                            'autoIncrement' => true,
                            'size' => 10,
                            'first' => true
                        ]
                    ),
                    new Column(
                        'case_number',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'size' => 50,
                            'after' => 'id'
                        ]
                    ),
                    new Column(
                        'reference_number',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'size' => 50,
                        ]
                    ),
                    new Column(
                        'address',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'notNull' => true,
                            'size' => 255,
                        ]
                    ),
                    new Column(
                        'latitude',
                        [
                            'type' => Column::TYPE_DOUBLE,
                            'unsigned' => true,
                            'notNull' => true,
                        ]
                    ),
                    new Column(
                        'longitude',
                        [
                            'type' => Column::TYPE_DOUBLE,
                            'unsigned' => true,
                            'notNull' => true,
                        ]
                    ),
                    new Column(
                        'delivered',
                        [
                            'type' => Column::TYPE_CHAR,
                            'default' => "N",
                            'size' => 1,
                        ]
                    ),
                    new Column(
                        'updated_by',
                        [
                            'type' => Column::TYPE_INTEGER,
                            'unsigned' => true,
                            'size' => 10,
                        ]
                    ),
                    new Column(
                        'updated_at',
                        [
                            'type' => Column::TYPE_DATETIME,
                            'size' => 1,
                        ]
                    ),
                    new Column(
                        'created_by',
                        [
                            'type' => Column::TYPE_INTEGER,
                            'unsigned' => true,
                            'size' => 10,
                        ]
                    ),
                    new Column(
                        'created_at',
                        [
                            'type' => Column::TYPE_DATETIME,
                            'size' => 1,
                        ]
                    )
                ],
                'indexes' => [
                    new Index('PRIMARY', ['id'], 'PRIMARY'),
                    new Index('reference_number', ['reference_number'], 'UNIQUE'),
                    //new Index('FK_addresses_users_2', ['updated_by'], null),
                    //new Index('FK_addresses_users_3', ['created_by'], null)
                ],
                /*'references' => [
                    new Reference(
                        'FK_addresses_users_2',
                        [
                            'referencedTable' => 'users',
                            'referencedSchema' => 'invo',
                            'columns' => ['updated_by'],
                            'referencedColumns' => ['id'],
                            'onUpdate' => 'RESTRICT',
                            'onDelete' => 'RESTRICT'
                        ]
                    ),
                    new Reference(
                        'FK_addresses_users_3',
                        [
                            'referencedTable' => 'users',
                            'referencedSchema' => 'invo',
                            'columns' => ['created_by'],
                            'referencedColumns' => ['id'],
                            'onUpdate' => 'RESTRICT',
                            'onDelete' => 'RESTRICT'
                        ]
                    )
                ],*/
                'options' => [
                    'TABLE_TYPE' => 'BASE TABLE',
                    'AUTO_INCREMENT' => '1',
                    'ENGINE' => 'InnoDB',
                    'TABLE_COLLATION' => 'utf8_general_ci'
                ],
            ]
        );
    }

    /**
     * Run the migrations
     *
     * @return void
     */
    public function up()
    {

    }

    /**
     * Reverse the migrations
     *
     * @return void
     */
    public function down()
    {

    }

}
