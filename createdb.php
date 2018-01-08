<?php
/**
 * Programmatically bootstrap the database
 *
 * @var \Phalcon\Db\AdapterInterface $connection
 */

use Phalcon\Exception;
use Phalcon\Db\Column;
use Phalcon\Db\Index;
use Phalcon\Db\Reference;
use Phalcon\Config\Adapter\Ini as IniConfig;
use Phalcon\Config;

try {
    $configFile = __DIR__ . '/app/config/config.ini';
    if (!is_file($configFile)) {
        throw new Exception(
            sprintf('Unable to read config file located at %s.', $configFile)
        );
    }

    $config = new IniConfig($configFile);

    /** @var \Phalcon\Config $config */
    $config = $config->get('database');

    if (!$config instanceof Config) {
        throw new Exception('Unable to read database config.');
    }

    $dbClass = sprintf('\Phalcon\Db\Adapter\Pdo\%s', $config->get('adapter', 'MySql'));

    if (!class_exists($dbClass)) {
        throw new Exception(
            sprintf('PDO adapter "%s" not found.', $dbClass)
        );
    }

    $dbConfig = $config->toArray();
    unset($dbConfig['adapter']);

    $connection = new $dbClass($dbConfig);

    $connection->begin();

    $connection->createTable(
        'organisations',
        null,
        [
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
                    'firm',
                    [
                        'type' => Column::TYPE_INTEGER,
                        'default' => "0",
                        'notNull' => true,
                        'size' => 4,
                        'after' => 'id'
                    ]
                ),
                new Column(
                    'name',
                    [
                        'type' => Column::TYPE_VARCHAR,
                        'default' => "0",
                        'notNull' => true,
                        'size' => 50,
                        'after' => 'firm'
                    ]
                ),
                new Column(
                    'updated_at',
                    [
                        'type' => Column::TYPE_DATETIME,
                        'size' => 1,
                        'after' => 'name'
                    ]
                ),
                new Column(
                    'updated_by',
                    [
                        'type' => Column::TYPE_INTEGER,
                        'size' => 11,
                        'after' => 'updated_at'
                    ]
                ),
                new Column(
                    'created_at',
                    [
                        'type' => Column::TYPE_DATETIME,
                        'size' => 1,
                        'after' => 'updated_by'
                    ]
                ),
                new Column(
                    'created_by',
                    [
                        'type' => Column::TYPE_INTEGER,
                        'size' => 11,
                        'after' => 'created_at'
                    ]
                )
            ],
            'indexes' => [
                new Index('PRIMARY', ['id'], 'PRIMARY')
            ],
            'options' => [
                'TABLE_TYPE' => 'BASE TABLE',
                'AUTO_INCREMENT' => '1',
                'ENGINE' => 'InnoDB',
                'TABLE_COLLATION' => 'utf8_general_ci'
            ],
        ]
    );

    $connection->createTable(
        'users',
        null,
        [
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
                    'org',
                    [
                        'type' => Column::TYPE_INTEGER,
                        'unsigned' => true,
                        'notNull' => true,
                        'size' => 10,
                        'after' => 'id'
                    ]
                ),
                new Column(
                    'type',
                    [
                        'type' => Column::TYPE_INTEGER,
                        'unsigned' => true,
                        'size' => 1,
                        'after' => 'org'
                    ]
                ),
                new Column(
                    'first_name',
                    [
                        'type' => Column::TYPE_VARCHAR,
                        'size' => 70,
                        'after' => 'type'
                    ]
                ),
                new Column(
                    'last_name',
                    [
                        'type' => Column::TYPE_VARCHAR,
                        'size' => 70,
                        'after' => 'first_name'
                    ]
                ),
                new Column(
                    'email',
                    [
                        'type' => Column::TYPE_VARCHAR,
                        'notNull' => true,
                        'size' => 70,
                        'after' => 'last_name'
                    ]
                ),
                new Column(
                    'password',
                    [
                        'type' => Column::TYPE_VARCHAR,
                        'notNull' => true,
                        'size' => 70,
                        'after' => 'email'
                    ]
                ),
                new Column(
                    'active',
                    [
                        'type' => Column::TYPE_CHAR,
                        'default' => "1",
                        'notNull' => true,
                        'size' => 1,
                        'after' => 'password'
                    ]
                ),
                new Column(
                    'created_by',
                    [
                        'type' => Column::TYPE_INTEGER,
                        'size' => 11,
                        'after' => 'active'
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
                    'updated_by',
                    [
                        'type' => Column::TYPE_INTEGER,
                        'size' => 11,
                        'after' => 'created_at'
                    ]
                ),
                new Column(
                    'updated_at',
                    [
                        'type' => Column::TYPE_DATETIME,
                        'size' => 1,
                        'after' => 'updated_by'
                    ]
                )
            ],
            'indexes' => [
                new Index('PRIMARY', ['id'], 'PRIMARY'),
                new Index('UNIQUE', ['email'], 'UNIQUE'),
            ],
            'options' => [
                'TABLE_TYPE' => 'BASE TABLE',
                'AUTO_INCREMENT' => '1',
                'ENGINE' => 'InnoDB',
                'TABLE_COLLATION' => 'utf8_general_ci'
            ],
        ]
    );

    $connection->createTable(
        'addresses',
        null,
        [
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
                new Index('REFERENCE_NUMBER', ['reference_number'], 'UNIQUE'),
            ],
            'options' => [
                'TABLE_TYPE' => 'BASE TABLE',
                'AUTO_INCREMENT' => '1',
                'ENGINE' => 'InnoDB',
                'TABLE_COLLATION' => 'utf8_general_ci'
            ],
        ]
    );

    $connection->createTable(
        'subpoenas',
        null,
        [
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
                    'updated_by',
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
                new Index('PRIMARY', ['id'], 'PRIMARY')
            ],
            'options' => [
                'TABLE_TYPE' => 'BASE TABLE',
                'AUTO_INCREMENT' => '1',
                'ENGINE' => 'InnoDB',
                'TABLE_COLLATION' => 'utf8_general_ci'
            ],
        ]
    );

    $connection->commit();

} catch (\Exception $e) {

    if ($connection->isUnderTransaction()) {
        $connection->rollback();
    }

    echo $e->getMessage(), PHP_EOL;
    echo $e->getTraceAsString(), PHP_EOL;
}
