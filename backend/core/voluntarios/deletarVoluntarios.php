<?php

include_once __DIR__ . "/../../../config/db.php";
include_once __DIR__ . "/../helpers.php";

$voltar = "../../../frontend/pages/voluntarios.php";
exigirPost($voltar);

$id = validarId($_POST["id"] ?? null);
if (!$id) {
    responderErro("ID do voluntário inválido.", $voltar);
}

$stmt = $conexao->prepare("DELETE FROM voluntarios WHERE id = ?");
$stmt->bind_param("i", $id);

if (!$stmt->execute()) {
    responderErro("Erro ao deletar voluntário: " . $conexao->error, $voltar, 500);
}

responderSucesso("Voluntário deletado com sucesso.", [], $voltar);
?>

