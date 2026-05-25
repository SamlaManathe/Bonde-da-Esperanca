<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Login ADM</title>
  <link rel="stylesheet" href="../css/style.css">
</head>
<body class="login-page">

  <header class="topo">
    <div class="logo">Bonde da Esperança</div>
  </header>

  <form method="post">
    <main class="login-container">
      <div class="card-login">
        <h2>Login de ADM</h2>
        
        <label>Login</label>
        <input name="login" type="text">

        <label>Senha</label>
        <input name="senha" type="password">

        <button type="submit">Entrar</button>
      </div>
    </main>
  </form>

  <footer>Todos os direitos reservados</footer>

</body>
</html>

<?php
  $admin = $_POST['login'];
  $senha = $_POST['senha'];

  if ($admin == 'professor' && $senha == 'senha') {
    header("Location: https://localhost/projetos/Bonde-da-Esperanca/frontend/pages/dashboard.php");
    
    exit();
  } else {
    echo "<p>Usuário ou senha inválidos.</p>";
  };

?>