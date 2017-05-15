<?php
namespace TestApp\Model\Table;

use Cake\ORM\Table;
use Slince\CakePermission\Model\Table\RolesTableTrait;

class FooRolesTable extends Table
{
    use RolesTableTrait;

    public function initialize(array $config)
    {
        $this->setTable('foo_roles');
        $this->buildPermissionRelationship();
    }
}