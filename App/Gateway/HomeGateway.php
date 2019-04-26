<?php
/**
 * @copyright   2018 - Vindite
 * @author      Vinicius Oliveira <vinicius_o.a@live.com>
 * @category    Micro Framework
 * @since       2018-04-28
 */

use Vindite\Gateway\GatewayAbstract;

/**
 * Responsavel por fazer a ponte entra a controller e a base de dados
 */
class HomeGateway extends GatewayAbstract
{
    /**
     * Usando o gateway para criar uma query
     */
    public function getPessoas()
    {
        return $this
                ->select('*')
                ->table('pessoa')
                ->where(['bairro = ?' => 'centro'])
                ->where(['email = ?' => 'teste@teste.com'])
                ->where(['id_cidade = ?' => 1])
                ->execute()
                ->fetchAll();
    }

    /**
     * Criando uma clausula insert
     */
    public function insertTipo()
    {
        return $this->insert([
                'id' => 1,
                'nome' => 'Teste'
            ])
            ->table('tipo')
            ->execute();
    }

    /**
     * Criando uma clausula delete
     */
    public function deleteTipo(int $cod)
    {
        $this->delete()
            ->table('tipo')
            ->where(['id = ?' => $cod])
            ->execute();
    }

    /**
     * Criando uma clausula update
     */
    public function updateTipo()
    {
        $this->update([
                'nome' => 'Teste2'
            ])
            ->table('tipo')
            ->where(['id = ?' => 1])
            ->execute();
    }
}
