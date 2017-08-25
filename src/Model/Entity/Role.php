<?php
/**
 * CakePHP permission handling library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\CakePermission\Model\Entity;

use Cake\ORM\Entity;

class Role extends Entity implements RoleInterface
{
    use RoleTrait;

    /**
     * The map of fields that can be saved
     * @var array
     */
    protected $_accessible = [
        'id' => false,
        '*' => true
    ];

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->get('name');
    }
}