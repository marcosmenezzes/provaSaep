<?php include 'db.php'; ?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Cadastro de Usuário</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <h1>Cadastro de Usuário</h1>
  <form method="POST" onsubmit="return validarEmail();">
    <input type="text" name="nome" placeholder="Nome" required>
    <input type="email" name="email" id="email" placeholder="Email" required>
    <button type="submit">Cadastrar</button>
  </form>
  <a href="index.php">Voltar</a>
</body>
<script src="scripts.js"></script>
<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
    echo "<script>alert('Email inválido');</script>";
    exit;
  }

  $nome = $_POST['nome'];
  $email = $_POST['email'];

  // Evita erro se a conexão estiver com problema
  if ($conn) {
    $stmt = $conn->prepare("INSERT INTO usuarios (nome, email) VALUES (?, ?)");
    if ($stmt) {
      $stmt->bind_param("ss", $nome, $email);
      if ($stmt->execute()) {
        echo "<script>alert('Cadastro concluído com sucesso');</script>";
      } else {
        echo "<script>alert('Erro ao cadastrar: {$stmt->error}');</script>";
      }
      $stmt->close();
    } else {
      echo "<script>alert('Erro na preparação da query: {$conn->error}');</script>";
    }
  } else {
    echo "<script>alert('Erro na conexão com o banco de dados');</script>";
  }

  $conn->close();
}
?>
</html>
