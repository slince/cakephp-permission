<?php
namespace Slince\CakePermission\Tests\Model\Entity;

use Cake\ORM\Query;
use Slince\CakePermission\Model\Entity\Permission;
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

    protected static $addArticle;

    protected static $editArticle;

    protected static $dropArticle;

    protected static $adminRole;

    protected static $editorRole;

    protected static $guestRole;

    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();
        static::$user = TableFactory::getUserModel()->newEntity([
            'name' => 'foo'
        ]);
        static::$admin = TableFactory::getUserModel()->newEntity([
            'name' => 'bar'
        ]);
        TableFactory::getUserModel()->save(static::$user );
        TableFactory::getUserModel()->save( static::$admin );

        static::$addArticle = Permission::create('add article');
        static::$editArticle = Permission::create('edit article');
        static::$dropArticle = Permission::create('drop article');

        static::$adminRole = Role::create('admin');
        static::$editorRole = Role::create('editor');
        static::$guestRole = Role::create('guest');

        static::$adminRole->givePermission(static::$addArticle);
        static::$adminRole->givePermission(static::$editArticle);
        static::$adminRole->givePermission(static::$dropArticle);

//        static::$editorRole->givePermission(static::$addArticle);
//        static::$editorRole->givePermission(static::$editArticle);
    }


    public function testAssignRole()
    {
        $this->assertTrue(static::$user->assignRole(static::$editorRole));
        $this->assertTrue(static::$user->assignRole('guest'));
    }

    /**
     * @depends testAssignRole
     */
    public function testHasRole()
    {
        $this->assertTrue(static::$user->hasRole('editor'));
        $this->assertTrue(static::$user->hasRole(static::$guestRole));
        $this->assertFalse(static::$user->hasRole('admin'));
    }

    /**
     * @depends testAssignRole
     */
    public function testGetAllRoles()
    {
        $this->assertCount(2, static::$user->getAllRoles());
        static::$user->assignRole(static::$adminRole);
        $this->assertCount(3, static::$user->getAllRoles());
    }

    /**
     * @depends testGetAllRoles
     */
    public function testRemoveRole()
    {
        static::$user->removeRole(static::$adminRole);
        $this->assertCount(2, static::$user->getAllRoles());
        static::$user->removeRole('editor');
        $this->assertCount(1, static::$user->getAllRoles());
        static::$user->removeAllRoles();
        $this->assertCount(0, static::$user->getAllRoles());
    }

    /**
     * @depends testRemoveRole
     */
    public function testGetAllPermissions()
    {
        $this->assertCount(0, static::$user->getAllPermissions());
        $this->assertTrue(static::$user->assignRole(static::$adminRole));
        $this->assertCount(2, static::$user->getAllPermissions());
    }

    /**
     * @depends testGetAllPermissions
     */
    public function testHasPermission()
    {
        $this->assertTrue(static::$user->hasPermission('add article'));
        $this->assertTrue(static::$user->hasPermission(['add article', 'edit article']));
        $this->assertFalse(static::$user->hasPermission(['add article', 'edit article', 'drop article']));
        $this->assertTrue(static::$user->hasAnyPermission(['add article', 'edit article', 'drop article']));

        static::$user->assignRole(static::$adminRole);
        $this->assertTrue(static::$user->hasPermission(['add article', 'edit article', 'drop article']));

        $this->assertTrue(static::$user->can('add article'));
        $this->assertTrue(static::$user->can(['add article', 'edit article']));
        $this->assertTrue(static::$user->can(['add article', 'edit article', 'drop article']));
    }
}