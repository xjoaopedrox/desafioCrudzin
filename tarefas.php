<?php

date_default_timezone_set('America/Sao_Paulo');

//Exibir erros
ini_set('display_errors', 1);
error_reporting(E_ALL);



require_once("util/Conexao.php");
require_once("dao/TarefaDAO.php");

$categorias_permitidas = ["V", "P", "E"];

// Instanciamos o DAO para usar as funções de banco de dados
$tarefaDAO = new TarefaDAO();


$msgsErro = "";
$nome_tarefa = "";
$categoria = "";
$duracao_minutos = "";
$prioridade = "";
$data_entrega = "";

//Salvar a tarefa
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

//barra
$totalTarefas = count($tarefas);
$tarefasAltas = 0;

foreach ($tarefas as $t) {
    if ($t['prioridade'] == 'A') {
        $tarefasAltas++;
    }
}

// Calcula a porcentagem de tarefas altas (evitando divisão por zero se o banco estiver vazio)
$porcentagemAltas = $totalTarefas > 0 ? round(($tarefasAltas / $totalTarefas) * 100) : 0;



?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciador de Tarefas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.
    min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>
<body class="bg-light">

    <header class="bg-dark text-white py-4 mb-5 shadow-sm">
        <div class="container text-center">
            <h1 class="h2 m-0">Gerenciador de Estudos</h1>
            <?php include("component/relogio.html"); ?>
        </div>
    </header>

    <main class="container">
        
        <div class="row mb-5">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="text-secondary small fw-bold text-uppercase tracking-wider">
                                Alerta de Sobrecarga de altas tarefas
                            </span>
                            <span class="text-danger small fw-bold fs-6"><?= $porcentagemAltas ?>%</span>
                        </div>
                        <div class="progress" style="height: 12px;">
                            <div class="progress-bar bg-danger progress-bar-striped progress-bar-animated" 
                                 role="progressbar" 
                                 style="width: <?= $porcentagemAltas ?>%;" 
                                 aria-valuenow="<?= $porcentagemAltas ?>" 
                                 aria-valuemin="0" 
                                 aria-valuemax="100">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-12">
                <div id="msgErro" class="alert alert-danger" style="display: none;" role="alert"></div>
                
                <?php if (!empty($msgsErro)): ?>
                    <div class="alert alert-danger" role="alert">
                        <?= $msgsErro ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-12">
                <div id="msgErro" class="alert alert-danger" style="display: none;" role="alert"></div>
                
                <?php if (!empty($msgsErro)): ?>
                    <div class="alert alert-danger" role="alert">
                        <?= $msgsErro ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="row g-4">
            
            <div class="col-lg-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4">
                        <h3 class="h5 card-title text-dark mb-4 pb-2 border-bottom">Nova Tarefa</h3>
                        
                        <form action="" method="POST" onsubmit="return validarForm();">
                            
                            <div class="mb-3">
                                <label for="nome_tarefa" class="form-label text-secondary small">Nome da Tarefa</label>
                                <input type="text" class="form-control" placeholder="Ex: Estudar como estudar"
                                    name="nome_tarefa" id="nome_tarefa" value="<?= $nome_tarefa ?>">
                            </div>

                            <div class="mb-3">
                                <label for="categoria" class="form-label text-secondary small">Categoria</label>
                                <select name="categoria" id="categoria" class="form-select">
                                    <option value="">---Selecione---</option>
                                    <option value="V" <?= $categoria == "V" ? "selected" : "" ?>>Vestibular</option>
                                    <option value="P" <?= $categoria == "P" ? "selected" : "" ?>>Pessoal</option>
                                    <option value="E" <?= $categoria == "E" ? "selected" : "" ?>>Escola</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="duracao_minutos" class="form-label text-secondary small">Duração (minutos)</label>
                                <input type="number" class="form-control" name="duracao_minutos" id="duracao_minutos"
                                    placeholder="Ex: 60" value="<?= isset($duracao_minutos) ? $duracao_minutos : '' ?>">
                            </div>

                            <div class="mb-3">
                                <label for="prioridade" class="form-label text-secondary small">Prioridade</label>
                                <select name="prioridade" id="prioridade" class="form-select">
                                    <option value="">---Selecione---</option>
                                    <option value="B" <?= $prioridade == "B" ? "selected" : "" ?>>Baixa</option>
                                    <option value="M" <?= $prioridade == "M" ? "selected" : "" ?>>Média</option>
                                    <option value="A" <?= $prioridade == "A" ? "selected" : "" ?>>Alta</option>
                                </select>
                            </div>

                            <div class="mb-4">
                                <label for="data_entrega" class="form-label text-secondary small">Data de Entrega</label>
                                <input type="date" class="form-control" name="data_entrega" id="data_entrega" value="<?= $data_entrega ?>">
                            </div>

                            <button type="submit" class="btn btn-primary w-100 shadow-sm fw-semibold">Gravar Tarefa</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="card shadow-sm border-0 mb-5">
                    <div class="card-body p-4">
                        <h3 class="h5 card-title text-dark mb-4 pb-2 border-bottom">Listagem de Tarefas</h3>
                        
                        <div class="table-responsive">
                            <table class="table table-striped table-hover align-middle table-bordered m-0">
                                <thead class="table-dark">
                                    <tr>
                                        <th>ID</th>
                                        <th>Nome da Tarefa</th>
                                        <th>Categoria</th>
                                        <th>Duração</th>
                                        <th>Prioridade</th>
                                        <th>Data Entrega</th>
                                        <th class="text-center">Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($tarefas)): ?>
                                        <tr>
                                            <td colspan="7" class="text-center text-muted py-3">Nenhuma tarefa cadastrada.</td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($tarefas as $t): ?> 
                                            <tr>
                                                <td class="fw-bold text-secondary"><?= $t["id"] ?></td>
                                                <td><?= $t["nome_tarefa"] ?></td>
                                                <td>
                                                    <?php
                                                    if ($t['categorias_permitidas'] == 'V') echo "Vestibular";
                                                    else if ($t['categorias_permitidas'] == 'P') echo "Pessoal";
                                                    else if ($t['categorias_permitidas'] == 'E') echo "Escola";
                                                    ?>
                                                </td>
                                                <td><?= $t["duracao_minutos"] ?> min</td>
                                                <td>
                                                    <?php 
                                                        $cor_da_etiqueta = "bg-secondary";
                                                        if($t["prioridade"] == "A") $cor_da_etiqueta = "bg-danger";
                                                        if($t["prioridade"] == "M") $cor_da_etiqueta = "bg-warning text-dark";
                                                        if($t["prioridade"] == "B") $cor_da_etiqueta = "bg-success";
                                                    ?>
                                                    <span class="badge <?= $cor_da_etiqueta ?>"><?= $t["prioridade"] ?></span>
                                                </td>
                                                <td><?= date("d/m/Y", strtotime($t["data_entrega"])) ?></td>
                                                <td class="text-center">
                                                    <a href="tarefas_excluir.php?id=<?= $t['id'] ?>"
                                                       class="btn btn-sm btn-outline-danger"
                                                       onclick="if(! confirm('Confirma a exclusão?')) return false;">Excluir</a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <h3 class="h5 text-dark mb-3 pb-2 border-bottom">Visualização em Cards</h3>
                <div class="row row-cols-1 row-cols-md-2 g-3 mb-5">
                    <?php foreach ($tarefas as $t): ?>
                        <?php
                            // Lógica de cores do baseado na prioridade
                            $cor_da_borda = "border-secondary";
                            $cor_da_etiqueta = "bg-secondary";
                            if ($t['prioridade'] == 'A') { $cor_da_borda = 'border-danger'; $cor_da_etiqueta = 'bg-danger'; }
                            else if ($t['prioridade'] == 'M') { $cor_da_borda = 'border-warning'; $cor_da_etiqueta = 'bg-warning text-dark'; }
                            else if ($t['prioridade'] == 'B') { $cor_da_borda = 'border-success'; $cor_da_etiqueta = 'bg-success'; }
                        ?>
                        <div class="col">
                            <div class="card h-100 border-start border-4 <?= $cor_da_borda ?> shadow-sm">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h5 class="card-title h6 m-0 text-truncate" style="max-width: 75%;"><?= $t["nome_tarefa"] ?></h5>
                                        <span class="badge <?= $cor_da_etiqueta ?>">Prioridade <?= $t["prioridade"] ?></span>
                                    </div>
                                    <p class="card-text small my-1">
                                        <strong>Categoria:</strong> 
                                        <?php
                                        if ($t['categorias_permitidas'] == 'V') echo "Vestibular";
                                        else if ($t['categorias_permitidas'] == 'P') echo "Pessoal";
                                        else if ($t['categorias_permitidas'] == 'E') echo "Escola";
                                        ?>
                                    </p>
                                    <p class="card-text small my-1"><strong>Duração:</strong> <?= $t["duracao_minutos"] ?> minutos</p>
                                    <p class="card-text small my-1 text-muted"><strong>Entrega:</strong> <?= date("d/m/Y", strtotime($t["data_entrega"])) ?></p>
                                </div>
                                <div class="card-footer bg-transparent border-0 pt-0 text-end">
                                    <a href="tarefas_excluir.php?id=<?= $t['id'] ?>" class="text-danger small text-decoration-none fw-semibold" onclick="if(! confirm('Confirma a exclusão?')) return false;">Excluir</a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="validacao.js"></script>


  <!--                                                                       
                                                                 
                                  @@@@@@@@@@@@@@@@@@                  
                                  @@@@@@@@@@@@@@@@@@                  
                                @@@@mm@@@@@@@@@@@@@@@@                
                                @@@@  @@@@@@@@@@  @@@@                
                                @@@@@@@@@@@@@@@@@@@@@@                
                                @@@@@@@@@@@@@@@@@@@@@@                
                                  @@@ @@@@@@@@@@ @@@                  
                                  @@@@  @@@@@@   @@@                  
                                  @@@@@@      @@@@@@                
                                  @@@@@@@@@@@@@@@@@@                
                                    @@@@@@@@@@@@@@     @   @              
                                @@@@@@@@@@@@@@@@@@       @            
                                @@@@@@@@@@@@@@@@@@      @@                  
                            @@@@@@@@@@@@@@@@@@@@@@@@@@@@@          
          @@@@@@@@@@      @@@@@@@@@@@@@@@@@@@@@@@@                    
      @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@                    
      @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@                    
             @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@                    
                @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@                    
                  @@@@@@@@@@@@@@@@@@@@@@@@@@@@                     
                  @@@@@@@@@@@@@@@@@@@@@@@@@@@@                      
                    @@@@@@@@@@@@@@@@@@@@@@@@                          
                        @@@@@@@@@@@@@@@@@@                            
                          @@@@@@@@  @@@@@@                            
                          @@@@@@        @@      
                          @@@@@@        @@                            
                          @@            @@                            
                          @@@@@@        @@@@                          
                                                            
                                                                      
-->                                                      
</body>
</html>




                                                                      
                                                                      
