<?php
    include __DIR__ . "/../../../config/db.php";

    $id = isset($_GET['id']) ? $_GET['id'] : null;

    if (!$id) {
        echo "<p style='color:red; text-align:center; padding:20px;'>ID não informado.</p>";
        exit();
    }

    // Se o usuário clicou em salvar dentro do iframe (Envio do formulário)
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $data_saida = $_POST['data_saida'];
        $prazo_inscricao = $_POST['prazo_inscricao'];
        $status = $_POST['status'];

        $sql = "UPDATE saidas SET 
                data_saida='$data_saida', 
                prazo_limite_inscricao='$prazo_inscricao', 
                status='$status' 
                WHERE id='$id'";

        if ($conexao->query($sql)) {
            // Script simples para avisar o semanas.php (pai do iframe) para fechar o modal e atualizar a página
            echo "<script>
                    alert('Saída atualizada com sucesso!');
                    window.parent.fecharModalAtualizar();
                </script>";
            exit;
        } else {
            echo "<p style='color:red;'>Erro ao atualizar saída: " . $conexao->error . "</p>";
        }
    }

    // Busca os dados atuais para preencher o formulário
    $sql = "SELECT * FROM saidas WHERE id = $id";
    $res = $conexao->query($sql);

    if (!$res || $res->num_rows !== 1) {
        echo "<p style='color:red; text-align:center; padding:20px;'>Saída não encontrada.</p>";
        exit;
    }

    $saida_atual = $res->fetch_assoc();
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
            <input type="date" name="data_saida" value="<?php echo $saida_atual['data_saida']; ?>" required>
        </div>
        
        <div class="modal-grupo">
            <label>Prazo limite de inscrição</label>
            <input type="datetime-local" name="prazo_inscricao" value="<?php echo date('Y-m-d\TH:i', strtotime($saida_atual['prazo_limite_inscricao'])); ?>" required>
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