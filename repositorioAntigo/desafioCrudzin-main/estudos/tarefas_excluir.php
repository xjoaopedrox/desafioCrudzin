<?php

require_once("util/Conexao.php");

//1- Identificar qual tarefa o usuário quer excluir
//2- Validar se o identificador da tarefa foi recebida
$id = 0;
if(isset($_GET['id']))
    $id = $_GET['id'];

if($id > 0) {
    //3- Excluir o livro do banco de dados (SQL)
    $conexao = Conexao::getConexao();
    
    $sql = "DELETE FROM tarefas WHERE id = ?";
    $stm = $conexao->prepare($sql);
    $stm->execute([$id]);

    //4- Redirecionar para a listagem de tarefas
    header("location: tarefas.php");

} else {
    echo "Parâmetro ID inválido!<br>";
    echo "<a href='tarefas.php'>Voltar</a>";
}