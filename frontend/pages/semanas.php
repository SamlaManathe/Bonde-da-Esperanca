<?php
session_start();

if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header("Location: login.php");
    exit();
}

ob_start();
include "../../backend/core/saidas/listarSaidas.php";
ob_end_clean();

$buscaValor = htmlspecialchars($busca ?? '', ENT_QUOTES, 'UTF-8');
$buscaParam = $buscaValor !== '' ? '&busca=' . urlencode($buscaValor) : '';
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Semanas</title>
  <link rel="stylesheet" href="../css/style.css">
</head>
<body>

  <header class="topo" style="display:flex; justify-content:space-between; align-items:center; padding-right:30px;">
    <div class="logo">Bonde da Esperança</div>
    <nav>
      <a href="../../index.php">Início</a>
      <a href="logout.php">Sair</a>
    </nav>
  </header>

  <aside class="menu">
    <h3>Bonde da Esperança</h3>
    <a href="dashboard.php">Dashboard</a>
    <a href="semanas.php">Saídas</a>
    <a href="voluntarios.php">Voluntários</a>
  </aside>

  <main class="conteudo">
    <h1>Saídas</h1>
    <p>Gerencie as saídas do projeto</p>

    <div style="margin-bottom: 20px; display: flex; flex-direction: column; gap: 15px;">
      <form method="get" style="display:flex; gap:10px; flex-wrap:wrap; align-items:center;">
        <input type="text" name="busca" placeholder="Pesquisar saídas..." value="<?php echo $buscaValor; ?>">
        <button type="submit" class="btn-texto verde">Filtrar</button>
      </form>

      <div style="display:flex; justify-content: space-between; align-items:center; gap: 15px; flex-wrap:wrap;">
        <div class="abas">
          <a href="?status=aberto<?php echo $buscaParam; ?>"><button type="button">Ativas</button></a>
          <a href="?status=encerrado<?php echo $buscaParam; ?>"><button type="button">Arquivadas</button></a>
        </div>

        <button type="button" class="btn-texto verde" onclick="abrirModalCriar()" title="Cadastrar Nova Semana">Criar saída</button>
      </div>
    </div>
    
    <table>
      <tr>
        <th>Período</th>
        <th>Voluntários</th>
        <th>Status</th>
        <th>Ações</th>
      </tr>
      <?php
        // Usa a variável corrigida para evitar conflito com o banco de dados
        while ($saida = $resultado_busca->fetch_assoc()) {
            $id_da_saida = $saida['id'];

            // Faz a contagem de voluntários para esta saída específica
            $sql_contagem = "SELECT COUNT(id) AS total FROM voluntarios WHERE saida_id = $id_da_saida";
            $resultado_contagem = $conexao->query($sql_contagem);
            $dados_contagem = $resultado_contagem->fetch_assoc();
            $total_inscritos = $dados_contagem['total'];
            
            echo "<tr>";
            echo "  <td>" . date('d/m/Y', strtotime($saida['data_saida'])) . "</td>";
            echo "  <td>" . $total_inscritos . "</td>";
            echo "  <td>" . ucfirst($saida['status']) . "</td>";
            echo "  <td class='acoes'>";
            echo "    <button type='button' class='btn info btn-centralizado' onclick='abrirModalVoluntarios(" . $id_da_saida . ")'>👥</button>";
            echo "    <button type='button' class='btn modificar btn-centralizado' onclick='abrirModalAtualizar(" . $id_da_saida . ")'>✎</button>";
            echo "    <button type='button' class='btn apagar btn-centralizado' onclick='abrirModalDeletar(" . $id_da_saida . ")'>🗑</button>";
            echo "  </td>";
            echo "</tr>";
        }
      ?>
    </table>
  </main>

  <div id="ModalCriar" class="modal-overlay">
    <div class="modal-container">
      <span onclick="fecharModalCriar()" class="modal-fechar">&times;</span>
      
      <h2>Cadastrar Saída</h2>
      
      <form method="post" action="../../backend/core/saidas/criarSaidas.php">
        <div class="modal-grupo">
          <label>Data da saída</label>
          <input type="date" name="data_saida">
        </div>
        
        <div class="modal-grupo">
          <label>Prazo limite de inscrição</label>
          <input type="datetime-local" name="prazo_inscricao" required>
        </div>
        
        <div class="modal-grupo">
          <label>Status</label>
          <select name="status" id="status" required>
              <option value="aberto">Aberto</option>
              <option value="encerrado">Encerrado</option>
          </select>
        </div>
        
        <button type="submit" class="btn-texto verde" style="margin-top: 10px; width: 100%;">Salvar</button>
      </form>
    </div>
  </div>

  <div id="modalAtualizar" class="modal-overlay">
    <div class="modal-container modal-iframe">
      <span onclick="fecharModalAtualizar()" class="modal-fechar">&times;</span>
      
      <iframe id="iframeAtualizar" src="" class="modal-iframe-conteudo"></iframe>
    </div>
  </div>

  <div id="modalDeletar" class="modal-overlay">
    <div class="modal-container modal-aviso">
      <span onclick="fecharModalDeletar()" class="modal-fechar">&times;</span>
      
      <h2 style="color: #d9534f; margin-bottom: 10px;">Excluir Registro?</h2>
      <p>Tem certeza que deseja apagar esta saída? Esta ação não pode ser desfeita.</p>
      
      <form method="post" action="../../backend/core/saidas/deletarSaidas.php">
        <input type="hidden" name="id" id="delete_id">
        
        <div class="modal-botoes">
          <button type="button" onclick="fecharModalDeletar()" class="btn-cancelar">Cancelar</button>
          <button type="submit" class="btn-texto vermelho">Sim, Excluir</button>
        </div>
      </form>
    </div>
  </div>

  <div id="modalVoluntarios" class="modal-overlay">
    <div class="modal-container">
      <span onclick="fecharModalVoluntarios()" class="modal-fechar">&times;</span>
      <h2>Voluntários da saída</h2>
      <div id="voluntariosLista"></div>
    </div>
  </div>

  <footer>Todos os direitos reservados</footer>

  <script src="../js/scripts.js"></script>

</body>
</html>