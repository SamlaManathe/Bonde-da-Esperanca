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
$statusAtual = $_GET['status'] ?? 'aberto';
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Semanas</title>
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
    Saídas
    </h2>

    <p style="
    color:#666;
    margin-bottom:30px;">
    Gerencie as saídas do projeto
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
              style="display:flex; gap:10px; align-items:center; flex-wrap:wrap;">

          <input type="text"
                name="busca"
                placeholder="Pesquisar saídas..."
                value="<?php echo $buscaValor; ?>">

          <button type="submit" class="btn-texto verde">
            Filtrar
          </button>

          <a href="?status=aberto<?php echo $buscaParam; ?>"
            style="
            text-decoration:none;
            padding:10px 18px;
            border-radius:8px;
            font-weight:bold;
            border:1px solid #dcdcdc;
            <?php echo $statusAtual == 'aberto'
              ? 'background:#3B5D11;color:white;border-color:#3B5D11;'
              : 'background:#f1f1f1;color:#555;'; ?>">
            Ativas
          </a>

          <a href="?status=encerrado<?php echo $buscaParam; ?>"
            style="
            text-decoration:none;
            padding:10px 18px;
            border-radius:8px;
            font-weight:bold;
            border:1px solid #dcdcdc;
            <?php echo $statusAtual == 'encerrado'
              ? 'background:#3B5D11;color:white;border-color:#3B5D11;'
              : 'background:#f1f1f1;color:#555;'; ?>">
            Arquivadas
          </a>

        </form>

        <button type="button"
                class="btn-texto verde"
                onclick="abrirModalCriar()">
          Criar saída
        </button>

      </div>

    </div>
    
    <div style="
    background:white;
    border-radius:12px;
    overflow:hidden;
    box-shadow:0 4px 12px rgba(0,0,0,0.05);">
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
            echo "    <button type='button' class='btn modificar btn-centralizado' onclick='abrirModalAtualizarSaida(" . $id_da_saida . ")'>✎</button>";
            echo "    <button type='button' class='btn apagar btn-centralizado' onclick='abrirModalDeletar(" . $id_da_saida . ")'>🗑</button>";
            echo "  </td>";
            echo "</tr>";
        }
      ?>
    </table>
  </div>
  </main>

  <div id="ModalCriar" class="modal-overlay">
    <div class="modal-container"
    style="
    border-radius:12px;
    box-shadow:0 10px 30px rgba(0,0,0,0.15);">
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
    <div class="modal-container"
    style="
    border-radius:12px;
    box-shadow:0 10px 30px rgba(0,0,0,0.15);">
      <span onclick="fecharModalVoluntarios()" class="modal-fechar">&times;</span>
      <h2>Voluntários da saída</h2>
      <div id="voluntariosLista"></div>
    </div>
  </div>

  <footer style="
  position:fixed;
  bottom:0;
  left:0;
  background:#3B5D11;
  color:rgba(255,255,255,0.7);
  height:40px;
  width:100%;
  display:flex;
  align-items:center;
  justify-content:center;
  font-size:13px;">
  Todos os direitos reservados &copy; Bonde da Esperança
  </footer>

  <script src="../js/scripts.js"></script>

</body>
</html>