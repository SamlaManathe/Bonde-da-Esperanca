<?php
include_once __DIR__ . "/../../../config/db.php";
include_once __DIR__ . "/../helpers.php";

$voltar = "../../../index.php";
exigirPost($voltar);

$nome = limparTexto($_POST["nome"] ?? "");
$telefone = normalizarTelefone($_POST["telefone"] ?? "");
$saida_id = validarId($_POST["saida_id"] ?? null);

if (strlen($nome) < 3) {
    responderErro("Informe o nome completo do voluntário.", $voltar);
}

if (!telefoneValido($telefone)) {
    responderErro("Informe um telefone válido.", $voltar);
}

$saida = $saida_id ? buscarSaidaPorId($conexao, $saida_id) : buscarProximaSaidaAberta($conexao);
if (!$saida) {
    responderErro("Não há saída disponível para inscrição no momento.", $voltar, 404);
}

if (!saidaAceitaInscricao($saida)) {
    responderErro("As inscrições para esta saída estão encerradas.", $voltar);
}

$saida_id = (int) $saida["id"];

$stmtDuplicado = $conexao->prepare("
    SELECT id
    FROM voluntarios
    WHERE saida_id = ? AND telefone = ?
    LIMIT 1
");
$stmtDuplicado->bind_param("is", $saida_id, $telefone);
$stmtDuplicado->execute();

if ($stmtDuplicado->get_result()->num_rows > 0) {
    responderErro("Este telefone já está inscrito nessa saída.", $voltar, 409);
}

$stmt = $conexao->prepare("
    INSERT INTO voluntarios (saida_id, nome, telefone, inscrito_em)
    VALUES (?, ?, ?, NOW())
");
$stmt->bind_param("iss", $saida_id, $nome, $telefone);

if (!$stmt->execute()) {
    responderErro("Erro ao criar voluntário: " . $conexao->error, $voltar, 500);
}

responderSucesso("Inscrição realizada com sucesso.", ["id" => $conexao->insert_id], $voltar . "?inscricao=sucesso");
?>

