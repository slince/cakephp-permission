<?php
/**
 * CakePHP permission handling library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\CakePermission\Model\Entity;

use Cake\Cache\Cache;
use Cake\Collection\Collection;
use Cake\ORM\Query;
use Slince\CakePermission\Constants;
use Slince\CakePermission\Model\Table\RolesTableTrait;
use Slince\CakePermission\TableFactory;

trait UserTrait
{
    use HasPermissionTrait;

    /**
     * Alias of "hasAnyPermission"
     * @param $permission
     * @return boolean
     */
    public function can($permission)
    {
        return $this->hasAnyPermission((array)$permission);
    }

    /**
     * Assigns a role or an array of roles for the user
     * @param string|Role|array $role
     * @return boolean
     */
    public function assignRole($role)
    {
        $roles = is_array($role) ? $role : [$role];
        $roles = array_map(function($role){
            return is_string($role) ? Role::find($role) : $role;
        }, $roles);
        $this->set('roles', $roles);
        $result = TableFactory::getUserModel()->save($this) !== false;
        RolesTableTrait::refreshCache($this->id);
        return $result;
    }

    /**
     * Revokes the specified role from the user
     * @param string|Role $role
     * @return boolean
     */
    public function removeRole($role)
    {
        $role = is_string($role) ? Role::find($role) : $role;
        $result = TableFactory::getUserModel()->association('Roles')->unlink($this, [$role]);
        RolesTableTrait::refreshCache($this->id);
        return $result;
    }

    /**
     * Removes all roles
     */
    public function removeAllRoles()
    {
        $result = TableFactory::getUserModel()->association('Roles')->unlink($this, $this->getAllRoles() );
        RolesTableTrait::refreshCache($this->id);
        return $result;
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
        $permissions = $roles ? call_user_func_array('array_merge', array_map(function(Role $role){
            return $role->getAllPermissions();
        }, $roles)) : [];
        return (new Collection($permissions))->combine('slug', function($permission){
            return $permission;
        })->toArray();
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