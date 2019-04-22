<?php
/**
 * @copyright   2018 - Vindite
 * @author      Vinicius Oliveira <vinicius_o.a@live.com>
 * @category    Micro Framework
 * @since       2018-04-21
 */

use Vindite\Model\ModelAbstract;

class Pessoa extends ModelAbstract
{
    /**
     * Constante que indica o nome da tabela
     */
    const TABLENAME = 'pessoa';

    /**
     * Variavel que registra o nome do Usuario
     */
    protected $nome;

    /**
     * Variavel que registra o endereco do Usuario
     */
    protected $endereco;

    /**
     * Variavel que registra o bairro do Usuario
     */
    protected $bairro;

    /**
     * Variavel que registra o telefone do Usuario
     */
    protected $telefone;

    /**
     * Variavel que registra o email do Usuario
     */
    protected $email;
}
