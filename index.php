<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Bonde da Esperança</title>
  <link rel="stylesheet" href="frontend/css/style.css">
</head>
<body class="home-page">

  <header class="topo">
    <div class="logo">Bonde da Esperança</div>

    <nav>
      <a href="index.php">Início</a>
      <a href="#">Sobre o projeto</a>
      <a href="frontend/pages/login.php" class="adm">adm</a>
    </nav>
  </header>

  <main class="home-container">

    <section class="lado-esquerdo">
      <h1>Faça parte desse projeto!</h1>
      <p>
        Preencha seus dados para se voluntariar e fazer a diferença
      </p>

      <div class="icone-coracao">♡</div>
    </section>

    <section class="lado-direito">

      <div class="card-cadastro">
        <h2>Inscrição do Voluntário</h2>

        <label>Nome completo</label>
        <input type="text">

        <label>Telefone</label>
        <input type="text">

        <button>Quero me voluntariar!</button>
      </div>

      <div class="card-apoio">
        <h2>Apoie esse projeto</h2>
        <p>
          Você também pode contribuir com qualquer valor através da chave PIX abaixo:
        </p>

        <button>chavepix@projetovoluntarios.com.br</button>

        <p class="obs">
          Sua doação ajuda a manter esse projeto ativo!
        </p>
      </div>

    </section>

  </main>

  <footer>Todos os direitos reservados</footer>

</body>
</html>