<?php

use Phalcon\Db\Column;
use Phalcon\Db\Index;
use Phalcon\Db\Reference;
use Phalcon\Mvc\Model\Migration;

/**
 * Class SubpoenasMigration_101
 */
class SubpoenasMigration_101 extends Migration
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
            'subpoenas',
            $schema,
            new Index('ADDRESS', ['address'], null)
        );

        self::$connection->addIndex(
            'subpoenas',
            $schema,
            new Index('ASSIGNEE', ['assigned_to'], null)
        );

        self::$connection->addIndex(
            'subpoenas',
            $schema,
            new Index('SUB_CREATOR', ['created_by'], null)
        );

        self::$connection->addIndex(
            'subpoenas',
            $schema,
            new Index('SUB_UPDATER', ['updated_by'], null)
        );

        self::$connection->addForeignKey(
            'subpoenas',
            $schema,
            new Reference(
                'ADDRESS',
                [
                    'referencedTable' => 'addresses',
                    'columns' => ['address'],
                    'referencedColumns' => ['id'],
                    'onUpdate' => 'CASCADE',
                    'onDelete' => 'CASCADE'
                ]
            )
        );

        self::$connection->addForeignKey(
            'subpoenas',
            $schema,
            new Reference(
                'ASSIGNEE',
                [
                    'referencedTable' => 'users',
                    'columns' => ['assigned_to'],
                    'referencedColumns' => ['id'],
                    'onUpdate' => 'CASCADE',
                    'onDelete' => 'CASCADE'
                ]
            )
        );

        self::$connection->addForeignKey(
            'subpoenas',
            $schema,
            new Reference(
                'SUB_CREATOR',
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
            'subpoenas',
            $schema,
            new Reference(
                'SUB_UPDATER',
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

        $indexes = self::$connection->describeIndexes('subpoenas');

        if (isset($indexes['ADDRESS'])) {
            self::$connection->dropForeignKey('subpoenas', $schema, 'ADDRESS');
            self::$connection->dropIndex('subpoenas', $schema, 'ADDRESS');
        }
        if (isset($indexes['ASSIGNEE'])) {
            self::$connection->dropForeignKey('subpoenas', $schema, 'ASSIGNEE');
            self::$connection->dropIndex('subpoenas', $schema, 'ASSIGNEE');
        }
        if (isset($indexes['SUB_CREATOR'])) {
            self::$connection->dropForeignKey('subpoenas', $schema, 'SUB_CREATOR');
            self::$connection->dropIndex('subpoenas', $schema, 'SUB_CREATOR');
        }
        if (isset($indexes['SUB_UPDATER'])) {
            self::$connection->dropForeignKey('subpoenas', $schema, 'SUB_UPDATER');
            self::$connection->dropIndex('subpoenas', $schema, 'SUB_UPDATER');
        }
    }

}
