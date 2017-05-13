<?php
/**
 * CakePHP permission handling library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\CakePermission\Model\Entity;

use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Utility\Text;
use Slince\CakePermission\Exception\InvalidArgumentException;
use Slince\CakePermission\TableFactory;

trait PermissionTrait
{
    /**
     * Gets the permission by its name
     * @param $name
     * @return Permission
     */
    public static function find($name)
    {
        return TableFactory::getPermissionModel()->findByName($name)->firstOrFail();
    }

    /**
     * Creates a role
     * @param $arguments
     * @return Permission
     */
    public static function create($arguments)
    {
        if (is_string($arguments)) {
            $arguments = [
                'name' => $arguments
            ];
        }
        $arguments['slug'] = Text::slug($arguments['name']);
        $permission = TableFactory::getPermissionModel()->newEntity($arguments, ['validate' => 'permission']);
        if (TableFactory::getPermissionModel()->save($permission) === false) {
            throw new InvalidArgumentException(sprintf('Failed to create the permission "%s"',
                $arguments['name']));
        }
        return $permission;
    }

    /**
     * Finds the permission by its name, if it does not exists, the role will be created
     * @param $name
     * @return Permission
     */
    public static function findOrCreate($name)
    {
        try {
            $permission = static::find($name);
        } catch (RecordNotFoundException $exception) {
            $permission = static::create($name);
        }
        return $permission;
    }
}