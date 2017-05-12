<?php
/**
 * CakePHP permission handling library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\CakePermission\Model;

use Cake\Core\Configure;
use Cake\ORM\TableRegistry;
use Slince\CakePermission\Model\Table\UsersTable;
use Slince\CakePermission\Model\Table\RolesTable;
use Slince\CakePermission\Model\Table\PermissionsTable;
use Cake\ORM\Table;

class TableFactory
{
    /**
     * Array of default models classes
     * @var array
     */
    protected static $defaultModelClasses = [
        'Users' => UsersTable::class,
        'Roles' => RolesTable::class,
        'Permissions' => PermissionsTable::class
    ];

    /**
     * Gets the role model
     * @return Table
     */
    public static function getRoleModel()
    {
        return static::getModel('Roles');
    }

    /**
     * Gets the permission model
     * @return Table
     */
    public static function getPermissionModel()
    {
        return static::getModel('Permissions');
    }

    /**
     * Gets the user model
     * @return Table
     */
    public static function getUserModel()
    {
        return static::getModel('Users');
    }

    /**
     * Gets the model instance
     * @param $name
     * @return Table
     */
    public static function getModel($name)
    {
        return TableRegistry::get($name, [
            'className' => static::getModelClass($name)
        ]);
    }

    /**
     * Gets the default model class
     * @param string $name
     * @return string
     */
    public static function getModelClass($name)
    {
        return Configure::read("Permission.tableClassMap.{$name}") ?: static::$defaultModelClasses[$name];
    }
}