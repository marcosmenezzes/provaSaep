CREATE DATABASE dbgest�otarefa
ON PRIMARY(
NAME = dbgest�otarefa,
FILENAME = 'C:\BD\dbgest�otarefa.mdf',
SIZE = 100MB,
MAXSIZE = 500MB,
FILEGROWTH = 5%);

GO
USE dbgest�otarefa;
GO

CREATE TABLE usuario(
id INT PRIMARY KEY IDENTITY(1,1),
nome VARCHAR(30)NOT NULL,
email VARCHAR(30)NOT NULL
);

CREATE TABLE tarefa(
id INT PRIMARY KEY IDENTITY(1,1),
idUsuario INT NOT NULL,
descricao VARCHAR(MAX) NOT NULL,
setor VARCHAR(30) NOT NULL,
prioridade VARCHAR(10)NOT NULL,
datacadastro DATETIME NOT NULL,
situacao VARCHAR(10) DEFAULT 'A Fazer' NOT NULL,
FOREIGN KEY(idUsuario) REFERENCES usuario(id) ON DELETE CASCADE 
);