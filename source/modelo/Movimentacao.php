<?php

class Movimentacao 
{
    private $id;
    private $valor;
    private $tipo;
    
    /**
     * @var DespesaTipo
     */
    private $despesaTipo;
    
    /**
     * @var Congregacao
     */
    private $congregacao;
    
    /**
     * @var CongregacaoCulto 
     */
    private $congregacaoCulto;
    
    /**
     * @var Membro 
     */
    private $contribuinte;
    private $descricao;
    private $dataMovimentacao;
    
    /**
     * @var Membro 
     */
    private $usuarioCadastro;
    private $dataCadastro;

    public function getId() {
        return $this->id;
    }

    public function getValor() {
        return $this->valor;
    }

    public function getTipo() {
        return $this->tipo;
    }

    public function getDespesaTipo() {
        return $this->despesaTipo;
    }

    public function getCongregacao() {
        return $this->congregacao;
    }

    public function getCongregacaoCulto() {
        return $this->congregacaoCulto;
    }

    public function getContribuinte() {
        return $this->contribuinte;
    }
    
    public function getDescricao() {
        return $this->descricao;
    }

    public function getDataMovimentacao() {
        return $this->dataMovimentacao;
    }

    public function getUsuarioCadastro() {
        return $this->usuarioCadastro;
    }

    public function getDataCadastro() {
        return $this->dataCadastro;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setValor($valor) {
        $this->valor = $valor;
    }

    public function setTipo($tipo) {
        $this->tipo = $tipo;
    }

    public function setDespesaTipo(DespesaTipo $despesaTipo) {
        $this->despesaTipo = $despesaTipo;
    }

    public function setCongregacao(Congregacao $congregacao) {
        $this->congregacao = $congregacao;
    }

    public function setCongregacaoCulto(CongregacaoCulto $congregacaoCulto) {
        $this->congregacaoCulto = $congregacaoCulto;
    }

    public function setContribuinte(Membro $contribuinte) {
        $this->contribuinte = $contribuinte;
    }
    
    public function setDescricao($descricao) {
        $this->descricao = $descricao;
    }

    public function setDataMovimentacao($dataMovimentacao) {
        $this->dataMovimentacao = $dataMovimentacao;
    }

    public function setUsuarioCadastro(Membro $usuarioCadastro) {
        $this->usuarioCadastro = $usuarioCadastro;
    }

    public function setDataCadastro($dataCadastro) {
        $this->dataCadastro = $dataCadastro;
    }
}