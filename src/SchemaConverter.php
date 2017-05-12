<?php
/**
 * CakePHP permission handling library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\CakePermission;

use Cake\Database\Schema\TableSchema;
use Migrations\Table;

class SchemaConverter
{
    /**
     * Converts the cake table schema to Migrations/Table
     * @param TableSchema $tableSchema
     * @return Table
     */
    public function convert(TableSchema $tableSchema)
    {

    }
}