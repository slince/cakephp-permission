<?php
namespace Slince\CakePermission\Tests;

use Cake\Cache\Cache;
use Cake\Database\Connection;
use Cake\Database\Schema\TableSchema;
use Cake\Datasource\ConnectionManager;
use Slince\CakePermission\Exception\RuntimeException;
use Slince\CakePermission\SchemaFactory;

class TestCase extends \PHPUnit\Framework\TestCase
{
    /**
     * @var Connection
     */
    protected static $connection;

    protected static $schemas = [];

    public static function setUpBeforeClass()
    {
        if (is_null(static::$connection)) {
            static::setUpDatabaseConnection();
        }
        static::createTables();
    }

    public static function tearDownAfterClass()
    {
        static::dropTables();
    }

    protected static function setUpDatabaseConnection()
    {
        //config cache
        $configs = [
            '_cake_core_' => [
                'engine' => 'File',
                'prefix' => 'cake_core_',
                'serialize' => true
            ],
            '_cake_model_' => [
                'engine' => 'File',
                'prefix' => 'cake_model_',
                'serialize' => true
            ],
            'default' => [
                'engine' => 'File',
                'prefix' => 'permission_',
                'serialize' => true
            ]
        ];
        Cache::setConfig($configs);

        //config database
        $config = [
            'className' => 'Cake\Database\Connection',
            'driver' => 'Cake\Database\Driver\Sqlite',
            'database' => ':memory:',
            'encoding' => 'utf8',
            'cacheMetadata' => false,
            'quoteIdentifiers' => false,
        ];
        ConnectionManager::setConfig('default', $config);

        static::$connection = ConnectionManager::get('default');
    }

    protected static function createTables()
    {
        static::$schemas = [
            SchemaFactory::getUsersSchema(),
            SchemaFactory::getRolesSchema(),
            SchemaFactory::getPermissionsSchema(),
            SchemaFactory::getUsersRolesSchema(),
            SchemaFactory::getRolesPermissionsSchema(),
        ];
        foreach (static::$schemas as $schema) {
            static::createTable($schema);
        }
    }

    protected static function dropTables()
    {
        foreach (static::$schemas as $schema) {
            static::dropTable($schema);
        }
    }

    protected static function createTable(TableSchema $schema)
    {
        $queries = $schema->createSql(static::$connection);
        foreach ($queries as $query) {
            $statement = static::$connection->prepare($query);
            if (!$statement->execute()) {
                throw new RuntimeException(sprintf('Cannot create table'));
            }
            $statement->closeCursor();
        }
    }

    protected static function dropTable(TableSchema $schema)
    {
        $sql = $schema->dropSql(static::$connection);
        foreach ($sql as $query) {
            static::$connection->execute($query);
        }
    }
}