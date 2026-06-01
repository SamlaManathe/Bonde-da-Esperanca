<?php
include_once __DIR__ . "/../../../config/db.php";
include_once __DIR__ . "/../helpers.php";

$status_atual = "aberto";
if (isset($_GET["status"]) && statusSaidaValido($_GET["status"])) {
    $status_atual = $_GET["status"];
}

$stmt = $conexao->prepare("SELECT * FROM saidas WHERE status = ? ORDER BY data_saida ASC");
$stmt->bind_param("s", $status_atual);
$stmt->execute();
$resultado_busca = $stmt->get_result();

if (executadoDiretamente(__FILE__) && desejaJson()) {
    responderJson([
        "sucesso" => true,
        "status" => $status_atual,
        "saidas" => $resultado_busca->fetch_all(MYSQLI_ASSOC),
    ]);
}
?>
