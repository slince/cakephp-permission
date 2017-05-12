<?php
/**
 * CakePHP permission handling library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\CakePermission\Model\Entity;

use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Utility\Text;
use Slince\CakePermission\Exception\InvalidArgumentException;
use Slince\CakePermission\Model\TableFactory;

trait PermissionTrait
{
    /**
     * Gets the permission by its name
     * @param $name
     * @return PermissionTrait
     */
    public static function find($name)
    {
        return TableFactory::getPermissionModel()->findByName($name)->firstOrFail();
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
        $permission = TableFactory::getPermissionModel()->newEntity($arguments);
        if (TableFactory::getPermissionModel()->save($permission) === false) {
            throw new InvalidArgumentException(sprintf('Failed to create the permission "%s"',
                $permission->get('name'))
            );
        }
        return $permission;
    }

    /**
     * Finds the permission by its name, if it does not exists, the role will be created
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