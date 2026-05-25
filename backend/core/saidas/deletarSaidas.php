<?php
    include "../../../config/db.php";

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        // Recebe o ID de forma oculta vindo do formulário do modal
        $id = $_POST['id'];

        if ($id) {
            $sql = "DELETE FROM saidas WHERE id = '$id'";

            if ($conexao->query($sql)) {
                // Deletado com sucesso, retorna para a listagem
                header("Location: ../../../frontend/pages/semanas.php");
                exit();
            } else {
                echo "<p>Erro ao deletar saída: " . $conexao->error . "</p>";
                echo "<p><a href='../../../frontend/pages/semanas.php'>Voltar</a></p>";
            }
        }
    } else {
        // Proteção: se tentarem acessar a página direto, joga de volta
        header("Location: ../../../frontend/pages/semanas.php");
        exit();
    }
?>