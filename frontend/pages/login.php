<?php
session_start();

if (isset($_SESSION['admin']) && $_SESSION['admin'] === true) {
    header("Location: dashboard.php");
    exit();
}

$erroLogin = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $admin = $_POST['login'] ?? '';
    $senha = $_POST['senha'] ?? '';

    if ($admin === 'professor' && $senha === 'senha') {
        $_SESSION['admin'] = true;
        header("Location: dashboard.php");
        exit();
    }

    $erroLogin = "Usuário ou senha inválidos.";
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Login ADM</title>
  <link rel="stylesheet" href="../css/style.css">
</head>

<body class="login-page">

  <!-- HEADER COM MENU -->
  <header class="topo" style="display:flex; justify-content:space-between; align-items:center; padding-right:30px;">

    <div class="logo">Bonde da Esperança</div>

    <nav>
      <a href="../../index.php">Início</a>
      <a href="sobre.php">Sobre</a>
      <a href="login.php">Admin</a>
    </nav>

  </header>

  <form method="post">
    <main class="login-container">
      <div class="card-login">
        <h2>Login de ADM</h2>
        
        <label>Login</label>
        <input name="login" type="text" required>

        <label>Senha</label>
        <input name="senha" type="password" required>

        <button type="submit">Entrar</button>

        <?php if ($erroLogin): ?>
          <p class="erro-login" style="color:#d9534f; margin-top:15px;"><?php echo htmlspecialchars($erroLogin, ENT_QUOTES, 'UTF-8'); ?></p>
        <?php endif; ?>
      </div>
    </main>
  </form>

  <footer>Todos os direitos reservados</footer>

</body>
</html>
