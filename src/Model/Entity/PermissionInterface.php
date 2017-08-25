<?php
/**
 * CakePHP permission handling library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\CakePermission\Model\Entity;

interface PermissionInterface
{
    /**
     * Gets the permission name
     * return string
     */
    public function getName();
}