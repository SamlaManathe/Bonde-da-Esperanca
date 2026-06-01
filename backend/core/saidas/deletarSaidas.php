<?php
include_once "../../../config/db.php";
include_once __DIR__ . "/../helpers.php";

$voltar = "../../../frontend/pages/semanas.php";
exigirPost($voltar);

$id = validarId($_POST["id"] ?? null);
if (!$id) {
    responderErro("ID da saída inválido.", $voltar);
}

$stmt = $conexao->prepare("DELETE FROM saidas WHERE id = ?");
$stmt->bind_param("i", $id);

if (!$stmt->execute()) {
    responderErro("Erro ao deletar saída: " . $conexao->error, $voltar, 500);
}

responderSucesso("Saída deletada com sucesso.", [], $voltar);
?>
