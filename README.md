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

// Loading your application folders
$loader = new Selene\Loader\AppLoader;
$loader->addDirectory('App/Controllers');
$loader->addDirectory('App/Models');
$loader->addDirectory('App/Gateway');
$loader->addDirectory('App/Config');
$loader->load();

// Getting an instance of Selene framework
$app = Selene\App::getInstance();

// Using the router to register your application routes
// In this case we are using the authentication middleware
$app->route()->middleware([
    new Selene\Middleware\Handler\Auth
])->group(function () use ($app) {

    // This route responds as callback function
    $app->route()->get('/callable', function () use ($app) {
        $app->json('ola mundo again');
    });

    // Mapping requested http method with request http path
    $app->route()->get('/', 'HomeController@index');
    $app->route()->get('/shos/{id}', 'HomeController@show');
    $app->route()->get('/show/{id}', 'HomeController@show');
    $app->route()->get('/store', 'HomeController@store');
    $app->route()->get('/login', 'HomeController@login');
    $app->route()->post('/login', 'HomeController@login');
    $app->route()->get('/logout', 'HomeController@logout');
})->run();
```

## Creating a database gateway connection

Create a file inside the Gateway folder with the following contents:

```php
<?php

use Selene\Gateway\GatewayAbstract;

class BookGateway extends GatewayAbstract
{
    /**
     * Using the gateway to create a query
     */
    public function getBooks()
    {
        return $this
                ->select('*')
                ->table('books')
                ->where(['title = ?' => 'matrix'])
                ->execute()
                ->fetchAll();
    }

    /**
     * Creating an insert clause
     */
    public function insertBook()
    {
        return $this->insert([
                'id' => 1,
                'title' => 'Toy Story'
            ])
            ->table('books')
            ->execute();
    }

    /**
     * Creating a delete clause
     */
    public function deleteBook(int $cod)
    {
        $this->delete()
            ->table('books')
            ->where(['id = ?' => $cod])
            ->execute();
    }

    /**
     * Creating an update clause
     */
    public function updateTipo()
    {
        $this->update([
                'title' => 'Toy Story'
            ])
            ->table('books')
            ->where(['id = ?' => 1])
            ->execute();
    }
}
```

## Creating a controller

Create a file inside the Controllers folder with the following contents:

```php
<?php

use Selene\App\AppCreator;
use Selene\Controllers\BaseController;
use Selene\Request\Request;
use Selene\Response\Response;
use HomeGateway;

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
        $books = [
            0 => [
                'terror' => 'A terror book',
                'romance' => 'A romance book'
            ],
            1 => [
                'romance' => 'Another romance book',
            ]
        ];

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
        $auth = AppCreator::container()->get(AppCreator::AUTH);

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
        $auth = AppCreator::container()->get(AppCreator::AUTH);

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
        $auth = AppCreator::container()->get(AppCreator::AUTH);
        $auth->logout();
    }

    // ... omitted code
```

## Changing session configuration parameters

If you are using authentication middleware, you will need to set session parameters.

```php
use Selene\Config\ConfigInterface;
use Selene\Config\ConfigConstant;

class Session implements ConfigInterface
{
    public function __invoke() : array
    {
        return [
            ConfigConstant::SESSION_TABLE_NAME      => "session",
            ConfigConstant::SESSION_EXPIRATION_TIME => 30,
            ConfigConstant::SESSION_REFRESH_TIME    => 10,
        ];
    }
}
```

## Changing authentication configuration parameters

If you are using authentication middleware, you will need to set auth parameters.

```php
use Selene\Config\ConfigInterface;
use Selene\Config\ConfigConstant;

class Auth implements ConfigInterface
{
    public function __invoke() : array
    {
        return [
            ConfigConstant::AUTH_LOGIN_URL              => "/login",
            ConfigConstant::AUTH_TABLE_NAME             => "user",
            ConfigConstant::AUTH_REDIRECT_SUCCESS_LOGIN => "/users",
            ConfigConstant::AUTH_REDIRECT_FAILED_LOGIN  => "/logout"
        ];
    }
}
```

## Creating a template with Selene template engine

Create a file called home.php inside the Views/home folder with the following contents:

```php
<?php

<!-- include a partial template -->
{{ include /partials/header.php }}

<!-- Iterating in an array -->
{{ foreach $books as $book }}
    <p>{{ $book.terror }}</p>
    <p>{{ $book.romance }}</p>
{{ endforeach }}

<!-- Using template modification plugins -->
<p>{{ $toUpperCase | upper }}</p>
<p>{{ $toLowerCase | lower }}</p>

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

The Selene Microframework is licensed under the MIT license. See [License File](LICENSE) for more information.