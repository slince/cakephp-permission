<?php
namespace Slince\CakePermission\Tests\Model\Entity;

use Slince\CakePermission\Model\Entity\Role;
use Slince\CakePermission\Model\Entity\User;
use Slince\CakePermission\TableFactory;
use Slince\CakePermission\Tests\TestCase;

class UserTest extends TestCase
{
    /**
     * @var User
     */
    protected static $user;

    /**
     * @var User
     */
    protected static $admin;

    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();
        static::$user = TableFactory::getUserModel()->newEntity([
            'name' => 'foo'
        ]);
        static::$admin = TableFactory::getUserModel()->newEntity([
            'name' => 'admin'
        ]);
        TableFactory::getUserModel()->save(static::$user );
        TableFactory::getUserModel()->save( static::$admin );
    }


    public function testAssignRole()
    {
        $role = Role::create('foo');
        Role::create('bar');
        $this->assertTrue(static::$user->assignRole($role));
        $this->assertTrue(static::$user->assignRole('bar'));
    }

    /**
     * @depends testAssignRole
     */
    public function testGetRoles()
    {
        $this->assertCount(2, static::$user->getAllRoles());
        $role = Role::create('baz');
        static::$user->assignRole($role);
        $this->assertCount(3, static::$user->getAllRoles());
    }

    /**
     * @depends testGetRoles
     */
    public function testRemoveRole()
    {
        $role = Role::find('foo');
        static::$user->removeRole($role);
        $this->assertCount(2, static::$user->getAllRoles());
        static::$user->removeRole('bar');
        $this->assertCount(1, static::$user->getAllRoles());
        static::$user->removeAllRoles();
        $this->assertCount(0, static::$user->getAllRoles());
    }
}