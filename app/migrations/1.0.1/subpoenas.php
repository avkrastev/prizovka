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
        $schema = self::$_connection->getDescriptor()['dbname'];

        self::$_connection->addIndex(
            'subpoenas',
            $schema,
            new Index('ADDRESS', ['address'], null)
        );

        self::$_connection->addIndex(
            'subpoenas',
            $schema,
            new Index('ASSIGNEE', ['assigned_to'], null)
        );
        
        self::$_connection->addIndex(
            'subpoenas',
            $schema,
            new Index('SUB_CREATOR', ['created_by'], null)
        );

        self::$_connection->addIndex(
            'subpoenas',
            $schema,
            new Index('SUB_UPDATER', ['updated_by'], null)
        );

        self::$_connection->addForeignKey(
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

        self::$_connection->addForeignKey(
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
        
        self::$_connection->addForeignKey(
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

        self::$_connection->addForeignKey(
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
        $schema = self::$_connection->getDescriptor()['dbname'];

        $indexes = self::$_connection->describeIndexes('subpoenas');

        if (isset($indexes['ADDRESS'])) {
            self::$_connection->dropForeignKey('subpoenas', $schema, 'ADDRESS'); 
            self::$_connection->dropIndex('subpoenas', $schema, 'ADDRESS');
        }
        if (isset($indexes['ASSIGNEE'])) {
            self::$_connection->dropForeignKey('subpoenas', $schema, 'ASSIGNEE'); 
            self::$_connection->dropIndex('subpoenas', $schema, 'ASSIGNEE');
        }
        if (isset($indexes['SUB_CREATOR'])) {
            self::$_connection->dropForeignKey('subpoenas', $schema, 'SUB_CREATOR'); 
            self::$_connection->dropIndex('subpoenas', $schema, 'SUB_CREATOR');
        }
        if (isset($indexes['SUB_UPDATER'])) {
            self::$_connection->dropForeignKey('subpoenas', $schema, 'SUB_UPDATER'); 
            self::$_connection->dropIndex('subpoenas', $schema, 'SUB_UPDATER');
        }
    }

}
