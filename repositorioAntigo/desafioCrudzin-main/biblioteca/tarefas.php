<?php

//Exibir erros
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once("util/Conexao.php");

$categorias_permitidas = ["V", "P", "E"];

// Instanciamos o DAO para usar as funções de banco de dados
$tarefaDAO = new TarefaDAO();

$msgsErro = "";
$nome_tarefa = "";
$categoria = "";
$duracao_minutos = "";
$prioridade = "";
$data_entrega = "";

//Salvar o livro

if (isset($_POST['nome_tarefa'])) {

    //1- Receber os dados do formulário

    $nome_tarefa = trim($_POST['nome_tarefa']) ? trim($_POST['nome_tarefa']) : null;


    $categoria = trim($_POST['categoria']) ? trim($_POST['categoria']) : null;

    $duracao_minutos = is_numeric($_POST['duracao_minutos']) ? $_POST['duracao_minutos'] : null;

    $prioridade = trim($_POST['prioridade']) ? trim($_POST['prioridade']) : null;

    $data_entrega = trim($_POST['data_entrega']) ? trim($_POST['data_entrega']) : null;


    //1.1 - Validar os dados
    $msgs = array();

    if (! $nome_tarefa)

        array_push($msgs, "Informe o nome da tarefa!");

    else if (strlen($nome_tarefa) < 3 || strlen($nome_tarefa) > 100)

        array_push($msgs, "O nome da tarefa deve ter entre 3 e 100 caracteres!");


    //verificar se algo está dentro de um array
    if (!in_array($categoria, $categorias_permitidas)) {
        array_push($msgs, "Selecione uma categoria válida!");
    }



    if (! $duracao_minutos) {
        array_push($msgs, "Informe a duração da tarefa");
    } else if ($duracao_minutos <= 0) { // pega negativos e o zero de uma vez só
        array_push($msgs, "Informe minutos válidos (maior que zero)");
    }

    if (! $data_entrega)
        array_push($msgs, "Informe a data de entrega");



    if (empty($msgs)) {


        //2- Inserir o objeto e usamos os SETTERS para abastecer com os dados do formulário
        $novaTarefa = new Tarefa();
        $novaTarefa->setNomeTarefa($nome_tarefa);
        $novaTarefa->setCategoria($categoria);
        $novaTarefa->setDuracaoMinutos($duracao_minutos);
        $novaTarefa->setPrioridade($prioridade);
        $novaTarefa->setDataEntrega($data_entrega);

        $tarefaDAO->inserir($novaTarefa);

        //3- Redirecionar para a página de listagem
        header("location: tarefas.php");
    } else {

        $msgsErro = implode("<br>", $msgs);
    }
}

//Listagem das tarefas chamando o método do DAO
$tarefas = $tarefaDAO->listar();

//echo "<pre>" . print_r($tarefas, true) . "</pre>";

?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de livros</title>
</head>

<body>

    <h1>Cadastro de livros</h1>

    <h3>Listagem</h3>

    <table border="1">
        <!-- Cabeçalho -->
        <tr>
            <th>ID</th>
            <th>Nome da Tarefa</th>
            <th>Categorias Permitidas</th>
            <th>Duração Minutos</th>
            <th>Prioridade</th>
            <th>Data Entrega</th>

            <th></th>
        </tr>

        <!-- Dados -->
        <?php foreach ($tarefas as $t): ?> <tr>
                <td><?= $t["id"] ?></td>
                <td><?= $t["nome_tarefa"] ?></td>

                <td>
                    <?php
                    if ($t['categorias_permitidas'] == 'V') echo "Vestibular";
                    else if ($t['categorias_permitidas'] == 'P') echo "Pessoal";
                    else if ($t['categorias_permitidas'] == 'E') echo "Escola";
                    ?>
                </td>

                <td><?= $t["duracao_minutos"] ?></td>
                <td><?= $t["prioridade"] ?></td>
                <td><?= $t["data_entrega"] ?></td>
                <td>
                    <a href="tarefas_excluir.php?id=<?= $t['id'] ?>"
                        onclick="if(! confirm('Confirma a exclusão?')) return false;">Excluir</a>
                </td>
            </tr>

        <?php endforeach; ?>
    </table>


    <h3>Formulário</h3>

    <!-- form action="" method="POST" onsubmit="return validarForm();" -->
    <form action="" method="POST">

        <input type="text" placeholder="Informe o nome da tarefa"
            name="nome_tarefa" id="nome_tarefa"
            value="<?= $nome_tarefa ?>">

        <br><br>

        <select name="categoria" id="categoria">
            <option value="">---Selecione---</option>
            <option value="V" <?= $categoria == "V" ? "selected" : "" ?>>Vestibular</option>
            <option value="P" <?= $categoria == "P" ? "selected" : "" ?>>Pessoal</option>
            <option value="E" <?= $categoria == "E" ? "selected" : "" ?>>Escola</option>
        </select>





        <br><br>

        <input type="number" name="duracao_minutos" id="duracao_minutos"
            placeholder="Informe a duração em minutos"
            value="<?= isset($duracao_minutos) ? $duracao_minutos : '' ?>">

        <br><br>

        <select name="prioridade" id="prioridade">
            <option value="">---Selecione---</option>
            <option value="B" <?= $prioridade == "B" ? "selected" : "" ?>>Baixa</option>
            <option value="M" <?= $prioridade == "M" ? "selected" : "" ?>>Media</option>
            <option value="A" <?= $prioridade == "A" ? "selected" : "" ?>>Alta</option>
        </select>

        <br><br>


        <label for="data_entrega">Data de Entrega:</label>
        <input type="date" name="data_entrega" id="data_entrega" value="<?= $data_entrega ?>">


        <br><br>

        <button>Gravar</button>

    </form>

    <div id="msgErro" style="color: red; display: none;">
        Exemplo de erro!
    </div>


    <div id="msgErro" style="color: red;">
        <?= $msgsErro ?>
    </div>
    <script src="validacao.js"></script>

</body>

</html>