<?php
    include "../../../config/db.php";

    if ($_SERVER["REQUEST_METHOD"] === "POST") {

        $id = $_POST['id'];

        if ($id) {

            $sql = "DELETE FROM voluntarios WHERE id = '$id'";

            if ($conexao->query($sql)) {

                header("Location: ../../../frontend/pages/voluntarios.php");
                exit();

            } else {

                echo "<p>Erro ao deletar voluntário: " . $conexao->error . "</p>";
                echo "<p><a href='../../../frontend/pages/voluntarios.php'>Voltar</a></p>";

            }
        }

    } else {

        header("Location: ../../../frontend/pages/voluntarios.php");
        exit();

    }
?>