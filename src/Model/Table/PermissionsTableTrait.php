<?php
/**
 * CakePHP permission handling library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\CakePermission\Model\Table;

use Cake\Cache\Cache;
use Cake\Core\Configure;
use Cake\Validation\Validator;
use Slince\CakePermission\Constants;
use Slince\CakePermission\TableFactory;

trait PermissionsTableTrait
{
    public function buildPermissionRelationship()
    {
        $this->belongsToMany('Roles', [
            'className' => TableFactory::getModelClass('Roles'),
            'foreignKey' => 'permission_id',
            'targetForeignKey' => 'role_id',
            'joinTable' => Configure::read('Permission.tableNameMap.roles_permissions') ?: 'roles_permissions',
            'saveStrategy' => 'append'
        ]);
        $this->addBehavior('Timestamp');
    }

    public function validationPermission(Validator $validator)
    {
        $validator->add('name', 'unique', [
            'rule' => 'validateUnique',
            'message' => 'The permission already exists',
            'provider' => 'table'
        ]);
        return $validator;
    }

    /**
     * Refreshes the cache
     * @param int $roleId
     */
    public static function refreshCache($roleId)
    {
        Cache::delete(sprintf(Constants::CACHE_PERMISSIONS, $roleId));
    }
}