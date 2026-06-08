<?php

require_once("util/Conexao.php");
require_once("model/Tarefa.php");

class TarefaDAO
{

    //metodo para salvar no Banco
    public function inserir(Tarefa $tarefa) //Tipagem de Parâmetro
    {
        $sql = "INSERT INTO tarefas (nome_tarefa, categorias_permitidas, duracao_minutos, prioridade, data_entrega) 
                VALUES (?, ?, ?, ?, ?)";

        $conexao = Conexao::getConexao();
        $stm = $conexao->prepare($sql);


        $stm->execute([
            $tarefa->getNomeTarefa(),
            $tarefa->getCategoria(),
            $tarefa->getDuracaoMinutos(),
            $tarefa->getPrioridade(),
            $tarefa->getDataEntrega()
        ]);
    }

    //metodo para uscar todos
    public function listar(){
        $sql = "SELECT * FROM tarefas";

        $conexao = Conexao::getConexao();
        $stm = $conexao->prepare($sql);
        $stm->execute();

        return $stm->fetchAll();
    }

    //metodod para deletar por ID

    public function excluir($id) {
        $sql = "DELETE FROM tarefas WHERE id = ?";
        
        $conexao = Conexao::getConexao();
        $stm = $conexao->prepare($sql);
        $stm->execute([$id]);
    }
        
    
}