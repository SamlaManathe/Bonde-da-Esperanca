<?php
session_start();

if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header("Location: login.php");
    exit();
}

ob_start();
include "../../backend/core/voluntarios/listarVoluntarios.php";
ob_end_clean();

include "../../config/db.php";

$sql_saidas = "SELECT * FROM saidas ORDER BY data_saida ASC";
$resultado_saidas = $conexao->query($sql_saidas);
$saidas = [];
if ($resultado_saidas) {
    $saidas = $resultado_saidas->fetch_all(MYSQLI_ASSOC);
}

$buscaValor = htmlspecialchars($_GET['busca'] ?? '', ENT_QUOTES, 'UTF-8');
$statusValor = htmlspecialchars($_GET['status'] ?? '', ENT_QUOTES, 'UTF-8');
$saidaSelecionada = htmlspecialchars($_GET['saida_id'] ?? '', ENT_QUOTES, 'UTF-8');
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>

    <meta charset="UTF-8">
    <title>Voluntários</title>

    <link rel="stylesheet" href="../css/style.css">

</head>

<body>

    <header class="topo" style="display:flex !important; justify-content:space-between !important; align-items:center !important; padding: 0 40px 0 260px !important; height: 60px !important; background: #3B5D11 !important; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">

        <div style="font-size:20px; font-weight:bold; color:white;">
            Bonde da Esperança
        </div>

        <nav style="display:flex; gap:15px;">
            <a href="../../index.php"
            style="color:white; text-decoration:none; background:rgba(255,255,255,0.15); padding:8px 16px; border-radius:6px;">
            Início
            </a>

            <a href="logout.php"
            style="color:white; text-decoration:none; background:#d9534f; padding:8px 16px; border-radius:6px; font-weight:bold;">
            Sair
            </a>
        </nav>

    </header>

    <aside class="menu">

        <h3>Painel de Controle</h3>

        <a href="dashboard.php">Dashboard</a>
        <a href="semanas.php">Saídas</a>
        <a href="voluntarios.php">Voluntários</a>

    </aside>

    <main class="conteudo"
    style="
    margin-left:220px;
    padding:40px;
    min-height:calc(100vh - 60px);
    background:#f4f6f3;">

        <h2 style="
        color:#3B5D11;
        font-size:26px;
        font-weight:bold;
        margin-bottom:8px;">
        Voluntários
        </h2>

        <p style="
        color:#666;
        margin-bottom:30px;">
        Gerencie os voluntários do projeto
        </p>

        <div style="
        background:white;
        padding:25px;
        border-radius:12px;
        box-shadow:0 4px 12px rgba(0,0,0,0.05);
        margin-bottom:25px;">

            <div style="
            display:flex;
            justify-content:space-between;
            align-items:center;
            gap:15px;
            flex-wrap:wrap;">

                <form method="get"
                    style="display:flex; gap:10px; flex-wrap:wrap; align-items:center;">

                <input
                    type="text"
                    name="busca"
                    placeholder="Buscar voluntário ou telefone"
                    value="<?php echo $buscaValor; ?>">

                <select name="status">
                    <option value="">Todos os status</option>
                    <option value="aberto" <?php echo $statusValor === 'aberto' ? 'selected' : ''; ?>>
                        Aberto
                    </option>
                    <option value="encerrado" <?php echo $statusValor === 'encerrado' ? 'selected' : ''; ?>>
                        Encerrado
                    </option>
                </select>

                <select name="saida_id">
                    <option value="">Todas as saídas</option>

                    <?php
                    foreach ($saidas as $saida) {
                        $selecionado = $saidaSelecionada == $saida['id'] ? 'selected' : '';

                        echo "
                        <option value='{$saida['id']}' {$selecionado}>
                        " . date('d/m/Y', strtotime($saida['data_saida'])) . "
                        </option>";
                    }
                    ?>

                </select>

                <button type="submit" class="btn-texto verde">
                    Filtrar
                </button>

                </form>

                <button
                    type="button"
                    class="btn-texto verde"
                    onclick="abrirModalCriar()">
                    Criar voluntário
                </button>

            </div>

        </div>

        <table>

            <tr>
                <th>Voluntário</th>
                <th>Telefone</th>
                <th>Saída</th>
                <th>Inscrito em</th>
                <th>Ações</th>
            </tr>

            <?php

                while ($voluntario = $resultado_busca_voluntarios->fetch_assoc()) {

                    echo "<tr>";

                    echo "<td>" . $voluntario['nome'] . "</td>";

                    echo "<td>" . $voluntario['telefone'] . "</td>";

                    echo "<td>" .
                        date('d/m/Y', strtotime($voluntario['data_saida']))
                    . "</td>";

                    echo "<td>" .
                        date('d/m/Y H:i', strtotime($voluntario['inscrito_em']))
                    . "</td>";

                    echo "<td class='acoes'>";

                    echo "
                        <button
                            type='button'
                            class='btn modificar btn-centralizado'
                            onclick='abrirModalAtualizarVoluntario(" . $voluntario['id'] . ")'>✎</button>
                    ";

                    echo "
                        <button
                            type='button'
                            class='btn apagar btn-centralizado'
                            onclick='abrirModalDeletar(" . $voluntario['id'] . ")'>🗑</button>
                    ";

                    echo "</td>";

                    echo "</tr>";
                }

            ?>

        </table>

    </main>

    <!-- MODAL CRIAR -->
    <div id="ModalCriar" class="modal-overlay">

        <div class="modal-container">

            <span onclick="fecharModalCriar()" class="modal-fechar">
                &times;
            </span>

            <h2>Cadastrar Voluntário</h2>

            <form
                method="post"
                action="../../backend/core/voluntarios/criarVoluntarios.php">

                <div class="modal-grupo">

                    <label>Saída</label>

                    <select name="saida_id" required>

                        <?php

                            foreach ($saidas as $saida) {

                                echo "
                                    <option value='" . $saida['id'] . "'>
                                        " . date('d/m/Y', strtotime($saida['data_saida'])) . "
                                    </option>
                                ";
                            }

                        ?>

                    </select>

                </div>

                <div class="modal-grupo">

                    <label>Nome</label>

                    <input
                        type="text"
                        name="nome"
                        required>

                </div>

                <div class="modal-grupo">

                    <label>Telefone</label>

                    <input
                        type="text"
                        name="telefone"
                        required>

                </div>

                <button
                    type="submit"
                    class="btn-texto verde"
                    style="margin-top: 10px; width: 100%;">

                    Salvar

                </button>

            </form>

        </div>

    </div>

    <!-- MODAL ATUALIZAR -->
    <div id="modalAtualizar" class="modal-overlay">

        <div class="modal-container modal-iframe">

            <span onclick="fecharModalAtualizar()" class="modal-fechar">
                &times;
            </span>

            <iframe
                id="iframeAtualizar"
                src=""
                class="modal-iframe-conteudo">
            </iframe>

        </div>

    </div>

    <!-- MODAL DELETAR -->
    <div id="modalDeletar" class="modal-overlay">

        <div class="modal-container modal-aviso">

            <span onclick="fecharModalDeletar()" class="modal-fechar">
                &times;
            </span>

            <h2 style="color: #d9534f; margin-bottom: 10px;">
                Excluir Registro?
            </h2>

            <p>
                Tem certeza que deseja apagar este voluntário?
            </p>

            <form
                method="post"
                action="../../backend/core/voluntarios/deletarVoluntarios.php">

                <input
                    type="hidden"
                    name="id"
                    id="delete_id">

                <div class="modal-botoes">

                    <button
                        type="button"
                        onclick="fecharModalDeletar()"
                        class="btn-cancelar">

                        Cancelar

                    </button>

                    <button
                        type="submit"
                        class="btn-texto vermelho">

                        Sim, Excluir

                    </button>

                </div>

            </form>

        </div>

    </div>

    <footer>
        Todos os direitos reservados
    </footer>

    <script src="../js/scripts.js"></script>

</body>
</html>