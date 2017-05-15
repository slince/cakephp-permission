<?php
namespace TestApp\Model\Table;

use Cake\ORM\Table;
use Slince\CakePermission\Model\Table\PermissionsTableTrait;

class FooPermissionsTable extends Table
{
    use PermissionsTableTrait;

    public function initialize(array $config)
    {
        $this->setTable('foo_permissions');
        $this->buildPermissionRelationship();
    }
}