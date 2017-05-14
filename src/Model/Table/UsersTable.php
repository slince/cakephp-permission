<?php
/**
 * CakePHP permission handling library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\CakePermission\Model\Table;

use Cake\Core\Configure;
use Cake\ORM\Table;

class UsersTable extends Table
{
    use UsersTableTrait;

    public function initialize(array $config)
    {
        parent::initialize($config);
        $tableName = Configure::read('Permission.tableNameMap.users') ?: 'users';
        if (method_exists($this, 'setTable')) {
            $this->setTable($tableName);
        } else {
            $this->table($tableName);
        }
        $this->buildPermissionRelationship();
    }
}