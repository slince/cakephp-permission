<?php
namespace TestApp\Model\Entity;

use Slince\CakePermission\Model\Entity\User;

class FooUser  extends User
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