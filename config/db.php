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

$conexao_temp->set_charset("utf8mb4");
$conexao_temp->query("CREATE DATABASE IF NOT EXISTS `$db` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");

$conexao_temp->close();

$conexao = new mysqli($host, $user, $pass, $db, $port);

if ($conexao->connect_error) {

    die ("Erro na conexão como o banco: " . $conexao->connect_error);

}

$conexao->set_charset("utf8mb4");

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

if (!function_exists("criarIndiceSeNaoExistir")) {
    function criarIndiceSeNaoExistir($conexao, $tabela, $indice, $sql) {
        $resultado = $conexao->query("SHOW INDEX FROM `$tabela` WHERE Key_name = '$indice'");

        if ($resultado && $resultado->num_rows === 0) {
            $conexao->query($sql);
        }
    }
}

criarIndiceSeNaoExistir($conexao, "saidas", "idx_saidas_status", "ALTER TABLE saidas ADD INDEX idx_saidas_status (status)");
criarIndiceSeNaoExistir($conexao, "voluntarios", "idx_voluntarios_saida", "ALTER TABLE voluntarios ADD INDEX idx_voluntarios_saida (saida_id)");

/*
se caso precisar popular o banco para testes:

-- ==========================================
-- TABELA DE SAÍDAS
-- ==========================================
INSERT INTO saidas (data_saida, prazo_limite_inscricao, status, criado_em) VALUES
('2026-07-15', '2026-07-10 23:59:59', 'aberto', '2026-05-01 10:00:00'),
('2026-06-01', '2026-05-20 18:00:00', 'encerrado', '2026-04-15 09:30:00'),
('2026-08-20', '2026-08-15 23:59:59', 'aberto', '2026-05-20 14:00:00'),
('2026-04-10', '2026-04-05 23:59:59', 'encerrado', '2026-03-01 08:00:00');

-- ==========================================
-- TABELA DE VOLUNTÁRIOS
-- ==========================================
INSERT INTO voluntarios (saida_id, nome, telefone, inscrito_em) VALUES
(1, 'Carlos Silva', '(11) 99999-1111', '2026-05-02 11:15:00'),
(1, 'Mariana Costa', '(21) 98888-2222', '2026-05-10 14:30:00'),
(2, 'Ana Souza', '(31) 97777-3333', '2026-04-20 16:00:00'),
(2, 'Bruno Alves', '(41) 96666-4444', '2026-05-15 10:22:00'),
(2, 'Julia Mendes', '(51) 95555-5555', '2026-05-19 17:45:00'),
(3, 'Ricardo Oliveira', '11944443333', '2026-05-21 09:00:00'),
(4, 'Fernando Dias', '(81) 93333-2222', '2026-03-15 11:00:00'),
(4, 'Beatriz Santos', '(11) 92222-1111', '2026-03-20 15:30:00');
*/

// echo "Banco e tabelas configurados com sucesso na porta 3307!";

?>
