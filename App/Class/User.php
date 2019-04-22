<?php
/**
 * @copyright   2018 - Vindite
 * @author      Vinicius Oliveira <vinicius_o.a@live.com>
 * @category    Micro Framework
 * @since       2018-05-01
 */

use Vindite\Session\Session;

class User
{
    /**
     * Guarda uma referencia para o objeto de sessao
     *
     * @var object
     */
    public $session;

    /**
     * Executa a acao de logar o ususario na aplicacao
     *
     * @param array $userdata
     * @return void
     */
    public function login(array $userdata)
    {
        $userdata = reset($userdata);

        if (empty($userdata)) {
            throw new Exception("Error Processing Request", 1);
        }

        $this->session()->setValue($userdata);
        return $this;
    }

    /**
     * Verifica se o usuario esta logado na aplicacao
     *
     * @param array $userdata
     * @return void
     */
    public function loggedIn()
    {
        if (empty($this->session()->hasSession())) {
            header('location:' . HOME_URI . DIRECTORY_SEPARATOR . 'auth/login');
        }

        return true;
    }

    /**
     * Pega o id do usuario logado da sessao
     *
     * @return int
     */
    public function userId(): int
    {
        if (empty($this->session()->hasSession())) {
            header('location:' . HOME_URI . DIRECTORY_SEPARATOR . 'auth/login');
        }

        return $this->session()->getValue('user_id');
    }

    /**
     * Retorna uma instancia do objeto de sessao
     *
     * @return object
     */
    public function session()
    {
        if (empty($this->session)) {
            $this->session = new Session;
        }
        return $this->session;
    }
}
