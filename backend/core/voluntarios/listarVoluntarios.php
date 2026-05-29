<?php
include __DIR__ . "/../../../config/db.php";

$sql_busca = "
    SELECT 
        voluntarios.id,
        voluntarios.nome,
        voluntarios.telefone,
        voluntarios.inscrito_em,
        saidas.data_saida

    FROM voluntarios

    INNER JOIN saidas
    ON voluntarios.saida_id = saidas.id

    ORDER BY voluntarios.inscrito_em DESC
";

$resultado_busca = $conexao->query($sql_busca);
?>