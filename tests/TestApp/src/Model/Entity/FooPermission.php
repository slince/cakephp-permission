<?php
namespace TestApp\Model\Entity;

use Slince\CakePermission\Model\Entity\Permission;

class FooPermission extends Permission
{
    /**
     * The map of fields that can be saved
     * @var array
     */
    protected $_accessible = [
        'id' => false,
        '*' => true
    ];
}