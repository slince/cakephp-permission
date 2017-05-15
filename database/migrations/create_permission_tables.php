<?php
/**
 * CakePHP permission handling library
 * @author Tao <taosikai@yeah.net>
 */
use Migrations\AbstractMigration;
use Cake\Core\Configure;

class CreatePermissionTables extends AbstractMigration
{
    public function change()
    {
        //creates permissions table
        $table = $this->table(Configure::read('Permission.tableNameMap.permissions') ?: 'permissions');
        $table->addColumn('name', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => false,
        ]);
        $table->addColumn('slug', 'text', [
            'default' => null,
            'null' => false,
        ]);
        $table->addColumn('created', 'datetime', [
            'default' => null,
            'null' => false,
        ]);
        $table->addColumn('modified', 'datetime', [
            'default' => null,
            'null' => false,
        ]);
        $table->create();

        //creates roles table
        $table = $this->table(Configure::read('Permission.tableNameMap.roles') ?: 'roles');
        $table->addColumn('name', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => false,
        ]);
        $table->addColumn('slug', 'text', [
            'default' => null,
            'null' => false,
        ]);
        $table->addColumn('created', 'datetime', [
            'default' => null,
            'null' => false,
        ]);
        $table->addColumn('modified', 'datetime', [
            'default' => null,
            'null' => false,
        ]);
        $table->create();

        //creates user_roles table
        $table = $this->table(Configure::read('Permission.tableNameMap.users_roles') ?: 'users_roles', [
            'id' => false
        ]);
        $table->addColumn('user_id', 'integer', [
            'default' => null,
            'null' => false,
        ]);
        $table->addColumn('role_id', 'integer', [
            'default' => null,
            'null' => false,
        ]);
        $table->addPrimaryKey(['user_id', 'role_id']);
        $table->create();

        //creates roles_permissions table
        $table = $this->table(Configure::read('Permission.tableNameMap.roles_permissions') ?: 'roles_permissions', [
            'id' => false
        ]);
        $table->addColumn('role_id', 'integer', [
            'default' => null,
            'null' => false,
        ]);
        $table->addColumn('permission_id', 'integer', [
            'default' => null,
            'null' => false,
        ]);
        $table->addPrimaryKey(['role_id', 'permission_id']);
        $table->create();
    }
}