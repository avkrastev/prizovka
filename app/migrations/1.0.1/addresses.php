<?php

use Phalcon\Db\Column;
use Phalcon\Db\Index;
use Phalcon\Db\Reference;
use Phalcon\Mvc\Model\Migration;

/**
 * Class AddressesMigration_101
 */
class AddressesMigration_101 extends Migration
{
    /**
     * Run the migrations
     *
     * @return void
     */
    public function up()
    {
        $schema = self::$connection->getDescriptor()['dbname'];

        self::$connection->addIndex(
            'addresses',
            $schema,
            new Index('ADD_CREATOR', ['created_by'], null)
        );

        self::$connection->addIndex(
            'addresses',
            $schema,
            new Index('ADD_UPDATER', ['updated_by'], null)
        );

        self::$connection->addForeignKey(
            'addresses',
            $schema,
            new Reference(
                'ADD_CREATOR',
                [
                    'referencedTable' => 'users',
                    'columns' => ['created_by'],
                    'referencedColumns' => ['id'],
                    'onUpdate' => 'CASCADE',
                    'onDelete' => 'CASCADE'
                ]
            )
        );

        self::$connection->addForeignKey(
            'addresses',
            $schema,
            new Reference(
                'ADD_UPDATER',
                [
                    'referencedTable' => 'users',
                    'columns' => ['updated_by'],
                    'referencedColumns' => ['id'],
                    'onUpdate' => 'CASCADE',
                    'onDelete' => 'CASCADE'
                ]
            )
        );
    }

    /**
     * Reverse the migrations
     *
     * @return void
     */
    public function down()
    {
        $schema = self::$connection->getDescriptor()['dbname'];

        $indexes = self::$connection->describeIndexes('addresses');

        if (isset($indexes['ADD_CREATOR'])) {
            self::$connection->dropForeignKey('addresses', $schema, 'ADD_CREATOR');
            self::$connection->dropIndex('addresses', $schema, 'ADD_CREATOR');
        }
        if (isset($indexes['ADD_UPDATER'])) {
            self::$connection->dropForeignKey('addresses', $schema, 'ADD_UPDATER');
            self::$connection->dropIndex('addresses', $schema, 'ADD_UPDATER');
        }
    }

}
