<?php
/**
 * @copyright   2019 - Selene
 * @author      Vinicius Oliveira <vinicius_o.a@live.com>
 * @category    Micro Framework
 * @since       2019-10-12
 */

use Selene\Container\ServiceContainer;
use Selene\Controllers\BaseController;
use Selene\Request\Request;
use Selene\Response\Response;

class HomeController extends BaseController
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
        $books = $this
            ->select('*')
            ->table('books')
            ->where(['title = ?' => 'matrix'])
            ->execute()
            ->fetchAll();

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

    /**
     * store Action
     *
     * @param Request $request
     * @param Response $response
     * @return template|view
     */
    public function store(Request $request, Response $response)
    {
        if ($response->isUnauthorized()) {
            $response->redirectToLoginPage();
        }

        $auth = $this->container->get(ServiceContainer::AUTH);
        if ($auth->isAuthenticated()) {
            // render store page
        } else {
            // render login page
        }
    }

    /**
     * register user Action
     *
     * @param Request $request
     * @param Response $response
     * @return template|view
     */
    public function register(Request $request)
    {
        $data = $request->getPostParams();
        $auth = $this->container->get(ServiceContainer::AUTH);

        /**
         * Register the user in the database
         */
        $auth->registerUser($data['email'], $data['password']);
    }

    /**
     * login user Action
     *
     * @param Request $request
     * @param Response $response
     * @return template|view
     */
    public function login(Request $request, Response $response)
    {
        $auth = $this->container()->get(ServiceContainer::AUTH);

        if ($request->getMethod() == "GET" && !$auth->isAuthenticated()) {
            $this->view()->render('home/login.php');
        } else {
            if ($auth->isAuthenticated()) {
                // is authenticated
            } else {
                // authenticating
                $data = $request->getPostParams();
                $data = $auth->authenticate($data['email'], $data['password']);
            }
        }
    }

    /**
     * login user Action
     *
     * @param Request $request
     * @param Response $response
     * @return template|view
     */
    public function logout(Request $request, Response $response)
    {
        $auth = $this->container->get(ServiceContainer::AUTH);
        $auth->logout();
    }
}
