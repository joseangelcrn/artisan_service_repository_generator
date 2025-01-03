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

### Add this line to  ``bootstrap/providers.php`` file:

````php

<?php

return [
     ...,
     josanangel\ServiceRepositoryManager\ServiceRepositoryManagerServiceProvider::class,
];
````
### Execute composer dump-autoload after that:

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

class AuthRepository
{
	public function __construct()
	{
	}
}

````
````php
<?php 

class AuthService
{
	public function __construct()
	{
	}
}

````

````php
<?php 

class MapService
{
	public function __construct()
	{
	}
}

````
````php
<?php 

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
