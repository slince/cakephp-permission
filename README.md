# Permission Management For CakePHP 3.x

[![Build Status](https://img.shields.io/travis/slince/cakephp-permission/master.svg?style=flat-square)](https://travis-ci.org/slince/cakephp-permission)
[![Coverage Status](https://img.shields.io/codecov/c/github/slince/cakephp-permission.svg?style=flat-square)](https://codecov.io/github/slince/cakephp-permission)
[![Latest Stable Version](https://img.shields.io/packagist/v/slince/cakephp-permission.svg?style=flat-square&label=stable)](https://packagist.org/packages/slince/cakephp-permission)
[![Scrutinizer](https://img.shields.io/scrutinizer/g/slince/cakephp-permission.svg?style=flat-square)](https://scrutinizer-ci.com/g/slince/cakephp-permission/?branch=master)

The library provides a flexible way to add role-based access control management to CakePHP 3.x

Inspired by [Laravel Permission](https://github.com/spatie/laravel-permission)

## Quick example

```php
//Creats a role
$role = Role::create('editor');
 
//Givs a permission to the role
$role->givePermission('edit article');

//Adds the role to the user 
$user->assignRole($role); 
// You can also give it directly by its name
$user->assignRole('editor');

//Checks whether the user has the permission
var_dump($user->hasPermission('edit article')) //output "true"
```
## Installation

1. Install via composer

```bash
composer require slince/cakephp-permission
```

2. Load the plugin in `config/bootstrap.php`:

```php
// Load the plugin.
Plugin::load('Slince/CakePermission');
```
3. Add the following configuration to your `app.php`

```php
'Permission' => [

    'tableNameMap' => [
        /**
         * Your users table, remember to modify it
         */
        'users' => 'your users table name',

        /**
         * Your roles table;If you want to use the default configuration. you don't need to change.
         */
        //'roles' => 'roles',

        /**
         * Your permissions table;If you want to use the default configuration. you don't need to change.
         */
        //'permissions' => 'permissions',

        /**
         * The join table between users and roles;If you want to use the default configuration. you don't need to change.
         */
        //'users_roles' => 'users_roles',

        /**
         * The join table between roles and permissions;If you want to use the default configuration. you don't need to change.
         */
        //'roles_permissions' => 'roles_permissions',
    ],

    'tableClassMap' => [
        /**
         * The Users model class, remember to modify it
         */
        'Users' => App\Model\Table\YourUsersTable::class,

        /**
         * The Roles model class;If you want to use the default configuration. you don't need to change.
         */
        //'Roles' => Slince\CakePermission\Model\Table\RolesTable::class,

        /**
         * The Permissions model class;If you want to use the default configuration. you don't need to change.
         */
        //'Permissions' => Slince\CakePermission\Model\Table\PermissionsTable::class
    ]
]
```

4. Generate the permission migration

```bash
./cake permission_migrate
```
If ok, now run the migrate command

```bash
./cake migrations migrate
```
 
 
## Usage

### Models

Open your `User` entity, use `UserTrait` like this:

```php
namespace App\Model\Entity;

use Cake\ORM\Entity;
use Slince\CakePermission\Model\Entity\UserTrait;

class User extends Entity
{
    use UserTrait; //Use trait provied by CakePermission

    protected $_accessible = [
        '*' => true,
        'id' => false
    ];
    
    // ...
}
```

Open your `UsersTable`, use `UserTableTrait` like this:

```php
namespace App\Model\Table;

use Cake\ORM\Table;
use Slince\CakePermission\Model\Table\UsersTableTrait;

class UsersTable extends Table
{
    use UsersTableTrait;  // Use `UsersTableTrait`

    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('users');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->buildPermissionRelationship(); // Creats the relationship
    }
    
    // ...
}
```

### Using permissions

Creates the permissions with `PermissionTrait::create` or `PermissionTrait::findOrCreate`

```php
$addPermission = Permission::findOrCreate('add article');

$editPermission = Permission::create('edit article');
```

### Using roles and permissions

Creates a role to the database with the `RoleTrait::create` or `RoleTrait::findOrCreate`

```php
$role = Role::create('editor');

//You can also use the following method. 
$role = Role::findOrCreate('editor');
```

Give the permission to the role;  You must confirm that the permission exists.

```php
$role->givePermission($addPermission);
$role->givePermission($editPermission);

//You can also directly give them by thier name
$role->givePermission('add article');
$role->givePermission('edit article');
```

Gets all permissions of the role

```php
$role->getAllPermissions();
```

Checks whether the role has permission to do something:

```php
$role->hasPermission('edit article'); //true

$role->hasPermission(['edit artic;e', 'add article']); //true

$role->hasPermission(['edit article', 'drop article']); // false

$role->hasAnyPermission('edit article', 'drop article'); // true
```

Removes the permission

```php
$role->revokePermission($addPermission);
 
//Or by its name
$role->revokePermission('add article'); 

//Revokes all permissions
$role->revokeAllPermissions();
```


### User's roles and permissions

Add the role to the user:

```php
$user->assignRole($role);
```

Gets all the roles of user

```php
$user->getAllRoles();
```

Gets all permissions of user:

```php
$user->getAllPermissions();
```

Checks whether the user has permission to do something:

```php
$user->hasPermission('edit article'); //true

$user->hasPermission(['edit artic;e', 'add article']); //true

$user->hasPermission(['edit article', 'drop article']); // false

$user->hasAnyPermission('edit article', 'drop article'); // true
```

Removes the role of the user:

```php
$user->removeRole('editor');

//Or removes all roles of the user
$user->removeAllRoles(); 
```

## Extending

You can extends all existing Entity or Table. Do not forget to modify the default configuration in your `app.php`

## Requirements

- CakePHP >=3.4
- PHP 5.5.9+

## LICENSE

The MIT license. See [MIT](https://opensource.org/licenses/MIT)