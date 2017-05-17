<?php
namespace Slince\CakePermission\Tests\Model\Table;

use Cake\ORM\Association\BelongsToMany;
use Slince\CakePermission\TableFactory;
use Slince\CakePermission\Tests\TestCase;
use Cake\Core\Configure;

class UsersTableTest extends TestCase
{
    public function testRelationship()
    {
        $users = TableFactory::getModel('Users');
        $this->assertInstanceOf(BelongsToMany::class, $users->association('Roles'));
    }

    public function testInitialize()
    {
        $users = TableFactory::getModel('Users');
        $this->assertEquals(Configure::read('Permission.tableNameMap.users') ?: 'users', $users->getTable());
    }
}