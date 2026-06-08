<?php
// controller/TarefaController.php
require_once("util/Conexao.php");
require_once("dao/TarefaDAO.php");
require_once("model/Tarefa.php");
date_default_timezone_set('America/Sao_Paulo');

// Exibir erros
ini_set('display_errors', 1);
error_reporting(E_ALL);


$categorias_permitidas = ["V", "P", "E"];

// instancia do DAO para usar as funções de banco de dados
$tarefaDAO = new TarefaDAO();

$msgsErro = "";
$nome_tarefa = "";
$categoria = "";
$duracao_minutos = "";
$prioridade = "";
$data_entrega = "";

// Salvar a tarefa
if (isset($_POST['nome_tarefa'])) {

    // 1- Receber os dados do formulário
    $nome_tarefa = trim($_POST['nome_tarefa']) ? trim($_POST['nome_tarefa']) : null;
    $categoria = trim($_POST['categoria']) ? trim($_POST['categoria']) : null;
    $duracao_minutos = is_numeric($_POST['duracao_minutos']) ? $_POST['duracao_minutos'] : null;
    $prioridade = trim($_POST['prioridade']) ? trim($_POST['prioridade']) : null;
    $data_entrega = trim($_POST['data_entrega']) ? trim($_POST['data_entrega']) : null;

    // 1.1 - Validar os dados
    $msgs = array();

    if (! $nome_tarefa) {
        array_push($msgs, "Informe o nome da tarefa!");
    } else if (strlen($nome_tarefa) < 3 || strlen($nome_tarefa) > 100) {
        array_push($msgs, "O nome da tarefa deve ter entre 3 e 100 caracteres!");
    }

    if (!in_array($categoria, $categorias_permitidas)) {
        array_push($msgs, "Selecione uma categoria válida!");
    }

    if (! $duracao_minutos) {
        array_push($msgs, "Informe a duração da tarefa");
    } else if ($duracao_minutos <= 0) { 
        array_push($msgs, "Informe minutos válidos (maior que zero)");
    }

    if (! $data_entrega) {
        array_push($msgs, "Informe a data de entrega");
    } else {
        $hoje = date('Y-m-d');
        if ($data_entrega < $hoje) {
            array_push($msgs, "A data de entrega não pode ser no passado!");
        }
    }

    if (empty($msgs)) {
        // 2- Inserir o objeto e usamos os SETTERS para abastecer com os dados do formulário
        $novaTarefa = new Tarefa();
        $novaTarefa->setNomeTarefa($nome_tarefa);
        $novaTarefa->setCategoria($categoria);
        $novaTarefa->setDuracaoMinutos($duracao_minutos);
        $novaTarefa->setPrioridade($prioridade);
        $novaTarefa->setDataEntrega($data_entrega);

        $tarefaDAO->inserir($novaTarefa); 

        // 3- Redirecionar para a página de listagem
        header("location: tarefas.php");
        exit; // Boa prática colocar exit após redirecionamento
    } else {
        $msgsErro = implode("<br>", $msgs);
    }
}

// Listagem das tarefas chamando o método do DAO
$tarefas = $tarefaDAO->listar();

// Barra de progresso
$totalTarefas = count($tarefas);
$tarefasAltas = 0;

foreach ($tarefas as $t) {
    if ($t['prioridade'] == 'A') {
        $tarefasAltas++;
    }
}

// Calcula a porcentagem de tarefas altas
$porcentagemAltas = $totalTarefas > 0 ? round(($tarefasAltas / $totalTarefas) * 100) : 0;