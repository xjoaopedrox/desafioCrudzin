<?php
//atributos privados ocultam dados

class Tarefa {

    private $id;
    private $nome_tarefa;
    private $duracao_minutos;
    private $prioridade;
    private $data_entrega;

// ID
    public function getId() {
        return $this->id;
    }
    public function setId($id) {
        $this->id = $id;
    }

    // Nome da Tarefa
    public function getNomeTarefa() {
        return $this->nome_tarefa;
    }
    public function setNomeTarefa($nome) {
        $this->nome_tarefa = $nome;
    }

    // Categoria
    public function getCategoria() {
        return $this->categoria;
    }
    public function setCategoria($categoria) {
        $this->categoria = $categoria;
    }

    // Duração
    public function getDuracaoMinutos() {
        return $this->duracao_minutos;
    }
    public function setDuracaoMinutos($minutos) {
        $this->duracao_minutos = $minutos;
    }

    // Prioridade
    public function getPrioridade() {
        return $this->prioridade;
    }
    public function setPrioridade($prioridade) {
        $this->prioridade = $prioridade;
    }

    // Data de Entrega
    public function getDataEntrega() {
        return $this->data_entrega;
    }
    public function setDataEntrega($data) {
        $this->data_entrega = $data;
    }
}



