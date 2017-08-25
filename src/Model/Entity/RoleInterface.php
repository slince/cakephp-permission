<?php
/**
 * CakePHP permission handling library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\CakePermission\Model\Entity;

interface RoleInterface
{
    /**
     * Gets the role name
     * @return string
     */
    public function getName();

    /**
     * Gives the permission or an array of permissions
     * @param string|Permission|array $permission
     * @return bool
     */
    public function givePermission($permission);

    /**
     * Revokes the permissions of the role
     * @param $permission
     * @return
     */
    public function revokePermission($permission);

    /**
     * Revokes all permissions of the role
     * @return boolean
     */
    public function revokeAllPermissions();

    /**
     * Gets all permissions for the role
     * @return Permission[]
     */
    public function getAllPermissions();

    /**
     * Finds the role by its name
     * @param string $name
     * @return RoleTrait
     */
    public static function find($name);
}