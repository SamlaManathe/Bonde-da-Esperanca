<?php
include_once __DIR__ . "/../../../config/db.php";
include_once __DIR__ . "/../helpers.php";

$saida_id = validarId($_GET["saida_id"] ?? null);
$status = limparTexto($_GET["status"] ?? "");
$busca = limparTexto($_GET["busca"] ?? "");

$sql = "
    SELECT
        v.id,
        v.saida_id,
        v.nome,
        v.telefone,
        v.inscrito_em,
        s.data_saida,
        s.status,
        s.prazo_limite_inscricao
    FROM voluntarios v
    INNER JOIN saidas s ON s.id = v.saida_id
    WHERE 1 = 1
";
$tipos = "";
$parametros = [];

if ($saida_id) {
    $sql .= " AND v.saida_id = ?";
    $tipos .= "i";
    $parametros[] = $saida_id;
}

if (statusSaidaValido($status)) {
    $sql .= " AND s.status = ?";
    $tipos .= "s";
    $parametros[] = $status;
}

if ($busca !== "") {
    $buscaLike = "%" . $busca . "%";
    $sql .= " AND (v.nome LIKE ? OR v.telefone LIKE ?)";
    $tipos .= "ss";
    $parametros[] = $buscaLike;
    $parametros[] = $buscaLike;
}

$sql .= " ORDER BY s.data_saida DESC, v.nome ASC";

$stmt = $conexao->prepare($sql);
if ($tipos !== "") {
    $stmt->bind_param($tipos, ...$parametros);
}
$stmt->execute();
$resultado_busca_voluntarios = $stmt->get_result();

if (executadoDiretamente(__FILE__) || desejaJson()) {
    responderJson([
        "sucesso" => true,
        "voluntarios" => $resultado_busca_voluntarios->fetch_all(MYSQLI_ASSOC),
    ]);
}
?>
