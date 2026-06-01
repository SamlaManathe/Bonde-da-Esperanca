<?php
include_once "../../../config/db.php";
include_once __DIR__ . "/../helpers.php";

$voltar = "../../../frontend/pages/semanas.php";
exigirPost($voltar);

$data_saida = limparTexto($_POST["data_saida"] ?? "");
$prazo_inscricao = limparTexto($_POST["prazo_inscricao"] ?? "");
$status = limparTexto($_POST["status"] ?? "");

if (!validarData($data_saida)) {
    responderErro("Informe uma data de saída válida.", $voltar);
}

$prazo_formatado = validarDataHora($prazo_inscricao);
if (!$prazo_formatado) {
    responderErro("Informe um prazo de inscrição válido.", $voltar);
}

if (!prazoAntesDaSaida($data_saida, $prazo_formatado)) {
    responderErro("O prazo de inscrição não pode ser depois da data da saída.", $voltar);
}

if (!statusSaidaValido($status)) {
    responderErro("Status da saída inválido.", $voltar);
}

$stmt = $conexao->prepare("
    INSERT INTO saidas (data_saida, prazo_limite_inscricao, status, criado_em)
    VALUES (?, ?, ?, NOW())
");
$stmt->bind_param("sss", $data_saida, $prazo_formatado, $status);

if (!$stmt->execute()) {
    responderErro("Erro ao criar saída: " . $conexao->error, $voltar, 500);
}

responderSucesso("Saída criada com sucesso.", ["id" => $conexao->insert_id], $voltar);
?>
