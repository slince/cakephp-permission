<?php
/**
 * CakePHP permission handling library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\CakePermission\Model\Entity;

use Cake\Cache\Cache;
use Cake\Core\Configure;
use Cake\ORM\Query;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Slince\CakePermission\Constants;
use Slince\CakePermission\Model\TableFactory;

trait UserTrait
{
    use HasPermissionTrait;

    /**
     * Alias of "hasAnyPermission"
     * @param $permission
     */
    public function can($permission)
    {
        $this->hasAnyPermission($permission);
    }

    /**
     * Attaches a role for the user
     * @param string|Role $role
     * @return boolean
     */
    public function attachRole($role)
    {
        $role = is_string($role) ? Role::find($role) : $role;
        return TableFactory::getUserModel()->association('Roles')->link($this, [$role]);
    }

    /**
     * Revokes the specified role from the user
     * @param string|Role $role
     * @return boolean
     */
    public function removeRole($role)
    {
        $role = is_string($role) ? Role::find($role) : $role;
        return TableFactory::getUserModel()->association('Roles')->unlink($this, [$role]);
    }

    /**
     * Removes all roles
     */
    public function removeAllRoles()
    {
        return TableFactory::getUserModel()->association('Roles')->unlink($this, $this->allRoles());
    }

    /**
     * Checks whether the user has thr role
     * @param string|Role $role
     * @return bool
     */
    public function hasRole($role)
    {
        $roleName = is_string($role) ? $role : $role->get('name');
        foreach ($this->getAllRoles() as $role) {
            if ($role->get('name') == $roleName) {
                return true;
            }
        }
        return false;
    }

    /**
     * Gets all the permissions for the user
     * @return Role[]
     */
    public function getAllPermissions()
    {
        $roles = $this->getAllRoles();
        return call_user_func_array('array_merge', array_map(function(Role $role){
            return $role->getAllPermissions();
        }, $roles));
    }

    /**
     * Gets all roles of the user
     * @return Role[]
     */
    public function getAllRoles()
    {
        $id = $this->get(TableFactory::getUserModel()->getPrimaryKey());
        return Cache::remember(sprintf(Constants::CACHE_ROLES, $id), function() use($id){
            return TableFactory::getRoleModel()->find()->matching('Users', function(Query $query) use ($id){
                return $query->where(['Users.id' => $id]);
            })->toArray();
        });
    }
}