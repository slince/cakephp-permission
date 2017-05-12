<?php
/**
 * CakePHP permission handling library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\CakePermission\Model\Table;

use Cake\Core\Configure;
use Cake\ORM\Table;

class RolesTable extends Table
{
    use RolesTableTrait;

    public function initialize(array $config)
    {
        parent::initialize($config);
        $this->setTable(Configure::read('Permission.tableNameMap.roles') ?: 'roles');
        $this->buildRelationship();
    }
}