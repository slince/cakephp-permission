<?php
namespace Slince\CakePermission\Tests\Model\Table;

use Cake\ORM\Association\BelongsToMany;
use Slince\CakePermission\TableFactory;
use Slince\CakePermission\Tests\TestCase;

class UsersTableTest extends TestCase
{
    public function testRelationship()
    {
        $users = TableFactory::getModel('Users');
        $this->assertInstanceOf(BelongsToMany::class, $users->association('Roles'));
    }
}