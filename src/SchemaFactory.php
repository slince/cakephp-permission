<?php
/**
 * CakePHP permission handling library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\CakePermission;

use Cake\Core\Configure;
use Cake\Database\Schema\Table as TableSchema;

class SchemaFactory
{
    /**
     * Gets the users table schema
     * @return TableSchema
     */
    public static function getUsersSchema()
    {
        $schema = new TableSchema(Configure::read('Permission.tableNameMap.users') ?: 'users');

        $schema->addColumn('id', [
            'type' => 'integer',
            'autoIncrement' => true
        ])
            ->addConstraint('primary', [
                'type' => 'primary',
                'columns' => ['id']
            ]);
        $schema->addColumn('name', [
            'type' => 'string',
            'length'  => 255,
            'default' => '',
            'null' => false
        ]);
        return $schema;
    }

    /**
     * Gets the roles table schema
     * @return TableSchema
     */
    public static function getRolesSchema()
    {
        $schema = new TableSchema(Configure::read('Permission.tableNameMap.roles') ?: 'roles');

        $schema->addColumn('id', [
                'type' => 'integer',
                'autoIncrement' => true
            ])
            ->addConstraint('primary', [
                'type' => 'primary',
                'columns' => ['id']
            ]);
        $schema->addColumn('name', [
            'type' => 'string',
            'length'  => 255,
            'default' => '',
            'null' => false
        ]);
        $schema->addColumn('slug', [
            'type' => 'string',
            'length'  => 255,
            'default' => '',
            'null' => false
        ]);
        $schema->addColumn('created', [
            'type' => 'datetime',
            'default' => null,
            'null' => false
        ]);
        $schema->addColumn('modified', [
            'type' => 'datetime',
            'default' => null,
            'null' => false
        ]);
        return $schema;
    }

    /**
     * Gets the roles table schema
     * @return TableSchema
     */
    public static function getPermissionsSchema()
    {
        $schema = new TableSchema(Configure::read('Permission.tableNameMap.permissions') ?: 'permissions');

        $schema->addColumn('id', [
            'type' => 'integer',
            'autoIncrement' => true
        ])
            ->addConstraint('primary', [
                'type' => 'primary',
                'columns' => ['id']
            ]);

        $schema->addColumn('name', [
            'type' => 'string',
            'length'  => 255,
            'default' => '',
            'null' => false
        ]);
        $schema->addColumn('slug', [
            'type' => 'string',
            'length'  => 255,
            'default' => '',
            'null' => false
        ]);
        $schema->addColumn('created', [
            'type' => 'datetime',
            'default' => null,
            'null' => false
        ]);
        $schema->addColumn('modified', [
            'type' => 'datetime',
            'default' => null,
            'null' => false
        ]);
        return $schema;
    }

    /**
     * Gets the join table schema between users and roles
     * @return TableSchema
     */
    public static function getUsersRolesSchema()
    {
        $schema = new TableSchema(Configure::read('Permission.tableNameMap.users_roles') ?: 'users_roles');

        $schema->addColumn('user_id', [
            'type' => 'integer',
            'default' => null,
            'null' => false
        ]);

        $schema->addColumn('role_id', [
            'type' => 'integer',
            'default' => null,
            'null' => false
        ]);

        $schema->addConstraint('primary', [
            'type' => 'primary', 'columns' => ['user_id', 'role_id']
        ]);
        return $schema;
    }

    /**
     * Gets the join table schema between roles and permissions
     * @return TableSchema
     */
    public static function getRolesPermissionsSchema()
    {
        $schema = new TableSchema(Configure::read('Permission.tableNameMap.roles_permissions') ?: 'roles_permissions');

        $schema->addColumn('role_id', [
            'type' => 'integer',
            'default' => null,
            'null' => false
        ]);

        $schema->addColumn('permission_id', [
            'type' => 'integer',
            'default' => null,
            'null' => false
        ]);

        $schema->addConstraint('primary', [
            'type' => 'primary', 'columns' => ['role_id', 'permission_id']
        ]);
        return $schema;
    }
}