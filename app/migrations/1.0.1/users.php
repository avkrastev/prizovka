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
        $schema = self::$connection->getDescriptor()['dbname'];

        self::$connection->addIndex(
            'users',
            $schema,
            new Index('USER_ORG', ['org'], null)
        );

        self::$connection->addForeignKey(
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

        self::$connection->insert(
            'users',
            [
                1,
                1,
                'Александър',
                'Кръстев',
                'avkrastev@gmail.com',
                password_hash('secret', PASSWORD_DEFAULT),
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

        self::$connection->insert(
            'users',
            [
                1,
                2,
                'Иван',
                'Стоянов',
                'ivan@example.com',
                password_hash('secret', PASSWORD_DEFAULT),
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

        self::$connection->insert(
            'users',
            [
                1,
                3,
                'Димитър',
                'Христов',
                'dimitar@example.com',
                password_hash('secret', PASSWORD_DEFAULT),
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

        self::$connection->insert(
            'users',
            [
                1,
                4,
                'Йордан',
                'Милушев',
                'jordan@example.com',
                password_hash('secret', PASSWORD_DEFAULT),
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

        self::$connection->insert(
            'users',
            [
                1,
                5,
                'Стоян',
                'Колев',
                'stoyan@example.com',
                password_hash('secret', PASSWORD_DEFAULT),
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
        $schema = self::$connection->getDescriptor()['dbname'];

        $indexes = self::$connection->describeIndexes('users');

        if (isset($indexes['USER_ORG'])) {
            self::$connection->dropForeignKey('users', $schema, 'USER_ORG');
            self::$connection->dropIndex('users', $schema, 'USER_ORG');
        }

        if (self::$connection->tableExists('users', $schema)) {
            self::$connection->dropTable('users');
        }
    }

}
