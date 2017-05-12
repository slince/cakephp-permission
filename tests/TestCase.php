<?php
namespace Slince\CakePermission\Tests;

use Cake\Datasource\ConnectionManager;

class TestCase extends \PHPUnit\Framework\TestCase
{
    protected function setUpDatabaseConnection()
    {
        ConnectionManager::setConfig('default', [
            'className' => 'Cake\Database\Connection',
            'driver' => 'Cake\Database\Driver\Sqlite',
            'database' => ':memory',
            'encoding' => 'utf8',
            'cacheMetadata' => true,
            'quoteIdentifiers' => false,
        ]);
    }

    protected function resolveDatabaseSchema()
    {

    }
}