## Selene Framework

O Selene é uma micro-framework PHP.

Selene foi desenvolvido para tornar mais simples as tarefas comuns utilizadas na maioria dos projetos da web, selene possui:

- Sistema de MVC
- Sistema de roteamento
- Sistema de injeção de dependência
- Gerenciamento de sessão
- Autenticação de usuário
- Query Builder para banco de dados Mysql e MongoDB.
- Sistema de template engine
- Sistema de Middleware
- Sistema de redirecionamento de usuário
- Gerenciamento do sistema de arquivos
- Gerenciamento de Logs

## Instalação

É recomendável que você use [Composer](https://getcomposer.org/) para instalar selene.

```bash
$ composer require ovalves/selene "dev-master@dev"
```

Isso instalará Selene e todas as suas dependências. Selene requer PHP 8.0 ou superior.

## Uso básico

Crie um arquivo index.php com o seguinte conteúdo:

```php
<?php

require 'vendor/autoload.php';

/*
|--------------------------------------------------------------------------
| Obtendo uma instância de Selene Framework
|--------------------------------------------------------------------------
|
| '/var/www/html/app/' é o mapeamento da raiz da nossa aplicação
*/
$app = Selene\App\Factory::create('/var/www/html/app/');

/*
|--------------------------------------------------------------------------
| Usando o roteador para registrar as rotas da sua aplicação
|--------------------------------------------------------------------------
| No caso abaixo, estamos criando um grupo nomeado 'auth'
|
| A criação de grupo de rotas serve para facilitar a utilização dos middlewares
*/
$app->route()->group('auth', function () use ($app) {

    /*
    |--------------------------------------------------------------------------
    | Neste caso, estamos adicionando o middleware de autentição
    |--------------------------------------------------------------------------
    | Esse middleware será executado em todas as rotas que pertencerem a esse grupo
    */
    $app->route()->middleware([new Selene\Middleware\Handler\Auth]);

    /*
    |--------------------------------------------------------------------------
    | Esta rota responde como um callable
    |--------------------------------------------------------------------------
    */
    $app->route()->get('/callable', function () use ($app) {
        $app->json('Hello World!!!');
    });

    /*
    |--------------------------------------------------------------------------
    | Mapeamento de método HTTP da request com a solicita~ HTTP de solicitação
    |--------------------------------------------------------------------------
    */
    $app->route()->get('/', 'HomeController@index');
    $app->route()->get('/show/{id}', 'HomeController@show');
    $app->route()->update('/show/{id}', 'HomeController@show');
    $app->route()->delete('/show/{id}', 'HomeController@show');
    $app->route()->post('/show', 'HomeController@login');
})->run();
```

## Criando uma controller

Crie um arquivo dentro da pasta Controllers com o seguinte conteúdo:

```php
<?php

use Selene\Controllers\BaseController;
use Selene\Request\Request;
use Selene\Response\Response;
use Selene\Render\View;

class HomeController extends BaseController
{
    public function index(Request $request, Response $response): View
    {
        return $this->view()->render(
            'pages/home',
            [
                'pageTitle' => 'Home'
            ]
        );
    }
}
```

## Utilizando o Query Builder

Podemos utilizar o query builder a partir dos gateways:

```php
<?php

use Selene\Gateway\GatewayAbstract;

class Gateway extends GatewayAbstract
{
    /*
    |--------------------------------------------------------------------------
    | Criando uma query select
    |--------------------------------------------------------------------------
    */
    public function select(): View
    {
        $books = $this->select('*')
                      ->table('movies')
                      ->where(['title = ?' => 'Toy Story'])
                      ->execute()
                      ->fetchAll();
    }

    /*
    |--------------------------------------------------------------------------
    | Criando uma query insert
    |--------------------------------------------------------------------------
    */
    public function insert(): View
    {
        $this->insert(['id' => 1, 'title' => 'Toy Story'])
             ->table('movies')
             ->execute();
    }

    /*
    |--------------------------------------------------------------------------
    | Criando uma query insert
    |--------------------------------------------------------------------------
    */
    public function update(): View
    {
        $this->update(['title' => 'Toy Story'])
             ->table('movies')
             ->where(['id = ?' => 1])
             ->execute();
    }

    /*
    |--------------------------------------------------------------------------
    | Criando uma query insert
    |--------------------------------------------------------------------------
    */
    public function update(): View
    {
        $this->delete()
             ->table('movies')
             ->where(['id = ?' => 1])
             ->execute();
    }
}
```

## Trabalhando com os dados da Request

```php
<?php

class HomeController extends BaseController
{
    public function index(Request $request, Response $response)
    {
        /*
        |--------------------------------------------------------------------------
        | Pegando os dados enviados via POST
        |--------------------------------------------------------------------------
        */
        $request->getPostParams();

        /*
        |--------------------------------------------------------------------------
        | Pegando os dados enviados via GET
        |--------------------------------------------------------------------------
        */
        $request->getGetParams();

        /*
        |--------------------------------------------------------------------------
        | Pegando os dados enviados via PUT
        |--------------------------------------------------------------------------
        */
        $request->getPutParams();

        /*
        |--------------------------------------------------------------------------
        | Pegando os dados enviados via DELETE
        |--------------------------------------------------------------------------
        */
        $request->getDeleteParams();

        /*
        |--------------------------------------------------------------------------
        | Pegando os dados do corpo da requisição
        |--------------------------------------------------------------------------
        */
        $request->getContentBody();

        /*
        |--------------------------------------------------------------------------
        | Pegando todos os dados da requisição
        |--------------------------------------------------------------------------
        */
        $request->all();
    }
}
```

## Trabalhando com autenticação na Controller

Se você estiver usando o middleware de autenticação, você pode testar se o usuário está logado.

```php
<?php
class HomeController extends BaseController
{
    public function index(Request $request, Response $response)
    {
        if ($response->isUnauthorized()) {
            $response->redirectToLoginPage();
        }
    }
}
```

Se você estiver usando o middleware de autenticação, você pode registrar, autenticar os usuários

```php
<?php

use Selene\Container\ServiceContainer;

class HomeController extends BaseController
{
    public function register(Request $request, Response $response)
    {
        /*
        |--------------------------------------------------------------------------
        | Pegando o objete de autenticação via Service Container
        |--------------------------------------------------------------------------
        */
        $auth = app()->container()->get(ServiceContainer::AUTH);
        $data = $request->getPostParams();

        /*
        |--------------------------------------------------------------------------
        | Registrando um usuário
        |--------------------------------------------------------------------------
        */
        $auth->registerUser($data['email'], $data['password']);
    }

    public function isAuthenticated(Request $request, Response $response): View
    {
        /*
        |--------------------------------------------------------------------------
        | Pegando o objete de autenticação via Service Container
        |--------------------------------------------------------------------------
        */
        $auth = app()->container()->get(ServiceContainer::AUTH);

        // Testando se o usuário está atenticado
        if ($auth->isAuthenticated()) {
            return $this->view()->render('home/home');
        }

        redirect()
            ->message('failed', 'Erro ao fazer login. Usuário ou senha incorreta!')
            ->back();
    }

    public function logout(Request $request, Response $response)
    {
        $auth = app()->container()->get(ServiceContainer::AUTH);
        $auth->logout();

        redirect()
            ->to('url')
            ->message('success', 'Sua sessão foi finalizada!')
            ->go();
    }
}
```

## Alterando os parâmetros de configuração do framework

Altere o arquivo app.php dentro da pasta Config

```php
return [
    /*
    |--------------------------------------------------------------------------
    | Configurações de conexação com a base de dados
    |--------------------------------------------------------------------------
    */
    ConfigConstant::DATABASE => [
        'mysql' => [
            DatabaseConstant::DB_HOST => env('MYSQL_HOST'),
            DatabaseConstant::DB_NAME => env('MYSQL_DATABASE'),
            DatabaseConstant::DB_USER => env('MYSQL_ROOT_USER'),
            DatabaseConstant::DB_PASS => env('MYSQL_ROOT_PASSWORD'),
        ],
        DatabaseConstant::DEFAULT_DB => 'mysql',
    ],
    /*
    |--------------------------------------------------------------------------
    | Configurações do sistema de autenticação do framework
    |--------------------------------------------------------------------------
    */
    ConfigConstant::AUTH => [
        ConfigConstant::AUTH_TABLE_NAME => 'users',
        ConfigConstant::AUTH_LOGIN_URL => env('APP_URL') . '/client/signin',
        ConfigConstant::AUTH_REDIRECT_SUCCESS_LOGIN => env('APP_URL'),
        ConfigConstant::AUTH_REDIRECT_FAILED_LOGIN => env('APP_URL') . '/client/signin',
    ],
    /*
    |--------------------------------------------------------------------------
    | Configurações do sistema de sessão do framework
    |--------------------------------------------------------------------------
    */
    ConfigConstant::SESSION => [
        ConfigConstant::SESSION_TABLE_NAME => 'session',
        ConfigConstant::SESSION_EXPIRATION_TIME => 3600,
        ConfigConstant::SESSION_REFRESH_TIME => 3600,
    ],

    /*
    |--------------------------------------------------------------------------
    | Habilita ou desabilita os módulos do framework
    |--------------------------------------------------------------------------
    */
    ConfigConstant::ENABLE_SESSION_CONTAINER => true,
    ConfigConstant::ENABLE_AUTH_CONTAINER => true,
    ConfigConstant::ENABLE_CACHE_VIEWS => false,
];
```

## Trabalhando com a template engine

As views do framework ficam na pasta public/Views:

### Crie um arquivo chamado template.html

```html
{{ include /partials/header.html }}
    <div class="wrapper">
        {{ include /components/navbar.html }}
        <div class="content-wrapper">
            <section class="content">
                <div class="container-fluid">
                    {{ yield content }}
                </div>
            </section>
        </div>
    </div>
{{ include /partials/footer.html }}
```

No código acima, definimos um template que pode ser extendido por outras páginas do sistema.

### Crie um arquivo chamado home.html

```html
{{ extends template.html }}

{{ block content }}
    <p>Olá Mundo</p>
{{ endblock }}
```

No código acima, criamos uma view que extende do template. O texto Olá Mundo será impreso dentro do yield

### Outros recursos da template engine
```php
<?php

/*
|--------------------------------------------------------------------------
| Utilizando código PHP dentro da template engine
|--------------------------------------------------------------------------
*/
{% (foreach $books as $book): %}
    <p>{{ $book['terror'] | lower }}</p>
    <p>{{ $book['romance'] | upper }}</p>
    <p>{{ $book['action'] | upper }}</p>
{% endforeach %}

/*
|--------------------------------------------------------------------------
| Ecoando texto dentro da template engine
|--------------------------------------------------------------------------
| No exemplo acima além de ecoar o texto também estamos aplicando um modificador no texto
|
| O modificador citado acima, transforma o texto em maiusculo
*/
{{ 'olá mundo!!!' | upper }} // 'OLÁ MUNDO!!!'
```

## Usando o console de linha de comando do Selene

## Pedindo ajuda
```php
php solvr --help
```

## Pedindo ajuda do console para criar controllers
```php
php solvr generate:controller --help
```

## Criando uma controller simples
```php
php solvr generate:controller simpleController
```

## Criando uma controller de recursos (irá criar actions para todos os verbos HTTP)
```php
php solvr generate:controller resourceController --resource
```

## Licença

O Selene framework é licenciado usa a licença MIT license. Veja [License File](LICENSE) para maiores informações.