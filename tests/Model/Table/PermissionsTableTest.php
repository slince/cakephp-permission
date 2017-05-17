<?php
namespace Slince\CakePermission\Tests\Model\Table;

use Cake\ORM\Association\BelongsToMany;
use Slince\CakePermission\TableFactory;
use Slince\CakePermission\Tests\TestCase;
use Cake\Core\Configure;

class PermissionsTableTest extends TestCase
{
    public function testRelationship()
    {
        $permissions = TableFactory::getModel('Permissions');
        $this->assertInstanceOf(BelongsToMany::class, $permissions->association('Roles'));
    }

    public function testValidatePermission()
    {
        $permissions = TableFactory::getModel('Permissions');
        $permission = $permissions->newEntity([
            'name' => 'foo',
            'slug' => 'foo'
        ]);
        $permissions->save($permission);
        $permission = $permissions->newEntity([
            'name' => 'foo',
            'slug' => 'foo'
        ], ['validate' => 'permission']);
        $this->assertNotEmpty($permission->getErrors());
    }

    public function testInitialize()
    {
        $permissions = TableFactory::getModel('Permissions');
        $this->assertEquals(Configure::read('Permission.tableNameMap.permissions') ?: 'permissions', $permissions->getTable());
    }
}