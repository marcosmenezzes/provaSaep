CREATE DATABASE kanban;
USE kanban;

CREATE TABLE usuarios (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(100) NOT NULL,
  email VARCHAR(100) NOT NULL
);

CREATE TABLE tarefas (
  id INT AUTO_INCREMENT PRIMARY KEY,
  id_usuario INT NOT NULL,
  descricao TEXT NOT NULL,
  setor VARCHAR(100) NOT NULL,
  prioridade ENUM('baixa', 'média', 'alta') NOT NULL,
  data_cadastro DATE NOT NULL,
  status ENUM('a fazer', 'fazendo', 'pronto') NOT NULL DEFAULT 'a fazer',
  FOREIGN KEY (id_usuario) REFERENCES usuarios(id)
);
