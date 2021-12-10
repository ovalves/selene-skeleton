<?php
/**
 * @copyright   2019 - Selene
 * @author      Vinicius Oliveira <vinicius_o.a@live.com>
 * @category    Micro Framework
 * @since       2019-10-12
 */

use Selene\Model\ModelAbstract;

class Books extends ModelAbstract
{
    /**
     * Constante que indica o nome da tabela.
     */
    public const TABLENAME = 'books';

    protected $id;
    protected $title;
}
