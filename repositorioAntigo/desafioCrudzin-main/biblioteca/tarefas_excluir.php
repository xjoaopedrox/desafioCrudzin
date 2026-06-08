<?php

//Exibir erros (Padrão para desenvolvimento)
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once("util/Conexao.php");

//1- Identificar qual atividade o usuário quer excluir
//2- Validar se o identificador da tarefa foi recebido
$id = 0;
if (isset($_GET['id']))
    $id = $_GET['id'];

if ($id > 0) {
    //3- Excluir as tarefas do banco de dados (DAO e chama o método de exclusão passando o ID)
    $tarefaDAO = new TarefaDAO();
    $tarefaDAO->excluir($id);

    //4- Redirecionar para a listagem de tarefas
    header("location: tarefas.php");
} else {
    echo "Parâmetro ID inválido!<br>";
    echo "<a href='tarefas.php'>Voltar</a>";
}
