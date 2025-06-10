<?php
include 'db.php';

$tarefas = $conn->query("SELECT t.*, u.nome AS nome_usuario FROM tarefas t JOIN usuarios u ON t.id_usuario = u.id");
$tarefas_por_status = ["a fazer" => [], "fazendo" => [], "pronto" => []];
while ($t = $tarefas->fetch_assoc()) {
  $tarefas_por_status[$t['status']][] = $t;
}

// Exclusão
if (isset($_GET['excluir'])) {
  $id = intval($_GET['excluir']);
  $conn->query("DELETE FROM tarefas WHERE id = $id");
  echo "<script>location.href='index.php';</script>";
  exit;
}

// Atualização de status
if (isset($_POST['atualizar'])) {
  $id = intval($_POST['id']);
  $status = $_POST['novo_status'];
  if (in_array($status, ['a fazer', 'fazendo', 'pronto'])) {
    $conn->query("UPDATE tarefas SET status = '$status' WHERE id = $id");
  }
  echo "<script>location.href='index.php';</script>";
  exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Gerenciamento de Tarefas</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <style>
    body {
      padding: 20px;
      background-color: #ffffff; /* Preto */
      color: #000000; /* Branco */
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    .kanban {
      display: flex;
      gap: 1rem;
      overflow-x: auto;
    }
    .coluna {
      background: #0056b3; /* Azul */
      border-radius: 8px;
      padding: 1rem;
      min-width: 300px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.5); /* Sombra preta para contraste */
      flex-shrink: 0;
      color: #FFFFFF; /* Texto branco na coluna */
    }
    .card {
      margin-bottom: 1rem;
      background-color: #FFFFFF; /* Branco */
      color: #000000; /* Texto preto no card */
      border: 1px solid #0056b3; /* Borda azul */
    }
    /* Cores dos botões e badges ajustadas para as cores permitidas */
    .btn-primary, .btn-success {
        background-color: #0056b3; /* Azul */
        border-color: #0056b3; /* Azul */
        color: #FFFFFF; /* Branco */
    }
    .btn-outline-primary {
        color: #0056b3; /* Azul */
        border-color: #0056b3; /* Azul */
    }
    .btn-outline-primary:hover {
        background-color: #0056b3; /* Azul */
        color: #FFFFFF; /* Branco */
    }
    .btn-outline-secondary { /* Usado para o botão 'Editar' */
        color: #000000; /* Preto */
        border-color: #000000; /* Preto */
    }
    .btn-outline-secondary:hover {
        background-color: #000000; /* Preto */
        color: #FFFFFF; /* Branco */
    }
    .btn-outline-danger {
        color: #000000; /* Preto */
        border-color: #000000; /* Preto */
    }
    .btn-outline-danger:hover {
        background-color: #000000; /* Preto */
        color: #FFFFFF; /* Branco */
    }
    .badge {
        color: #FFFFFF; /* Texto branco para badges */
    }
    .badge.bg-danger { /* Prioridade alta */
        background-color: #000000 !important; /* Preto */
    }
    .badge.bg-warning.text-dark { /* Prioridade média */
        background-color: #0056b3 !important; /* Azul */
        color: #FFFFFF !important; /* Branco */
    }
    .badge.bg-secondary { /* Prioridade baixa */
        background-color: #000000 !important; /* Preto */
    }
    .text-muted {
        color: #FFFFFF !important; /* Branco para texto 'Nenhuma tarefa' */
    }
    .form-select {
        background-color: #FFFFFF; /* Fundo branco */
        color: #000000; /* Texto preto */
        border-color: #0056b3; /* Borda azul */
    }
    .form-select option {
        background-color: #FFFFFF; /* Fundo branco */
        color: #000000; /* Texto preto */
    }
  </style>
</head>
<body>
  <h1 class="mb-4">Gerenciamento de Tarefas</h1>
  <nav class="mb-4">
    <a href="usuarios.php" class="btn btn-primary me-2">Cadastrar Usuário</a>
    <a href="tarefas.php" class="btn btn-primary">Cadastrar Tarefa</a>
  </nav>

  <div class="kanban">
    <?php foreach (["a fazer", "fazendo", "pronto"] as $status): ?>
      <div class="coluna">
        <h3 class="text-center text-capitalize mb-4"><?= $status ?></h3>
        <?php if (empty($tarefas_por_status[$status])): ?>
          <p class="text-muted text-center">Nenhuma tarefa nesta coluna.</p>
        <?php endif; ?>

        <?php foreach ($tarefas_por_status[$status] as $t): ?>
          <div class="card shadow-sm">
            <div class="card-body">
              <h5 class="card-title text-truncate"><?= htmlspecialchars($t['descricao']) ?></h5>
              <p class="card-text mb-1"><strong>Setor:</strong> <?= htmlspecialchars($t['setor']) ?></p>
              <p class="card-text mb-1"><strong>Prioridade:</strong> 
                <span class="badge 
                  <?php 
                    echo $t['prioridade'] === 'alta' ? 'bg-danger' : ($t['prioridade'] === 'média' ? 'bg-warning text-dark' : 'bg-secondary');
                  ?>">
                  <?= htmlspecialchars(ucfirst($t['prioridade'])) ?>
                </span>
              </p>
              <p class="card-text mb-3"><strong>Usuário:</strong> <?= htmlspecialchars($t['nome_usuario']) ?></p>

              <form method="POST" class="d-flex align-items-center gap-2 mb-3">
                <select name="novo_status" class="form-select form-select-sm w-auto" aria-label="Alterar status">
                  <option value="a fazer" <?= $t['status'] === 'a fazer' ? 'selected' : '' ?>>A Fazer</option>
                  <option value="fazendo" <?= $t['status'] === 'fazendo' ? 'selected' : '' ?>>Fazendo</option>
                  <option value="pronto" <?= $t['status'] === 'pronto' ? 'selected' : '' ?>>Pronto</option>
                </select>
                <input type="hidden" name="id" value="<?= $t['id'] ?>">
                <button type="submit" name="atualizar" class="btn btn-sm btn-outline-primary">Alterar</button>
              </form>

              <div class="d-flex justify-content-between">
                <a href="tarefas.php?id=<?= $t['id'] ?>" class="btn btn-sm btn-outline-secondary flex-grow-1 me-1">Editar</a>
                <a href="?excluir=<?= $t['id'] ?>" 
                   onclick="return confirm('Deseja realmente excluir esta tarefa?');" 
                   class="btn btn-sm btn-outline-danger flex-grow-1 ms-1">Excluir</a>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endforeach; ?>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>