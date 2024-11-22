<?php

require_once __DIR__ . '/ActiveDirectoryService.php';
require_once __DIR__ . '/vendor/autoload.php';

session_start();

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

switch ($uri) {
  case '/':
    include_once __DIR__ . '/index.html';
    break;
  case '/reset':
    if (isset($_POST['admin']) && isset($_POST['admin-password']) && isset($_POST['user']) && isset($_POST['user-password'])) {
      $adService = new ActiveDirectoryService();
      if ($adService->resetPassword($_POST['admin'], $_POST['admin-password'], $_POST['user'], $_POST['user-password'])) {
        $_SESSION['message'] = "Senha alterada com sucesso!";
      } else {
        $_SESSION['message'] = "Erro ao alterar senha!";
      }
      header("Location: /");
      exit;
    } else {
      header("Location: /");
      echo "ERRO";
      exit;
    }
}


?>

<!doctype html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Login</title>
  <link href="../public/css/login.css" rel="stylesheet" />
  <script src="https://kit.fontawesome.com/fb32a93e05.js" crossorigin="anonymous"></script>
  <script src="../public/js/login.js" defer></script>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link
    href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap"
    rel="stylesheet" />
</head>

<body>
  <div class="container">
    <div class="container--form">
      <form action="/reset" method="POST" autocomplete="off">
        <div class="input-div">
          <input type="text" class="pass-form-input" name="admin"
            required /><span class="floating-label">Admin</span>
        </div>
        <div class="input-div">
          <input type="password" class="pass-form-input" name="admin-password"
            required /><span class="floating-label">Admin Pass</span>
          <div class="input-eye-div">
            <i class="fa-solid fa-eye input-open-eye input-eye" id="inputOpenEye1"></i>
            <i class="fa-solid fa-eye-slash input-closed-eye input-eye" id="inputClosedEye1"></i>
          </div>
        </div>
        <div class="input-div">
          <input type="text" class="pass-form-input" name="user"
            required /><span class="floating-label">User</span>
        </div>
        <div class="input-div">
          <input type="password" class="pass-form-input" name="user-password"
            required /><span class="floating-label">User Pass</span>
          <div class="input-eye-div">
            <i class="fa-solid fa-eye input-open-eye input-eye" id="inputOpenEye2"></i>
            <i class="fa-solid fa-eye-slash input-closed-eye input-eye" id="inputClosedEye2"></i>
          </div>
        </div>


        <input type="submit" class="form-button" value="Change Password" name="submit"></input>
      </form>
    </div>

  </div>
  <?php
  if (@$_SESSION['message']) {
    $message = $_SESSION['message'];
    $success = '';
    if (str_contains($message, 'sucesso')) {
      echo "<div class='message-div success-msg'><i class='fa-solid fa-check-double message-icon'></i><span>$message</span><i class='fa-solid fa-x message-x' id='deleteMessageButton'></i></div>";
    } else {

      echo "<div class='message-div'><i class='fa-solid fa-triangle-exclamation message-icon'></i><span>$message</span><i class='fa-solid fa-x message-x' id='deleteMessageButton'></i></div>";
    }
    unset($_SESSION['message']);
  }

  ?>
</body>

</html>
