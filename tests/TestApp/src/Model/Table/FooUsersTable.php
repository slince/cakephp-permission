<?php
namespace TestApp\Model\Table;

use Cake\ORM\Table;
use Slince\CakePermission\Model\Table\UsersTableTrait;

class FooUsersTable extends Table
{
    use UsersTableTrait;

    public function initialize(array $config)
    {
        $this->setTable('foo_users');
        $this->buildPermissionRelationship();
    }
}