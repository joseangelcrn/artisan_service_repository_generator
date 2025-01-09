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

- PHP 8.0 - 8.4
- Composer
- nette/php-generator >= 4.1

## Packagist Page
https://packagist.org/packages/josanangel/service-repository-manager

## Installation

### Install the package via Composer:

```bash
composer require josanangel/service-repository-manager
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

## Command parameter details


| Command                      | Parameter               | Type       | Description                                                                                              | Required |
|------------------------------|-------------------------|------------|----------------------------------------------------------------------------------------------------------|----------|
| `php artisan make:repository` | `name`                 | Argument   | Name of the repository you want to create.                                                              | Yes      |
|                              | `--module`             | Option     | Specifies where the file(s) will be generated.                                                          | No       |
|                              | `--crud`               | Option     | Specifies if the repository should include predefined CRUD functions.                                   | No       |
| `php artisan make:service`    | `name`                 | Argument   | Name of the service you want to create.                                                                 | Yes      |
|                              | `--module`             | Option     | Specifies where the file(s) will be generated.                                                          | No       |
|                              | `--repositories`       | Option     | Indicates possible repositories to declare as injected dependencies in the main service file.           | No       |
|                              | `--services`           | Option     | Indicates possible services to declare as injected dependencies in the main service file.               | No       |
|                              | `--repositories-crud`  | Option     | Indicates repositories to declare as injected dependencies, including predefined CRUD functions.        | No       |

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

### Create a UserRepository file with CRUD methods

```bash
php artisan make:repository User --crud
```
### Content

````php
<?php 

namespace App\Repositories;

class UserRepository
{
	protected $model;


	public function __construct()
	{
	}


	public function index()
	{
		$this->model->all();
	}


	public function store($data)
	{
		$this->model->create($data);
	}


	public function show($id)
	{
		$this->model->findById($id);
	}


	public function update($id, $data)
	{
		$this->model->where("id",$id)->update($data);
	}


	public function destroy($id)
	{
		$this->model->where("id",$id)->delete();
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
#### UserService  + [ _injected_ ] UserRepository with CRUD methods

```bash
php artisan make:service User --repositories-crud=User
```

````php
<?php 

namespace App\Repositories;

class UserRepository
{
	protected $model;


	public function __construct()
	{
	}


	public function index()
	{
		$this->model->all();
	}


	public function store($data)
	{
		$this->model->create($data);
	}


	public function show($id)
	{
		$this->model->findById($id);
	}


	public function update($id, $data)
	{
		$this->model->where("id",$id)->update($data);
	}


	public function destroy($id)
	{
		$this->model->where("id",$id)->delete();
	}
}
````

````php
<?php 

namespace App\Services;

use App\Repositories\UserRepository;

class UserService
{
	protected UserRepository $userRepository;


	public function __construct(UserRepository $userRepository)
	{
		$this->userRepository = $userRepository;
	}
}

````
