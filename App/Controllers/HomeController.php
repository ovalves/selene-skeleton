<?php
/**
 * @copyright   2018 - Vindite
 * @author      Vinicius Oliveira <vinicius_o.a@live.com>
 * @category    Micro Framework
 * @since       2018-04-21
 */

use Vindite\Controllers\BaseController;
use Vindite\Request\Request;
use HomeGateway;

/**
 * Controller responsavel por renderizar dados da view home
 */
class HomeController extends BaseController
{
    /**
     * Request action
     *
     * @return template|view
     */
    public function index(Request $request)
    {
        /**
         * Instancia uma model
         * Busca os dados da model na base de dados
         */

        $pessoa = new Pessoa;
        $pessoa->nome     = 'Vinicius';
        $pessoa->endereco = 'sample';
        $pessoa->bairro   = 'sample';
        $pessoa->telefone = 'sample';
        $pessoa->email    = 'sample';

        /**
         * Instanciando o gateway para ter acesso a base de dados
         */
        // $gateway = new HomeGateway;
        // $selectpessoas = $gateway->getPessoas();
        // $inserttipo    = $gateway->insertTipo();
        // $deleteTipo    = $gateway->deleteTipo(7);
        // $updateTipo    = $gateway->updateTipo(7);

        /**
         * Variaveis da view
         */
        $pessoas = [
            [
                'nome' => 'vinicius',
                'idade' => 23,
                'endereco' => 'Rua de teste'
            ],
            [
                'nome' => 'sergio',
                'idade' => 46,
                'endereco' => 'Rua de teste 2'
            ]
        ];

        $tipos = [
            0 => [
                'suspense' => 'Uma bomba no quintal',
                'romance' => 'teste'
            ],
            1 => [
                'romance' => 'um amor para recordar',
            ]
        ];
        /**
         * @example Setando variaveis para a view
         */
        $this->view()->assign('tipos', $tipos);
        $this->view()->assign('pessoas', $pessoas);
        $this->view()->assign('pessoa', 'vinicius');
        $this->view()->assign('pessoa2', 'SERGIO');

        /**
        * @example render view
        * @package Vindite\Render\View
        * @param string caminho para a view
        */
        $this->view()->render('home/home.php');
        echo 'index of sample controller';
        echo '<br><br>';
    }
}
