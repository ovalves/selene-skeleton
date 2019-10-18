<?php
/**
 * @copyright   2019 - Selene
 * @author      Vinicius Oliveira <vinicius_o.a@live.com>
 * @category    Micro Framework
 * @since       2019-10-12
 */

use Selene\Gateway\GatewayAbstract;

class HomeGateway extends GatewayAbstract
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
