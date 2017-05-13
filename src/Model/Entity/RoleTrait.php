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
use Slince\CakePermission\TableFactory;

trait RoleTrait
{
    use HasPermissionTrait;

    /**
     * Gives a permission
     * @param string|Permission $permission
     * @return bool
     */
    public function givePermission($permission)
    {
        if (is_string($permission)) {
            $permission = Permission::find($permission);
        }
        return TableFactory::getRoleModel()->association('Permissions')->link($this, [$permission]);
    }

    /**
     * Revokes the permissions of the role
     * @param $permission
     * @return
     */
    public function revokePermission($permission)
    {
        if (is_string($permission)) {
            $permission = Permission::find($permission);
        }
        return TableFactory::getRoleModel()->association('Permissions')->unlink($this, [$permission]);
    }

    /**
     * Revokes all permissions of the role
     * @return boolean
     */
    public function revokeAllPermissions()
    {
        return TableFactory::getRoleModel()->association('Permissions')->unlink($this, $this->getAllPermissions());
    }

    /**
     * Gets all permissions for the role
     * @return Permission[]
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
     * @return RoleTrait
     */
    public static function find($name)
    {
        return TableFactory::getRoleModel()->findByName($name)->firstOrFail();
    }

    /**
     * Creates a role
     * @param $arguments
     * @return RoleTrait
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
     * @param $name
     * @return RoleTrait
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