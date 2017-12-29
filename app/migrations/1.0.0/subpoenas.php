<?php 

use Phalcon\Db\Column;
use Phalcon\Db\Index;
use Phalcon\Db\Reference;
use Phalcon\Mvc\Model\Migration;

/**
 * Class SubpoenasMigration_100
 */
class SubpoenasMigration_100 extends Migration
{
    /**
     * Define the table structure
     *
     * @return void
     */
    public function morph()
    {
        $this->morphTable('subpoenas', [
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
                        'address',
                        [
                            'type' => Column::TYPE_INTEGER,
                            'default' => "0",
                            'unsigned' => true,
                            'notNull' => true,
                            'size' => 10,
                            'after' => 'id'
                        ]
                    ),
                    new Column(
                        'assigned_to',
                        [
                            'type' => Column::TYPE_INTEGER,
                            'unsigned' => true,
                            'size' => 10,
                            'after' => 'address'
                        ]
                    ),
                    new Column(
                        'date',
                        [
                            'type' => Column::TYPE_DATETIME,
                            'size' => 1,
                            'after' => 'assigned_to'
                        ]
                    ),
                    new Column(
                        'action',
                        [
                            'type' => Column::TYPE_INTEGER,
                            'unsigned' => true,
                            'size' => 1,
                            'after' => 'date'
                        ]
                    ),
                    new Column(
                        'created_by',
                        [
                            'type' => Column::TYPE_INTEGER,
                            'unsigned' => true,
                            'size' => 10,
                            'after' => 'action'
                        ]
                    ),
                    new Column(
                        'created_at',
                        [
                            'type' => Column::TYPE_DATETIME,
                            'size' => 1,
                            'after' => 'created_by'
                        ]
                    ),
                    new Column(
                        'update_by',
                        [
                            'type' => Column::TYPE_INTEGER,
                            'unsigned' => true,
                            'size' => 10,
                            'after' => 'created_at'
                        ]
                    ),
                    new Column(
                        'updated_at',
                        [
                            'type' => Column::TYPE_DATETIME,
                            'size' => 1,
                            'after' => 'update_by'
                        ]
                    )
                ],
                'indexes' => [
                    new Index('PRIMARY', ['id'], 'PRIMARY'),
                    //new Index('FK_subpoenas_addresses', ['address'], null),
                    //new Index('FK_subpoenas_users', ['created_by'], null),
                    //new Index('FK_subpoenas_users_2', ['update_by'], null),
                    //new Index('FK_subpoenas_users_3', ['assigned_to'], null)
                ],
                /*'references' => [
                    new Reference(
                        'FK_subpoenas_addresses',
                        [
                            'referencedTable' => 'addresses',
                            'referencedSchema' => 'invo',
                            'columns' => ['address'],
                            'referencedColumns' => ['id'],
                            'onUpdate' => 'RESTRICT',
                            'onDelete' => 'RESTRICT'
                        ]
                    ),
                    new Reference(
                        'FK_subpoenas_users',
                        [
                            'referencedTable' => 'users',
                            'referencedSchema' => 'invo',
                            'columns' => ['created_by'],
                            'referencedColumns' => ['id'],
                            'onUpdate' => 'NO ACTION',
                            'onDelete' => 'CASCADE'
                        ]
                    ),
                    new Reference(
                        'FK_subpoenas_users_2',
                        [
                            'referencedTable' => 'users',
                            'referencedSchema' => 'invo',
                            'columns' => ['update_by'],
                            'referencedColumns' => ['id'],
                            'onUpdate' => 'NO ACTION',
                            'onDelete' => 'CASCADE'
                        ]
                    ),
                    new Reference(
                        'FK_subpoenas_users_3',
                        [
                            'referencedTable' => 'users',
                            'referencedSchema' => 'invo',
                            'columns' => ['assigned_to'],
                            'referencedColumns' => ['id'],
                            'onUpdate' => 'NO ACTION',
                            'onDelete' => 'CASCADE'
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
