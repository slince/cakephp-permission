<?php
/**
 * CakePHP permission handling library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\CakePermission\Schema;

use Cake\Database\Schema\TableSchema;

class RolesTableSchema extends TableSchema
{
    protected $_columns = [
        'name' => [
            'type' => 'string',
            'length'  => '255',
            'default' => null,
            'null' => false,
            'comment' => 'The role name'
        ],
        ''
    ];
}