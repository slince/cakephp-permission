<?php
/**
 * CakePHP permission handling library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\CakePermission\Model\Entity;

use Cake\Cache\Cache;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\ORM\Query;
use Cake\Utility\Text;
use Slince\CakePermission\Constants;
use Slince\CakePermission\Exception\InvalidArgumentException;
use Slince\CakePermission\Model\Table\PermissionsTableTrait;
use Slince\CakePermission\TableFactory;

trait RoleTrait
{
    use HasPermissionTrait;

    /**
     * Gives the permission or an array of permissions
     * @param string|PermissionInterface|array $permission
     * @return bool
     */
    public function givePermission($permission)
    {
        $permissions = is_array($permission) ? $permission : [$permission];
        $permissions = array_map(function($permission){
            return is_string($permission) ? Permission::find($permission) : $permission;
        }, $permissions);
        $this->set('permissions', $permissions);
        $result = TableFactory::getRoleModel()->save($this) !== false;
        PermissionsTableTrait::refreshCache($this->id);
        return $result;
    }

    /**
     * Revokes the permissions of the role
     * @param string|PermissionInterface $permission
     * @return
     */
    public function revokePermission($permission)
    {
        if (is_string($permission)) {
            $permission = Permission::find($permission);
        }
        $result = TableFactory::getRoleModel()->association('Permissions')->unlink($this, [$permission]);
        PermissionsTableTrait::refreshCache($this->id);
        return $result;
    }

    /**
     * Revokes all permissions of the role
     * @return boolean
     */
    public function revokeAllPermissions()
    {
        $result = TableFactory::getRoleModel()->association('Permissions')->unlink($this,
            $this->getAllPermissions());
        PermissionsTableTrait::refreshCache($this->id);
        return $result;
    }

    /**
     * Gets all permissions for the role
     * @return PermissionInterface[]
     */
    public function getAllPermissions()
    {
        $primaryKey = TableFactory::getRoleModel()->getPrimaryKey();
        $id = $this->get($primaryKey);
        return Cache::remember(sprintf(Constants::CACHE_PERMISSIONS, $id), function() use ($id){
            return TableFactory::getPermissionModel()->find()->matching('Roles', function(Query $query) use ($id){
                return $query->where(['Roles.id' => $id]);
            })->toArray();
        });
    }

    /**
     * Finds the role by its name
     * @param string $name
     * @return RoleInterface
     */
    public static function find($name)
    {
        return TableFactory::getRoleModel()->findByName($name)->firstOrFail();
    }

    /**
     * Creates a role
     * @param $arguments
     * @return RoleInterface
     */
    public static function create($arguments)
    {
        if (is_string($arguments)) {
            $arguments = [
                'name' => $arguments
            ];
        }
        $arguments['slug'] = Text::slug($arguments['name']);
        $role = TableFactory::getRoleModel()->newEntity($arguments, ['validate' => 'permission']);
        if (TableFactory::getRoleModel()->save($role) === false) {
            throw new InvalidArgumentException('Failed to create the role');
        }
        return $role;
    }

    /**
     * Finds the role, if it does not exists, the role will be created
     * @param string $name
     * @return RoleInterface
     */
    public static function findOrCreate($name)
    {
        try {
            $role = static::find($name);
        } catch (RecordNotFoundException $exception) {
            $role = static::create($name);
        }
        return $role;
    }
}