<?php

$host = "localhost";
$port = 3307;
$db = "db_bonde_da_esperanca";
$user = "root";
$pass = "";

$conexao_temp = new mysqli($host, $user, $pass, "", $port);

if ($conexao_temp->connect_error) {

    die("Erro na conexão temporária: " . $conexao_temp->connect_error);

}

$conexao_temp->query("CREATE DATABASE IF NOT EXISTS $db");

$conexao_temp->close();

$conexao = new mysqli($host, $user, $pass, $db, $port);

if ($conexao->connect_error) {

    die ("Erro na conexão como o banco: " . $conexao->connect_error);

}

$sql_saidas = "

    CREATE TABLE IF NOT EXISTS saidas (
        id INT NOT NULL AUTO_INCREMENT,
        data_saida DATE NOT NULL,
        prazo_limite_inscricao DATETIME NOT NULL,
        status ENUM('aberto', 'encerrado') NOT NULL,
        criado_em DATETIME NOT NULL,

        PRIMARY KEY (id)
    );

";

$sql_voluntarios = "

    CREATE TABLE IF NOT EXISTS voluntarios (
        id INT NOT NULL AUTO_INCREMENT,
        saida_id INT NOT NULL,
        nome VARCHAR(255) NOT NULL,
        telefone VARCHAR(20) NOT NULL,
        inscrito_em DATETIME NOT NULL,

        PRIMARY KEY (id),
        FOREIGN KEY (saida_id) REFERENCES saidas(id) ON DELETE CASCADE
    );

";

$conexao->query($sql_saidas);
$conexao->query($sql_voluntarios);

// echo "Banco e tabelas configurados com sucesso na porta 3307!";

?>