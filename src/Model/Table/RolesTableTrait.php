<?php
/**
 * CakePHP permission handling library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\CakePermission\Model\Table;

use Slince\CakePermission\Constants;
use Cake\Cache\Cache;
use Cake\Core\Configure;
use Slince\CakePermission\Model\TableFactory;

trait RolesTableTrait
{
    public function buildPermissionRelationship()
    {
        $this->belongsToMany('Permissions', [
            'className' => TableFactory::getModelClass('Permissions'),
            'foreignKey' => 'role_id',
            'targetForeignKey' => 'permission_id',
            'joinTable' => Configure::read('Permission.tableNameMap.roles_permissions') ?: 'roles_permissions',
            'saveStrategy' => 'append'
        ]);
        $this->belongsToMany('Users', [
            'className' => TableFactory::getModelClass('Users'),
            'foreignKey' => 'role_id',
            'targetForeignKey' => 'user_id',
            'joinTable' => Configure::read('Permission.tableNameMap.users_roles') ?: 'users_roles',
            'saveStrategy' => 'append'
        ]);
        $this->addBehavior('Timestamp');
    }

    /**
     * Refreshes the cache
     * @param int $userId
     */
    public function refreshCache($userId)
    {
        Cache::delete(sprintf(Constants::CACHE_ROLES, $userId));
    }
}