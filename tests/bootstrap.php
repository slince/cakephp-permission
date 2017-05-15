<?php
use Cake\Core\Configure;

$autoLoader = include __DIR__ .  '/../vendor/autoload.php';

$autoLoader->addPsr4('TestApp\\', __DIR__ . '/TestApp/src');

define('ROOT', __DIR__ . '/../TestApp');
define('APP_DIR', 'src');
define('APP', ROOT . 'src' . DS);
define('TMP', sys_get_temp_dir() . DS);

Configure::write('App', [
    'namespace' => 'TestApp'
]);


Configure::write('Permission', [
    'tableNameMap' => [
        /**
         * Your users table, remember to modify it
         */
        'users' => 'foo_users',

        /**
         * Your roles table;If you want to use the default configuration. you don't need to change.
         */
        'roles' => 'foo_roles',

        /**
         * Your permissions table;If you want to use the default configuration. you don't need to change.
         */
        'permissions' => 'foo_permissions',

        /**
         * The join table between users and roles;If you want to use the default configuration. you don't need to change.
         */
        'users_roles' => 'foo_users_roles',

        /**
         * The join table between roles and permissions;If you want to use the default configuration. you don't need to change.
         */
        'roles_permissions' => 'foo_roles_permissions',
    ],

    'tableClassMap' => [
        /**
         * The Users model class, remember to modify it
         */
        'Users' => \TestApp\Model\Table\FooUsersTable::class,

        /**
         * The Roles model class;If you want to use the default configuration. you don't need to change.
         */
        'Roles' => \TestApp\Model\Table\FooRolesTable::class,

        /**
         * The Permissions model class;If you want to use the default configuration. you don't need to change.
         */
        'Permissions' => \TestApp\Model\Table\FooPermissionsTable::class
    ]
]);


