<?php
include "config/db.php";

$sql = "
    SELECT *
    FROM saidas
    WHERE data_saida >= CURDATE()
    ORDER BY data_saida ASC
    LIMIT 1
";

$result = $conexao->query($sql);
$saida = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Bonde da Esperança</title>
  <link rel="stylesheet" href="frontend/css/style.css">
</head>

<body class="home-page">

  <!-- HEADER AJUSTADO -->
  <header class="topo" style="display:flex !important; justify-content:space-between !important; align-items:center !important; padding: 0 40px !important; height: 60px !important; background: #3B5D11 !important; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">

    <div style="display:flex; align-items:center; gap:12px;">
      <img src="frontend/images/LogoBondeDaEsperanca.png" alt="Logo do Site" width="42" height="42">

      <div style="font-size:20px; font-weight:bold; color:white;">
        Bonde da Esperança
      </div>
    </div>

    <nav style="display:flex; gap:15px;">
      <a href="index.php"
        style="color:white; text-decoration:none; background:rgba(255,255,255,0.15); padding:8px 16px; border-radius:6px; font-weight:500;">
        Início
      </a>

      <a href="frontend/pages/sobre.php"
        style="color:white; text-decoration:none; background:rgba(255,255,255,0.15); padding:8px 16px; border-radius:6px; font-weight:500;">
        Sobre
      </a>

      <a href="frontend/pages/login.php"
        style="color:white; text-decoration:none; background:#4d7917; padding:8px 16px; border-radius:6px; font-weight:bold;">
        Admin
      </a>
    </nav>

  </header>

  <main class="home-container"
      style="
      min-height: calc(100vh - 100px);
      background:#f4f6f3;
      padding:50px;
      gap:60px;
      align-items:center;">

    <!-- LADO ESQUERDO -->
    <section class="lado-esquerdo">

      <img src="frontend/images/LogoBondeDaEsperanca.png"
          alt="Logo do Site"
          width="300">

      <h1 style="
          color:#3B5D11;
          font-size:36px;
          margin-top:30px;
          margin-bottom:20px;">
          Participe dessa iniciativa
      </h1>

      <p style="
          max-width:650px;
          margin:auto;
          color:#666;
          font-size:18px;
          line-height:1.8;">
          O Bonde da Esperança é uma iniciativa social liderada por um professor de jiu-jitsu que, junto com voluntários, realiza a distribuição de refeições para moradores de rua sempre que possível.
      </p>

    </section>

    <!-- LADO DIREITO -->
    <section class="lado-direito">

      <!-- CARD DA SAÍDA (AGORA IGUAL AO PIX) -->
      <?php if ($saida): ?>

        <div class="card-apoio"
            style="
            border:none;
            border-radius:12px;
            box-shadow:0 4px 12px rgba(0,0,0,0.05);
            padding:30px;">

          <h2 style="color: #3B5D11">Próxima saída</h2>

          <p>
            <strong>Data da saída:</strong><br>
            <?php echo date('d/m/Y', strtotime($saida['data_saida'])); ?>
          </p>

          <p>
            <strong>Prazo de inscrição:</strong><br>
            <?php echo date('d/m/Y H:i', strtotime($saida['prazo_limite_inscricao'])); ?>
          </p>

          <p>
            <strong>Status:</strong><br>
            <?php echo ucfirst($saida['status']); ?>
          </p>

          <button class="btn-texto verde" onclick="abrirModal(<?php echo $saida['id']; ?>)">
            Quero participar
          </button>

        </div>

      <?php else: ?>

        <div class="card-apoio"
            style="
            border:none;
            border-radius:12px;
            box-shadow:0 4px 12px rgba(0,0,0,0.05);
            padding:30px;">
          <h2>Nenhuma saída disponível</h2>
          <p>Em breve novas ações serão cadastradas.</p>
        </div>

      <?php endif; ?>

      <!-- PIX -->
      <div class="card-apoio"
          style="
          border:none;
          border-radius:12px;
          box-shadow:0 4px 12px rgba(0,0,0,0.05);
          padding:30px;">

        <h2>Apoie o projeto</h2>

        <p>
          Você pode ajudar com qualquer valor através da chave PIX abaixo:
        </p>

        <button class="btn-texto verde">
          chavepix@projetovoluntarios.com.br
        </button>

      </div>

    </section>

  </main>

  <!-- MODAL -->
  <div id="modalInscricao" class="modal-overlay">

    <div class="modal-container"
        style="
        border-radius:12px;
        padding:30px;
        box-shadow:0 10px 30px rgba(0,0,0,0.15);">

      <span onclick="fecharModal()" class="modal-fechar">&times;</span>

      <h2>Inscrição de Voluntário</h2>

      <form method="post" action="backend/core/voluntarios/criarVoluntarios.php">

        <input type="hidden" name="saida_id" id="saida_id">
        <input type="hidden" name="origem" value="home">

        <div class="modal-grupo">
          <label>Nome completo</label>
          <input type="text" name="nome" required>
        </div>

        <div class="modal-grupo">
          <label>Telefone</label>
          <input type="text" name="telefone" required>
        </div>

        <button type="submit" class="btn-texto verde" style="width:100%;">
          Confirmar inscrição
        </button>

      </form>

    </div>

  </div>

  <!-- FOOTER -->
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

  <!-- JS -->
  <script>
    function abrirModal(id) {
        document.getElementById("saida_id").value = id;
        document.getElementById("modalInscricao").style.display = "block";
    }

    function fecharModal() {
        document.getElementById("modalInscricao").style.display = "none";
    }

    window.onclick = function(event) {
        let modal = document.getElementById("modalInscricao");
        if (event.target == modal) {
            fecharModal();
        }
    }
  </script>

</body>
</html>