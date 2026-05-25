<?php
    include "../../../config/db.php";

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $data_saida = $_POST['data_saida'];
        $prazo_inscricao = $_POST['prazo_inscricao'];
        $status = $_POST['status'];

        $sql = "
            INSERT INTO saidas (data_saida, prazo_limite_inscricao, status, criado_em) VALUES 
            ('$data_saida', '$prazo_inscricao', '$status', NOW());
        ";

        if ($conexao->query($sql)) {
            echo "<p>Saída criada com sucesso.</p>";
            header("Location: ../../../frontend/pages/semanas.php");
            exit();
        } else {
            echo "<p>Erro ao criar saída: " . $conexao->error . "</p>";
            echo "<p><a href='../../../frontend/pages/semanas.php'>Voltar</a></p>";
        }
    }
?>