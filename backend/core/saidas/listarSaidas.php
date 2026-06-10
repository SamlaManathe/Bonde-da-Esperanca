<?php
include_once __DIR__ . "/../../../config/db.php";
include_once __DIR__ . "/../helpers.php";

$status_atual = "aberto";
if (isset($_GET["status"]) && statusSaidaValido($_GET["status"])) {
    $status_atual = $_GET["status"];
}

$busca = limparTexto($_GET["busca"] ?? "");

$sql = "SELECT * FROM saidas WHERE status = ?";
$tipos = "s";
$parametros = [$status_atual];

if ($busca !== "") {
    $sql .= " AND (DATE_FORMAT(data_saida, '%d/%m/%Y') LIKE ? OR status LIKE ? OR prazo_limite_inscricao LIKE ?)";
    $buscaLike = "%{$busca}%";
    $tipos .= "sss";
    $parametros[] = $buscaLike;
    $parametros[] = $buscaLike;
    $parametros[] = $buscaLike;
}

$sql .= " ORDER BY data_saida ASC";
$stmt = $conexao->prepare($sql);
$stmt->bind_param($tipos, ...$parametros);
$stmt->execute();
$resultado_busca = $stmt->get_result();

if (executadoDiretamente(__FILE__) && desejaJson()) {
    responderJson([
        "sucesso" => true,
        "status" => $status_atual,
        "busca" => $busca,
        "saidas" => $resultado_busca->fetch_all(MYSQLI_ASSOC),
    ]);
}
?>
