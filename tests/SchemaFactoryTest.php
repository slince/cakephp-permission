<?php
namespace Slince\CakePermission\Tests;

use Cake\Database\Schema\TableSchema;
use Slince\CakePermission\SchemaFactory;

class SchemaFactoryTest extends TestCase
{
    public function testGetSchema()
    {
        $this->assertInstanceOf(TableSchema::class, SchemaFactory::getUsersSchema());
        $this->assertInstanceOf(TableSchema::class, SchemaFactory::getRolesSchema());
        $this->assertInstanceOf(TableSchema::class, SchemaFactory::getPermissionsSchema());
        $this->assertInstanceOf(TableSchema::class, SchemaFactory::getUsersRolesSchema());
        $this->assertInstanceOf(TableSchema::class, SchemaFactory::getRolesPermissionsSchema());
    }
}