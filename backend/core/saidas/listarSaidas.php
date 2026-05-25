<?php
include __DIR__ . "/../../../config/db.php";

// Controle das abas Ativas / Arquivadas
$status_atual = "aberto";
if (isset($_GET['status'])) {
    $status_atual = $_GET['status'];
}

// Busca as saídas de acordo com o status
$sql_busca_saidas = "SELECT * FROM saidas WHERE status = '$status_atual'";
$resultado_busca = $conexao->query($sql_busca_saidas);
?>