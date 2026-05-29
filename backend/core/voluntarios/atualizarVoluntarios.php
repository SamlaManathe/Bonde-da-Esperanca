<?php
    include __DIR__ . "/../../../config/db.php";

    $id = isset($_GET['id']) ? $_GET['id'] : null;

    if (!$id) {

        echo "<p style='color:red; text-align:center; padding:20px;'>ID não informado.</p>";
        exit();

    }

    if ($_SERVER["REQUEST_METHOD"] === "POST") {

        $saida_id = $_POST['saida_id'];
        $nome = $_POST['nome'];
        $telefone = $_POST['telefone'];

        $sql = "
            UPDATE voluntarios SET
            saida_id='$saida_id',
            nome='$nome',
            telefone='$telefone'
            WHERE id='$id'
        ";

        if ($conexao->query($sql)) {

            echo "
                <script>
                    alert('Voluntário atualizado com sucesso!');
                    window.parent.fecharModalAtualizar();
                </script>
            ";

            exit();

        } else {

            echo "<p style='color:red;'>Erro ao atualizar voluntário: " . $conexao->error . "</p>";

        }
    }

    $sql = "SELECT * FROM voluntarios WHERE id = $id";
    $res = $conexao->query($sql);

    if (!$res || $res->num_rows !== 1) {

        echo "<p style='color:red; text-align:center; padding:20px;'>Voluntário não encontrado.</p>";
        exit();

    }

    $voluntario_atual = $res->fetch_assoc();

    $sql_saidas = "SELECT * FROM saidas ORDER BY data_saida ASC";
    $resultado_saidas = $conexao->query($sql_saidas);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Atualizar Voluntário</title>

<link rel="stylesheet" href="../../../frontend/css/style.css">

<style>

    body {
        background: white;
        padding: 15px;
    }

    .container-iframe {
        max-width: 100%;
        margin: 0;
        padding: 0;
        box-shadow: none;
    }

</style>

</head>

<body>

<div class="container-iframe">

    <h2>Atualizar Voluntário</h2>

    <form method="post" action="atualizarVoluntarios.php?id=<?php echo $id; ?>">

        <div class="modal-grupo">

            <label>Saída</label>

            <select name="saida_id" required>

                <?php
                    while($saida = $resultado_saidas->fetch_assoc()) {

                        $selected = $saida['id'] == $voluntario_atual['saida_id'] ? 'selected' : '';

                        echo "
                            <option value='{$saida['id']}' $selected>
                                " . date('d/m/Y', strtotime($saida['data_saida'])) . "
                            </option>
                        ";
                    }
                ?>

            </select>

        </div>

        <div class="modal-grupo">

            <label>Nome</label>
            <input type="text" name="nome"
            value="<?php echo $voluntario_atual['nome']; ?>" required>

        </div>

        <div class="modal-grupo">

            <label>Telefone</label>
            <input type="text" name="telefone"
            value="<?php echo $voluntario_atual['telefone']; ?>" required>

        </div>

        <button type="submit" class="btn-texto verde"
        style="margin-top: 15px; width: 100%;">

            Salvar Alterações

        </button>

    </form>

</div>

</body>
</html>