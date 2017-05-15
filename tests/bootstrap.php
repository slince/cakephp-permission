<?php
use Cake\Core\Configure;

$findRoot = function ($root) {
    do {
        $lastRoot = $root;
        $root = dirname($root);
        if (is_dir($root . '/vendor/cakephp/cakephp')) {
            return $root;
        }
    } while ($root !== $lastRoot);
    throw new Exception('Cannot find the root of the application, unable to run tests');
};

$root = $findRoot(__FILE__);
unset($findRoot);
//chdir($root);

require_once $root . '/vendor/cakephp/cakephp/src/basics.php';
$autoLoader = require_once $root . '/vendor/autoload.php';

$autoLoader->addPsr4('TestApp\\', __DIR__ . '/TestApp/src');

define('CORE_PATH', $root . DS . 'vendor' . DS . 'cakephp' . DS . 'cakephp' . DS);
define('ROOT', __DIR__ . '/../TestApp');
define('APP_DIR', 'src');
define('APP', ROOT . 'src' . DS);
define('TMP', sys_get_temp_dir() . DS);

Configure::write('debug', true);
Configure::write('App', [
    'namespace' => 'TestApp',
    'paths' => [
        'plugins' => [ROOT . 'Plugin' . DS],
        'templates' => [ROOT . 'App' . DS . 'Template' . DS]
    ]
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


