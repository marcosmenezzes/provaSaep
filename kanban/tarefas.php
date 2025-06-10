<?php
include 'db.php';

$id = $_GET['id'] ?? null;
$tarefa = null;

if ($id) {
    // IMPORTANTE: Evitar SQL Injection usando prepared statements
    $stmt = $conn->prepare("SELECT * FROM tarefas WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $res = $stmt->get_result();
    $tarefa = $res->fetch_assoc();
    $stmt->close();
}

// Buscar usuários para o select (sem problemas, pois não recebe input do usuário)
$usuarios = $conn->query("SELECT * FROM usuarios");
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Cadastro de Tarefa</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <h1><?= $id ? 'Editar Tarefa' : 'Cadastrar Tarefa' ?></h1>
  <form method="POST">
    <select name="id_usuario" required>
      <option value="">Selecione o usuário</option>
      <?php while ($u = $usuarios->fetch_assoc()): ?>
        <option value="<?= $u['id'] ?>" <?= $tarefa && $u['id'] == $tarefa['id_usuario'] ? 'selected' : '' ?>>
          <?= htmlspecialchars($u['nome']) ?>
        </option>
      <?php endwhile; ?>
    </select>
    <input type="text" name="descricao" placeholder="Descrição" required value="<?= htmlspecialchars($tarefa['descricao'] ?? '') ?>">
    <input type="text" name="setor" placeholder="Setor" required value="<?= htmlspecialchars($tarefa['setor'] ?? '') ?>">
    <select name="prioridade" required>
      <option value="baixa" <?= $tarefa && $tarefa['prioridade'] == 'baixa' ? 'selected' : '' ?>>Baixa</option>
      <option value="média" <?= $tarefa && $tarefa['prioridade'] == 'média' ? 'selected' : '' ?>>Média</option>
      <option value="alta" <?= $tarefa && $tarefa['prioridade'] == 'alta' ? 'selected' : '' ?>>Alta</option>
    </select>
    <button type="submit">Salvar</button>
  </form>
  <a href="index.php">Voltar</a>
</body>
<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Pega os dados com segurança
    $id_usuario = $_POST['id_usuario'];
    $descricao = $_POST['descricao'];
    $setor = $_POST['setor'];
    $prioridade = $_POST['prioridade'];

    if ($id) {
        // UPDATE com prepared statement para evitar SQL injection
        $stmt = $conn->prepare("UPDATE tarefas SET id_usuario=?, descricao=?, setor=?, prioridade=? WHERE id=?");
        $stmt->bind_param("isssi", $id_usuario, $descricao, $setor, $prioridade, $id);
        $stmt->execute();
        $stmt->close();
    } else {
        $data = date('Y-m-d');
        // INSERT com prepared statement
        $stmt = $conn->prepare("INSERT INTO tarefas (id_usuario, descricao, setor, prioridade, data_cadastro) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("issss", $id_usuario, $descricao, $setor, $prioridade, $data);
        $stmt->execute();
        $stmt->close();
    }

    echo "<script>alert('Cadastro concluído com sucesso'); window.location='index.php';</script>";
}
?>
</html>
<?php
$conn->close();