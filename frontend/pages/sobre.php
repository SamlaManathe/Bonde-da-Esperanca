<?php
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Sobre - Bonde da Esperança</title>
  <link rel="stylesheet" href="../css/style.css">
</head>
<body class="home-page">

  <header class="topo"
  style="display:flex !important; justify-content:space-between !important; align-items:center !important; padding: 0 40px !important; height:60px !important; background:#3B5D11 !important; box-shadow:0 2px 5px rgba(0,0,0,0.1);">

    <div style="display:flex; align-items:center; gap:12px;">

      <img
        src="../images/LogoBondeDaEsperanca.png"
        alt="Logo Bonde da Esperança"
        width="42"
        height="42">

      <div style="font-size:20px; font-weight:bold; color:white;">
        Bonde da Esperança
      </div>

    </div>

    <nav style="display:flex; gap:15px;">
      <a href="../../index.php"
      style="color:white; text-decoration:none; background:rgba(255,255,255,0.15); padding:8px 16px; border-radius:6px;">
      Início
      </a>

      <a href="sobre.php"
      style="color:white; text-decoration:none; background:rgba(255,255,255,0.15); padding:8px 16px; border-radius:6px;">
      Sobre
      </a>

      <a href="login.php"
      style="color:white; text-decoration:none; background:#4d7917; padding:8px 16px; border-radius:6px; font-weight:bold;">
      Admin
      </a>
    </nav>

  </header>

  <main style="
  background:#f4f6f3;
  min-height:calc(100vh - 100px);
  padding:50px;">

    <div style="
    max-width:1200px;
    margin:auto;
    display:grid;
    grid-template-columns:2fr 1fr;
    gap:30px;">

      <!-- CONTEÚDO -->
      <div style="
      background:white;
      padding:35px;
      border-radius:12px;
      box-shadow:0 4px 12px rgba(0,0,0,0.05);">

        <h1 style="
        color:#3B5D11;
        margin-bottom:25px;">
        Sobre o Bonde da Esperança
        </h1>

        <p style="color:#555; line-height:1.8; margin-bottom:20px;">
          O Bonde da Esperança é uma iniciativa social liderada por um professor de jiu-jitsu que, junto com voluntários, realiza a distribuição de refeições para moradores de rua sempre que possível.
        </p>

        <p style="color:#555; line-height:1.8; margin-bottom:20px;">
          Nosso objetivo é levar alimento, carinho e apoio às pessoas em situação de rua, conectando quem pode ajudar com quem mais precisa. Cada saída é planejada com cuidado, respeitando datas, voluntários e segurança.
        </p>

        <p style="color:#555; line-height:1.8;">
          Se você deseja contribuir, uma das melhores formas é se inscrever como voluntário ou apoiar o projeto com doações. Juntos podemos fortalecer a rede do bem.
        </p>

        <!-- ÁREA DAS IMAGENS -->
        <div style="margin-top:40px;">

          <h2 style="
          color:#3B5D11;
          margin-bottom:20px;">
          Fotos do Projeto
          </h2>

          <div style="
          display:grid;
          grid-template-columns:1fr 1fr;
          gap:20px;">

            <img src="../images/ImagemEquipe.jpeg"
                style="width:100%; height:260px; object-fit:cover; border-radius:12px;">

            <img src="../images/ImagemEntregaMarmitas.jpeg"
                style="width:100%; height:260px; object-fit:cover; border-radius:12px;">

          </div>

        </div>

      </div>

      <!-- LATERAL -->
      <div style="
      display:flex;
      flex-direction:column;
      gap:20px;">

        <div style="
        background:white;
        padding:25px;
        border-radius:12px;
        box-shadow:0 4px 12px rgba(0,0,0,0.05);">

          <h2 style="color:#3B5D11; margin-bottom:15px;">
            Como participar
          </h2>

          <p style="color:#555; line-height:1.7;">
            As saídas são divulgadas no site e a inscrição é realizada diretamente na página inicial. Fique atento às datas e prazos.
          </p>

        </div>

        <div style="
        background:white;
        padding:25px;
        border-radius:12px;
        box-shadow:0 4px 12px rgba(0,0,0,0.05);">

          <h2 style="color:#3B5D11; margin-bottom:15px;">
            Contato
          </h2>

          <p style="color:#555; margin-bottom:20px; line-height:1.7;">
            Para dúvidas ou apoio, envie uma mensagem pelo Instagram.
          </p>

          <a href="https://www.instagram.com/bondedaesperancaa/"
            target="_blank"
            style="text-decoration:none;">

            <button class="btn-texto verde" style="width:100%;">
              Acessar Instagram
            </button>

          </a>

        </div>

      </div>

    </div>

  </main>

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

</body>
</html>
