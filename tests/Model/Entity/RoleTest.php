<?php
namespace Slince\CakePermission\Tests\Model\Entity;

use Cake\Datasource\Exception\RecordNotFoundException;
use Slince\CakePermission\Exception\InvalidArgumentException;
use Slince\CakePermission\Model\Entity\Permission;
use Slince\CakePermission\Model\Entity\Role;
use Slince\CakePermission\Tests\TestCase;

class RoleTest extends TestCase
{
    public function testCreate()
    {
        $permission = Role::create('foo');
        $this->assertInstanceOf(Role::class, $permission);
        $this->assertGreaterThan(0, $permission->id);
    }

    /**
     * @depends testCreate
     */
    public function testRepeatCreate()
    {
        $this->expectException(InvalidArgumentException::class);
        Role::create('foo');
    }

    /**
     * @depends testCreate
     */
    public function testFind()
    {
        $permission = Role::find('foo');
        $this->assertEquals('foo', $permission->name);
        $this->expectException(RecordNotFoundException::class);
        Role::find('not-exists-Role');
    }

    public function testFindOrCreate()
    {
        $permission = Role::findOrCreate('not-exists-Role');
        $this->assertEquals('not-exists-Role', $permission->name);
    }

    public function testGivePermission()
    {
        $role = Role::findOrCreate('editor');
        $addPermission = Permission::findOrCreate('add article');
        Permission::create('edit article');

        $this->assertTrue($role->givePermission($addPermission));
        $this->assertTrue($role->givePermission('edit article'));

        $this->expectException(RecordNotFoundException::class);
        $role->givePermission('not-exists-permission');
    }

    /**
     * @depends testGivePermission
     */
    public function testHasPermission()
    {
        $role = Role::find('editor');
        $this->assertTrue($role->hasPermission('add article'));
        $this->assertTrue($role->hasPermission(['add article', 'edit article']));
        $this->assertFalse($role->hasPermission(['add article', 'edit article', 'not-exists-permission']));
        $this->assertTrue($role->hasAnyPermission(['add article', 'edit article', 'not-exists-permission']));
    }

    /**
     * @depends testGivePermission
     */
    public function testGetPermissions()
    {
        $role = Role::find('editor');
        $this->assertCount(2, $role->getAllPermissions());
        $role->givePermission(Permission::create('drop article'));
        $this->assertCount(3, $role->getAllPermissions());
    }
}