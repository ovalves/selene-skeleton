# Vindite Microframework

Vindite is a PHP micro-framework that helps you quickly write simple web applications.

## Installation

It's recommended that you use [Composer](https://getcomposer.org/) to install Vindite.

```bash
$ composer require vindite/vindite "dev-master@dev"
```

This will install Vindite and all required dependencies. Vindite requires PHP 7.2 or newer.

## Seting up Vindite Microframework

Create an index.php file with the following contents:

```php
<?php

require 'vendor/autoload.php';

$app = Vindite\App::getInstance();

$app->route()->middleware([
    new Vindite\Middleware\Handler\Auth,
    new Vindite\Middleware\Handler\Session
])->group(function () use ($app) {
    $app->route()->get('/hello/{name}', function ($argument) use ($app) {
        return $app->json("Hello, {$argument['name']}");
    });
    $app->route()->get('/', 'HomeController@index');
    $app->route()->post('/store', 'HomeController@store');
    $app->route()->put('/put/{id}', 'HomeController@put');
    $app->route()->delete('/delete/{id}', 'HomeController@delete');
})->run();
```

## Creating a database gateway connection

Create a file called HomeGateway.php inside the Gateway folder with the following contents:

```php
<?php

use Vindite\Gateway\GatewayAbstract;

class HomeGateway extends GatewayAbstract
{
    /**
     * Using the gateway ORM to execute a simple query select
     */
    public function select()
    {
        return $this
                ->select('*')
                ->table('person')
                ->where(['country = ?' => 'brazil'])
                ->where(['name = ?' => 'John Doe'])
                ->execute()
                ->fetchAll();
    }

    /**
     * Using the gateway ORM to make a simple insert query
     */
    public function insert()
    {
        return $this->insert([
                'id' => 1,
                'name' => 'Mary'
            ])
            ->table('person')
            ->execute();
    }

    /**
     * Using the gateway ORM to update a row of the database
     */
    public function update()
    {
        $this->update([
                'name' => 'Stuart'
            ])
            ->table('person')
            ->where(['id = ?' => 1])
            ->execute();
    }

    /**
     * Using the gateway ORM to delete a row of the database
     */
    public function deleteTipo()
    {
        $this->delete()
            ->table('person')
            ->where(['id = ?' => 1)
            ->execute();
    }
}
```

## Creating a controller

Create a file called HomeController.php inside the Controllers folder with the following contents:

```php
<?php

use Vindite\Controllers\BaseController;
use Vindite\Request\Request;
use HomeGateway;

class HomeController extends BaseController
{
    public function index(Request $request)
    {
        /**
         * Setting a model object
         */
        $person = new Person;
        $person->city     = 'sao paulo';
        $person->nome     = 'JOHN';
        $person->phone    = '+55 11 99999-9999';
        $person->email    = 'sample@sample.com';

        $persons = [
            [
                'name' => 'John',
                'age' => 46
            ],
            [
                'name' => 'Mary',
                'age' => 23
            ]
        ];

        /**
         * Assign variables to template engine
         */
        $this->view()->assign('city', $person->city);
        $this->view()->assign('name', $person->nome);
        $this->view()->assign('phone', $person->phone);
        $this->view()->assign('email', $person->email);

        /**
         * Assign an array to template engine
         */
        $this->view()->assign('persons', $persons);

        /**
         * Render a template engine
         */
        $this->view()->render('home/home.php');
    }
}
```

## Creating a template with vindite template engine

Create a file called home.php inside the Views/home folder with the following contents:

```php
<?php

<!-- Include a partial template -->
{{ include /partials/header.php }}

<!-- Iterate an array of persons -->
{{ foreach $persons as $person }}
    <p>{{ $person.name }}</p>
    <p>{{ $person.age }}</p>
{{ endforeach }}


<!-- Using the template engine plugins -->
<p>{{ $city | upper }}</p> <!-- SAO PAULO -->
<p>{{ $name | lower }}</p>  <!-- john -->

<!-- uso da tag if no template -->
{{ if ($name == 'john') }}
    <p>Hello John</p>
{{ elseif ($name == 'Maty') }}
    <p>Hello Mary</p>
{{ else }}
    <p>Hello!!!</p>
{{ endif }}

<!-- Include a partial template -->
{{ include /partials/footer.php }}
```

## Database connection

Create a file called database_connection.ini inside the App/Config folder with the following contents:

```php
host = 127.0.0.1
name = database_name
user = database_user
pass = database_password
type = mysql
```

## Credits

- [Vinicius Alves](https://github.com/vindite)

## License

The Vindite Microframework is licensed under the MIT license. See [License File](LICENSE) for more information.