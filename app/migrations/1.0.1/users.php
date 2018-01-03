<?php 

use Phalcon\Db\Column;
use Phalcon\Db\Index;
use Phalcon\Db\Reference;
use Phalcon\Mvc\Model\Migration;

/**
 * Class UsersMigration_101
 */
class UsersMigration_101 extends Migration
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
            'users',
            $schema,
            new Index('USER_ORG', ['org'], null)
        );
        
        self::$_connection->addForeignKey(
            'users', 
            $schema,
            new Reference(
                'USER_ORG',
                [
                    'referencedTable' => 'organisations',
                    'columns' => ['org'],
                    'referencedColumns' => ['id'],
                    'onUpdate' => 'CASCADE',
                    'onDelete' => 'CASCADE'
                ]
            )
        );

        self::$_connection->insert(
            'users',
            [
                1,
                1,
                'Александър',
                'Кръстев',
                'avkrastev1@gmail.com',
                'c0bd96dc7ea4ec56741a4e07f6ce98012814d853',
                1
            ],
            [
                'org',
                'type',
                'first_name',
                'last_name',
                'email',
                'password',
                'active'
            ]
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

        $indexes = self::$_connection->describeIndexes('users');
        
        if (isset($indexes['USER_ORG'])) {
            self::$_connection->dropForeignKey('users', $schema, 'USER_ORG'); 
            self::$_connection->dropIndex('users', $schema, 'USER_ORG');
        }

        if (self::$_connection->tableExists('users', $schema)) {
            self::$_connection->dropTable('users');
        }
    }

}
