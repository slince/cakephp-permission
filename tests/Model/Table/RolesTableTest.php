<?php
namespace Slince\CakePermission\Tests\Model\Table;

use Cake\Core\Configure;
use Cake\ORM\Association\BelongsToMany;
use Slince\CakePermission\TableFactory;
use Slince\CakePermission\Tests\TestCase;

class RolesTableTest extends TestCase
{
    public function testRelationship()
    {
        $roles = TableFactory::getModel('Roles');
        $this->assertInstanceOf(BelongsToMany::class, $roles->association('Permissions'));
        $this->assertInstanceOf(BelongsToMany::class, $roles->association('Users'));
    }

    public function testValidatePermission()
    {
        $roles = TableFactory::getModel('Roles');
        $role = $roles->newEntity([
            'name' => 'foo',
            'slug' => 'foo'
        ]);
        $roles->save($role);
        $role = $roles->newEntity([
            'name' => 'foo',
            'slug' => 'foo'
        ], ['validate' => 'permission']);
        $this->assertNotEmpty($role->getErrors());
    }

    public function testInitialize()
    {
        $roles = TableFactory::getModel('Roles');
        $this->assertEquals(Configure::read('Permission.tableNameMap.roles') ?: 'roles', $roles->getTable());
    }
}