<?php
namespace Slince\CakePermission\Tests\Model;

use Cake\Core\Configure;
use PHPUnit\Framework\TestCase;
use Slince\CakePermission\Model\Table\PermissionsTable;
use Slince\CakePermission\Model\Table\RolesTable;
use Slince\CakePermission\Model\Table\UsersTable;
use Slince\CakePermission\Model\TableFactory;

class TableFactoryTest extends TestCase
{
    public function testGetModelClass()
    {
        $this->assertEquals(UsersTable::class, TableFactory::getModelClass('Users'));
        $this->assertEquals(RolesTable::class, TableFactory::getModelClass('Roles'));
        $this->assertEquals(PermissionsTable::class, TableFactory::getModelClass('Permissions'));

        Configure::write('Permission.tableClassMap', [
            'Users' => 'App\Model\Table\YourUsersTable',
            'Roles' => 'App\Model\Table\YourRolesTable',
            'Permissions' => 'App\Model\Table\YourRolesTable',
        ]);
        $this->assertEquals('App\Model\Table\YourUsersTable', TableFactory::getModelClass('Users'));
        $this->assertEquals('App\Model\Table\YourRolesTable', TableFactory::getModelClass('Roles'));
        $this->assertEquals('App\Model\Table\YourRolesTable', TableFactory::getModelClass('Permissions'));
    }

    public function testGetModelInstance()
    {

    }
}