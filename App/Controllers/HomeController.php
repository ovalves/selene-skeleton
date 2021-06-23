<?php
/**
 * @copyright   2019 - Selene
 * @author      Vinicius Oliveira <vinicius_o.a@live.com>
 * @category    Micro Framework
 * @since       2019-10-12
 */

use Selene\Container\ServiceContainer;
use Selene\Controllers\BaseController;
use Selene\Render\View;
use Selene\Request\Request;
use Selene\Response\Response;

class HomeController extends BaseController
{
    /**
     * Index Action.
     */
    public function index(): View
    {
        $books = $this
            ->select('*')
            ->table('books')
            ->where(['title = ?' => 'matrix'])
            ->execute()
            ->fetchAll();

        /*
         * @example Setting variables for the view
         */
        $this->view()->assign('books', $books);
        $this->view()->assign('statement', 'Matrix');
        $this->view()->assign('anotherStatement', 'Toy Story');
        $this->view()->assign('toUpperCase', 'person');
        $this->view()->assign('toLowerCase', 'PERSON');

        /*
        * @example render view
        */
        return $this->view()->render('home/home.php');
    }

    /**
     * store Action.
     */
    public function store(Request $request, Response $response): View
    {
        if ($response->isUnauthorized()) {
            $response->redirectToLoginPage();
        }

        $auth = $this->container->get(ServiceContainer::AUTH);
        if ($auth->isAuthenticated()) {
            return $this->view()->render('home/store.php');
        }

        return $this->view()->render('home/login.php');
    }

    /**
     * register user Action.
     */
    public function register(Request $request): void
    {
        $data = $request->getPostParams();
        $auth = $this->container->get(ServiceContainer::AUTH);

        /*
         * Register the user in the database
         */
        $auth->registerUser($data['email'], $data['password']);
    }

    /**
     * login user Action.
     */
    public function login(Request $request): View
    {
        $auth = $this->container()->get(ServiceContainer::AUTH);

        if ('GET' == $request->getMethod() && !$auth->isAuthenticated()) {
            return $this->view()->render('home/login.php');
        }

        if ($auth->isAuthenticated()) {
            return $this->view()->render('home/home.php');
        }

        $data = $request->getPostParams();
        $data = $auth->authenticate($data['email'], $data['password']);

        if ($auth->isAuthenticated()) {
            return $this->view()->render('home/home.php');
        }
    }

    /**
     * logout user Action.
     */
    public function logout(): View
    {
        $auth = $this->container->get(ServiceContainer::AUTH);
        $auth->logout();

        return $this->view()->render('home/login.php');
    }
}
