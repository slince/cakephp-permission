<?php
/**
 * CakePHP permission handling library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\CakePermission\Model\Table;

use Cake\Validation\Validator;
use Slince\CakePermission\Constants;
use Cake\Cache\Cache;
use Cake\Core\Configure;
use Slince\CakePermission\TableFactory;

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

    public function validationPermission(Validator $validator)
    {
        $validator->add('name', 'unique', [
            'rule' => 'validateUnique',
            'message' => 'The role already exists',
            'provider' => 'table'
        ]);
        return $validator;
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