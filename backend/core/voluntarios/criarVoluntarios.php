<?php
include "../../../config/db.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $saida_id = $_POST['saida_id'] ?? null;
    $nome = $_POST['nome'] ?? null;
    $telefone = $_POST['telefone'] ?? null;

    if (!$saida_id || !$nome || !$telefone) {
        die("Dados inválidos.");
    }

    $sql = "
        INSERT INTO voluntarios 
        (saida_id, nome, telefone, inscrito_em)
        VALUES
        ('$saida_id', '$nome', '$telefone', NOW())
    ";

    if ($conexao->query($sql)) {

        $origem = $_POST['origem'] ?? 'admin';

        if ($origem === 'home') {
            header("Location: ../../../index.php?inscricao=ok");
        } else {
            header("Location: ../../../frontend/pages/voluntarios.php?success=1");
        }

        exit();

    } else {

        echo "Erro ao criar voluntário: " . $conexao->error;
    }
}
?>