<?php
/**
 * CakePHP permission handling library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\CakePermission\Model\Table;

use Cake\Core\Configure;
use Slince\CakePermission\TableFactory;

trait UsersTableTrait
{
    public function buildPermissionRelationship()
    {
        $this->belongsToMany('Roles', [
            'className' => TableFactory::getModelClass('Roles'),
            'foreignKey' => 'user_id',
            'targetForeignKey' => 'role_id',
            'joinTable' => Configure::read('Permission.tableNameMap.users_roles') ?: 'users_roles',
            'saveStrategy' => 'append'
        ]);
    }
}