# Laravel Service-Repository Generator

This package provides artisan commands to easily generate **Services** and **Repositories**, following the **Service-Repository Pattern**, which are not natively supported in Laravel. It helps developers maintain a clean and scalable code structure.

The code is designed so that any developer can build upon it, as it follows best practices and clean code principles.

![Descripción de la imagen](https://i.ibb.co/XtzQRsJ/Pizarra-Mapa-de-Web-Lluvia-de-Ideas-Negro-Naranja-Moderno-Profesional.png)


## Features

- Generate repository and service classes with a single command.
- Adheres to the Service-Repository Pattern.
- Automatically binds repositories to interfaces in the Laravel service container.
- Customizable templates for your own conventions.

## Requirements

- PHP >= 8.3
- Laravel >= 11
- Composer
- nette/php-generator >= 4.1

## Packagist Page
https://packagist.org/packages/josanangel/service-repository-manager

## Installation

### Install the package via Composer:

```bash
composer require josanangel/service-repository-manager:dev-master
```

## After install

### Add the following service provider to your  ``bootstrap/providers.php`` file:

````php

<?php

return [
     ...,
     josanangel\ServiceRepositoryManager\ServiceRepositoryManagerServiceProvider::class,
];
````

## Configuration

### Example .env


````dotenv
# service repository manager .env

SRM_MODULES=false

#SRM_REPOSITORIES_NAMESPACE='App\Modules\{module_name}\Repositories'
SRM_REPOSITORIES_NAMESPACE='App\Repositories'
SRM_REPOSITORIES_PATH='Repositories'
SRM_REPOSITORIES_SUFFIX='Repository'

#SRM_SERVICES_NAMESPACE='App\Modules\{module_name}\Services'
SRM_SERVICES_NAMESPACE='App\Services'
SRM_SERVICES_PATH='Services'
SRM_SERVICES_SUFFIX='Service'

````

### Publish configuration file
````bash
php artisan vendor:publish --tag=service_repository_manager_config
````
### Laravel module (Optional)

If your project follows a module structure such as https://nwidart.com/laravel-modules/v6/introduction
You need to change  `SRM_MODULES` to **true** and set a **repository and service** namespace which contains
the ``{node_modules}`` keyword to replace in execution time when commands are called.

Then the commands will need ``--module=`` option to specify where file should be located.



Example .env using module:
```dotenv
SRM_MODULES=true
SRM_REPOSITORIES_NAMESPACE='App\Modules\{module_name}\Repositories'
SRM_SERVICES_NAMESPACE='App\Modules\{module_name}\Services'
```


Commands examples using module:

```bash
php artisan make:repository User --module=User
```

```bash
php artisan make:service User --module=User
```

## Execute composer dump-autoload after that:

```bash
composer dump-auto
```

<hr>
<hr>

## Examples

### Create a UserRepository file

```bash
php artisan make:repository User
```
### Content

````php
<?php 

namespace App\Repositories;

class UserRepository
{
	public function __construct()
	{
	}
}
````

<hr>

### Create a UserService file

```bash
php artisan make:service User
```
### Output

````php
<?php 

namespace App\Services;

class UserService
{
	public function __construct()
	{
	}
}

````

<hr>

### Create a UserService file with injected dependencies

#### UserService + [ _injected_ ] AuthService

```bash
php artisan make:service User --services=auth
```
#### Output

````php
<?php 

namespace App\Services;

class UserService
{
	protected AuthService $authService;


	public function __construct(AuthService $authService)
	{
		$this->authService = $authService;
	}
}


````
````php
<?php 

namespace App\Services;

class AuthService
{
	public function __construct()
	{
	}
}

````
<hr>

#### UserService + [ _injected_ ] AuthService + [ _injected_ ] MapService + [ _injected_ ] UserRepository

```bash
php artisan make:service User --services=auth,map --repositories=auth
```
### Output

````php
<?php 

namespace App\Repositories;

class AuthRepository
{
	public function __construct()
	{
	}
}


````
````php
<?php 

namespace App\Services;

class AuthService
{
	public function __construct()
	{
	}
}

````

````php
<?php 

namespace App\Services;

class MapService
{
	public function __construct()
	{
	}
}


````
````php
<?php 

namespace App\Services;

use App\Repositories\AuthRepository;

class UserService
{
	protected AuthRepository $authRepository;
	protected AuthService $authService;
	protected MapService $mapService;


	public function __construct(AuthRepository $authRepository, AuthService $authService, MapService $mapService)
	{
		$this->authRepository = $authRepository;
		$this->authService = $authService;
		$this->mapService = $mapService;
	}
}

````
