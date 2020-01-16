# Selene Microframework

Selene is a PHP micro-framework that helps you quickly write simple web applications.

## Installation

It's recommended that you use [Composer](https://getcomposer.org/) to install Selene.

```bash
$ composer require vindite/selene "dev-master@dev"
```

This will install Selene and all required dependencies. Selene requires PHP 7.2 or newer.

## Usage

Create an index.php file with the following contents:

```php
<?php

require 'vendor/autoload.php';

$app = Selene\App\Factory::create();

$app->route()->middleware([
   new Selene\Middleware\Handler\Auth
])->group(function () use ($app) {
    $app->route()->get('/callable', function () use ($app) {
        $app->json('ola mundo again');
    });

    $app->route()->get('/', 'HomeController@index');
    $app->route()->get('/shos/{id}', 'HomeController@show');
    $app->route()->get('/show/{id}', 'HomeController@show');
    $app->route()->get('/store', 'HomeController@store');
    $app->route()->get('/login', 'HomeController@login');
    $app->route()->post('/login', 'HomeController@login');
    $app->route()->get('/logout', 'HomeController@logout');
    $app->route()->get('/logout', 'HomeController@logout');
})->run();
```

## Creating a controller

Create a file inside the Controllers folder with the following contents:

```php
<?php

use Selene\Container\ServiceContainer;
use Selene\Controllers\BaseController;
use Selene\Request\Request;
use Selene\Response\Response;

class BookController extends BaseController
{
    /**
     * Index Action
     *
     * @param Request $request
     * @param Response $response
     * @return template|view
     */
    public function index(Request $request, Response $response)
    {
        /**
         * Select Query
         */
        $books = $this->select('*')
                      ->table('books')
                      ->where(['title = ?' => 'movies'])
                      ->execute()
                      ->fetchAll();

        /**
         * Insert Query
         */
        $this->insert(['id' => 1, 'title' => 'Toy Story'])
             ->table('movies')
             ->execute();

        /**
         * Update Query
         */
        $this->update(['title' => 'Toy Story'])
             ->table('movies')
             ->where(['id = ?' => 1])
             ->execute();

        /**
         * Delete Query
         */
        $this->delete()
             ->table('movies')
             ->where(['id = ?' => $cod])
             ->execute();

        /**
         * @example Setting variables for the view
         */
        $this->view()->assign('books', $books);
        $this->view()->assign('statement', 'Matrix');
        $this->view()->assign('anotherStatement', 'Toy Story');
        $this->view()->assign('toUpperCase', 'person');
        $this->view()->assign('toLowerCase', 'PERSON');

        /**
        * @example render view
        */
        $this->view()->render('home/home.php');
    }
}
```
## Getting the request parameters

```php
<?php

    // ... omitted code

    /**
     * Index Action
     *
     * @param Request $request
     * @param Response $response
     * @return template|view
    */
    public function index(Request $request, Response $response)
    {
        // Getting post params
        $request->getPostParams();

        // Getting get params
        $request->getGetParams();

        // Getting put params
        $request->getPutParams();

        // Getting delete params
        $request->getDeleteParams();
    }

    // ... omitted code
```

## Using Authentication on Controller

If you are using authentication middleware you can test if the user is logged in.

```php
<?php

    // ... omitted code

    /**
     * Index Action
     *
     * @param Request $request
     * @param Response $response
     * @return template|view
    */
    public function index(Request $request, Response $response)
    {
        if ($response->isUnauthorized()) {
            $response->redirectToLoginPage();
        }

        // ... omitted code
    }

    // ... omitted code
```
Using Selene Authentication Container you can register, authenticate or lo users too

```php
<?php

    // ... omitted code

    /**
     * Index Action
     *
     * @param Request $request
     * @param Response $response
     * @return template|view
    */
    public function register(Request $request, Response $response)
    {
        // Getting the authentication object container
        $auth = $this->container->get(ServiceContainer::AUTH);

        // Getting request params
        $data = $request->getPostParams();

        // Registering user
        $auth->registerUser($data['email'], $data['password']);
    }

    /**
     * isAuthenticated Action
     *
     * @param Request $request
     * @param Response $response
     * @return template|view
    */
    public function isAuthenticated(Request $request, Response $response)
    {
        // Getting the authentication object container
        $auth = $this->container->get(ServiceContainer::AUTH);

        // Testing authentication
        if ($auth->isAuthenticated()) {
            $this->view()->render('home/home.php');
        } else {
            $this->view()->render('home/login.php');
        }
    }

    /**
     * logout Action
     *
     * @param Request $request
     * @param Response $response
     * @return template|view
     */
    public function logout(Request $request, Response $response)
    {
        // Getting the authentication object container
        $auth = $this->container->get(ServiceContainer::AUTH);
        $auth->logout();
    }

    // ... omitted code
```

## Changing app configuration parameters

```php
    return [
        // Changing the database configuration parameters
        'database' => [
            'mysql' => [
                'db_host' => '127.0.0.1',
                'db_name' => 'vindite',
                'db_user' => 'root',
                'db_pass' => 'root',
                'db_type' => 'mysql'
            ],
            'default' => 'mysql'
        ],
        // If you are using authentication middleware, you will need to set session parameters.
        'auth' => [
            // data
        ],
        // If you are using authentication middleware, you will need to set auth parameters.
        'session' => [
            // data
        ]
    ];
```

## Creating a template with Selene template engine

Create a file called home.php inside the Views/home folder with the following contents:

```php
<?php

<!-- include a partial template -->
{{ include /partials/header.php }}

<!-- Iterating in an array -->
{{ foreach $books as $book }}
    <p>{{ $book.terror | lower }}</p>
    <p>{{ $book.romance | upper }}</p>
    <p>{{ $book.action | upper }}</p>
{{ endforeach }}

<!-- Using template modification plugins -->
<p>{{ $anotherStatement | lower }}</p>
<p>{{ $toUpperCase | upper }}</p>
<p>{{ $toLowerCase | lower }}</p>
<p>{{ $statement | upper }}</p>

<a href="/login">teste</a>
<!-- use of if tag in template -->
{{ if ($statement == 'compare') }}
    <!-- ...code -->
{{ elseif ($anotherStatement == 'compare') }}
    <!-- ...code -->
{{ else }}
    <!-- ...code -->
{{ endif }}

<!-- include a partial template -->
{{ include /partials/footer.php }}
```

## Using Selene solvr console to generate class

## Asking for console help
```php
php solvr --help
```

## Asking for console help on making controllers
```php
php solvr generate:controller --help
```

## Making a simple controller
```php
php solvr generate:controller simpleController
```

## Creating a resource controller (it will create actions for HTTP methods - to make CRUD easier)
```php
php solvr generate:controller resourceController --resource
```

## Credits

- [Vinicius Alves](https://github.com/vindite)

## License

The Selene Microframework is licensed under the MIT license. See [License File](LICENSE) for more information.