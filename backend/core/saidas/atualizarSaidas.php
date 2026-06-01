<?php
    include_once __DIR__ . "/../../../config/db.php";
    include_once __DIR__ . "/../helpers.php";

    $id = validarId($_GET["id"] ?? $_POST["id"] ?? null);

    if (!$id) {
        responderErro("ID não informado.", "../../../frontend/pages/semanas.php");
    }

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $data_saida = limparTexto($_POST["data_saida"] ?? "");
        $prazo_inscricao = limparTexto($_POST["prazo_inscricao"] ?? "");
        $status = limparTexto($_POST["status"] ?? "");

        if (!validarData($data_saida)) {
            responderErro("Informe uma data de saída válida.");
        }

        $prazo_formatado = validarDataHora($prazo_inscricao);
        if (!$prazo_formatado) {
            responderErro("Informe um prazo de inscrição válido.");
        }

        if (!prazoAntesDaSaida($data_saida, $prazo_formatado)) {
            responderErro("O prazo de inscrição não pode ser depois da data da saída.");
        }

        if (!statusSaidaValido($status)) {
            responderErro("Status da saída inválido.");
        }

        $stmt = $conexao->prepare("
            UPDATE saidas
            SET data_saida = ?, prazo_limite_inscricao = ?, status = ?
            WHERE id = ?
        ");
        $stmt->bind_param("sssi", $data_saida, $prazo_formatado, $status, $id);

        if ($stmt->execute()) {
            echo "<script>
                    alert('Saída atualizada com sucesso!');
                    window.parent.fecharModalAtualizar();
                </script>";
            exit();
        }

        responderErro("Erro ao atualizar saída: " . $conexao->error, null, 500);
    }

    $saida_atual = buscarSaidaPorId($conexao, $id);

    if (!$saida_atual) {
        responderErro("Saída não encontrada.", "../../../frontend/pages/semanas.php", 404);
    }
?>
    <!DOCTYPE html>
    <html lang="pt-br">
    <head>
    <meta charset="UTF-8">
    <title>Atualizar Saída</title>
    <link rel="stylesheet" href="../../../frontend/css/style.css">
    <style>
        /* Pequeno ajuste para o formulário se adaptar bem dentro do iframe */
        body { background: white; padding: 15px; }
        .container-iframe { max-width: 100%; margin: 0; padding: 0; box-shadow: none; }
    </style>
    </head>
    <body>

    <div class="container-iframe">
        <h2>Atualizar Dados da Saída</h2>
        
        <form method="post" action="atualizarSaidas.php?id=<?php echo $id; ?>">
        
        <div class="modal-grupo">
            <label>Data da saída</label>
            <input type="date" name="data_saida" value="<?php echo escaparHtml($saida_atual['data_saida']); ?>" required>
        </div>
        
        <div class="modal-grupo">
            <label>Prazo limite de inscrição</label>
            <input type="datetime-local" name="prazo_inscricao" value="<?php echo escaparHtml(date('Y-m-d\TH:i', strtotime($saida_atual['prazo_limite_inscricao']))); ?>" required>
        </div>
        
        <div class="modal-grupo">
            <label>Status</label>
            <select name="status" id="status" required>
                <option value="aberto" <?php echo $saida_atual['status'] == 'aberto' ? 'selected' : ''; ?>>Aberto</option>
                <option value="encerrado" <?php echo $saida_atual['status'] == 'encerrado' ? 'selected' : ''; ?>>Encerrado</option>
            </select>
        </div>
        
        <button type="submit" class="btn-texto verde" style="margin-top: 15px; width: 100%;">Salvar Alterações</button>
        </form>
    </div>

    </body>
    </html>
