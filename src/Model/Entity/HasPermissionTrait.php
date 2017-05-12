<?php
/**
 * CakePHP permission handling library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\CakePermission\Model\Entity;

trait HasPermissionTrait
{
    /**
     * Checks whether the role or user has the permission with the name or array of permission names
     * @param string|array $name
     * @return bool
     */
    public function hasPermission($name)
    {
        if (is_array($name)) {
            $hasPermission = true;
            foreach ($name as $permissionName) {
                $hasPermission = $hasPermission && $this->hasPermission($permissionName);
                if (!$hasPermission) {
                    break;
                }
            }
            return $hasPermission;
        } else {
            $permissions = $this->getAllPermissions();
            $hasPermission = false;
            foreach ($permissions as $permission) {
                if ($permission->get('name') == $name) {
                    $hasPermission = true;
                    break;
                }
            }
            return $hasPermission;
        }
    }

    /**
     * Checks whether the role or user has any of permissions in the specified permissions
     * @param array $names
     * @return bool
     */
    public function hasAnyPermission($names)
    {
        $hasPermission = false;
        foreach ($names as $permissionName) {
            if ($hasPermission = $this->hasPermission($permissionName)) {
                break;
            }
        }
        return $hasPermission;
    }

    /**
     * Gets all permissions
     * @return Permission[]
     */
    abstract public function getAllPermissions();
}