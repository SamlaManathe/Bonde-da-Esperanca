<?php
session_start();

if (isset($_SESSION['admin']) && $_SESSION['admin'] === true) {
    header("Location: dashboard.php");
    exit();
}

$erroLogin = "";

include "../../config/db.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $admin = $_POST['login'] ?? '';
    $senha = $_POST['senha'] ?? '';

    $stmt = $conexao->prepare("
        SELECT *
        FROM administradores
        WHERE login = ? AND senha = ?
        LIMIT 1
    ");

    $stmt->bind_param("ss", $admin, $senha);
    $stmt->execute();

    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
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
  <header class="topo" style="display:flex !important; justify-content:space-between !important; align-items:center !important; padding: 0 40px !important; height: 60px !important; background: #3B5D11 !important; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">

    <div style="display:flex; align-items:center; gap:12px;">
      <img src="../images/LogoBondeDaEsperanca.png" alt="Logo" width="42" height="42">

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

  <form method="post">
    <main class="login-container"
          style="
          background:#f4f6f3;
          min-height:calc(100vh - 100px);">
      <div class="card-login"
          style="
          border:none;
          border-radius:12px;
          padding:35px;
          background:white;
          box-shadow:0 4px 12px rgba(0,0,0,0.05);
          width:420px;">
        <h2 style="
        color:#3B5D11;
        font-size:28px;
        margin-bottom:25px;
        text-align:center;">
        Login Administrativo
        </h2>
        
        <label>Login</label>
        <input name="login" type="text" required
              style="
              background:#f5f5f5;
              border:1px solid #ddd;
              padding:14px;
              border-radius:8px;">

        <label>Senha</label>
        <input name="senha" type="password" required
              style="
              background:#f5f5f5;
              border:1px solid #ddd;
              padding:14px;
              border-radius:8px;">

        <button type="submit"
                class="btn-texto verde"
                style="
                width:100%;
                height:48px;
                font-size:15px;">
            Entrar
        </button>

        <?php if ($erroLogin): ?>
          <p class="erro-login" style="color:#d9534f; margin-top:15px;"><?php echo htmlspecialchars($erroLogin, ENT_QUOTES, 'UTF-8'); ?></p>
        <?php endif; ?>
      </div>
    </main>
  </form>

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
