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
        Cache::config([
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
        ]);
        ConnectionManager::setConfig('default', [
            'className' => 'Cake\Database\Connection',
            'driver' => 'Cake\Database\Driver\Sqlite',
            'database' => ':memory:',
            'encoding' => 'utf8',
            'cacheMetadata' => false,
            'quoteIdentifiers' => false,
        ]);
        static::$connection = ConnectionManager::get('default');
    }

    protected static function createTables()
    {
        static::createTable(SchemaFactory::getUsersSchema());
        static::createTable(SchemaFactory::getRolesSchema());
        static::createTable(SchemaFactory::getPermissionsSchema());
        static::createTable(SchemaFactory::getUsersRolesSchema());
        static::createTable(SchemaFactory::getRolesPermissionsSchema());
    }

    protected static function dropTables()
    {
        static::dropTable(SchemaFactory::getUsersSchema());
        static::dropTable(SchemaFactory::getRolesSchema());
        static::dropTable(SchemaFactory::getPermissionsSchema());
        static::dropTable(SchemaFactory::getUsersRolesSchema());
        static::dropTable(SchemaFactory::getRolesPermissionsSchema());
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