<?php 

use Phalcon\Db\Column;
use Phalcon\Db\Index;
use Phalcon\Db\Reference;
use Phalcon\Mvc\Model\Migration;

/**
 * Class OrganisationsMigration_101
 */
class OrganisationsMigration_101 extends Migration
{
    /**
     * Run the migrations
     *
     * @return void
     */
    public function up()
    {
        self::$_connection->insert(
            'organisations',
            [
                1,
                824,
                'Константин Павлов'
            ],
            [
                'id',
                'firm',
                'name'
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
        self::$_connection->execute('SET FOREIGN_KEY_CHECKS = 0');
        self::$_connection->dropTable('organisations');
        self::$_connection->execute('SET FOREIGN_KEY_CHECKS = 1');
    }

}
