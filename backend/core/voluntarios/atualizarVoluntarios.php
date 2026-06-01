<?php
include_once __DIR__ . "/../../../config/db.php";
include_once __DIR__ . "/../helpers.php";

$voltar = "../../../frontend/pages/voluntarios.php";
$id = validarId($_GET["id"] ?? $_POST["id"] ?? null);

if (!$id) {
    responderErro("ID do voluntário inválido.", $voltar);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nome = limparTexto($_POST["nome"] ?? "");
    $telefone = normalizarTelefone($_POST["telefone"] ?? "");
    $saida_id = validarId($_POST["saida_id"] ?? null);

    if (strlen($nome) < 3) {
        responderErro("Informe o nome completo do voluntário.", $voltar);
    }

    if (!telefoneValido($telefone)) {
        responderErro("Informe um telefone válido.", $voltar);
    }

    if (!$saida_id || !buscarSaidaPorId($conexao, $saida_id)) {
        responderErro("Saída informada não existe.", $voltar);
    }

    $stmtDuplicado = $conexao->prepare("
        SELECT id
        FROM voluntarios
        WHERE saida_id = ? AND telefone = ? AND id <> ?
        LIMIT 1
    ");
    $stmtDuplicado->bind_param("isi", $saida_id, $telefone, $id);
    $stmtDuplicado->execute();

    if ($stmtDuplicado->get_result()->num_rows > 0) {
        responderErro("Este telefone já está inscrito nessa saída.", $voltar, 409);
    }

    $stmt = $conexao->prepare("
        UPDATE voluntarios
        SET saida_id = ?, nome = ?, telefone = ?
        WHERE id = ?
    ");
    $stmt->bind_param("issi", $saida_id, $nome, $telefone, $id);

    if (!$stmt->execute()) {
        responderErro("Erro ao atualizar voluntário: " . $conexao->error, $voltar, 500);
    }

    responderSucesso("Voluntário atualizado com sucesso.", [], $voltar);
}

$stmt = $conexao->prepare("SELECT * FROM voluntarios WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$voluntario = $stmt->get_result()->fetch_assoc();

if (!$voluntario) {
    responderErro("Voluntário não encontrado.", $voltar, 404);
}

$saidas = $conexao->query("SELECT id, data_saida, status FROM saidas ORDER BY data_saida DESC");
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Atualizar Voluntário</title>
    <link rel="stylesheet" href="../../../frontend/css/style.css">
</head>
<body>
    <main class="conteudo" style="margin-left: 0; padding: 30px;">
        <h1>Atualizar voluntário</h1>

        <form method="post" action="atualizarVoluntarios.php?id=<?php echo $id; ?>">
            <div class="modal-grupo">
                <label>Nome completo</label>
                <input type="text" name="nome" value="<?php echo escaparHtml($voluntario["nome"]); ?>" required>
            </div>

            <div class="modal-grupo">
                <label>Telefone</label>
                <input type="text" name="telefone" value="<?php echo escaparHtml($voluntario["telefone"]); ?>" required>
            </div>

            <div class="modal-grupo">
                <label>Saída</label>
                <select name="saida_id" required>
                    <?php while ($saida = $saidas->fetch_assoc()) { ?>
                        <option value="<?php echo (int) $saida["id"]; ?>" <?php echo (int) $saida["id"] === (int) $voluntario["saida_id"] ? "selected" : ""; ?>>
                            <?php echo escaparHtml(date("d/m/Y", strtotime($saida["data_saida"])) . " - " . ucfirst($saida["status"])); ?>
                        </option>
                    <?php } ?>
                </select>
            </div>

            <button type="submit" class="btn-texto verde">Salvar alterações</button>
        </form>
    </main>
</body>
</html>
