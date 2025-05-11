<?php
  $host = 'MySQL-8.2';
  $user = 'root';
  $pass = '';
  $db = 'site';
  $connect = mysqli_connect($host, $user, $pass, $db);

  session_start();
  session_destroy();
  session_start();

  $loginError = "";

  if (isset($_POST['enter'])) {
      $log = $_POST['log'];
      $pas = $_POST['pas'];

      if (empty($log) || empty($pas)) {
          $loginError = "Пожалуйста, заполните все поля.";
      } else {
          if (!preg_match("/^[a-zA-Z0-9]{3,}$/", $log)) {
              $loginError = "Некорректный логин. Допускаются только буквы и цифры (минимум 3 символа).";
          } else {
              $hash = md5($pas);
              $user_query = mysqli_query($connect, "SELECT * FROM users WHERE login='$log' AND password='$hash'");
              $user = mysqli_fetch_assoc($user_query);

              if ($user) {
                  $_SESSION['user'] = $user['id'];
                  $_SESSION['access'] = $user['access'];
                  $_SESSION['active'] = $user['active'];
                  $_SESSION['user_email'] = $user['email'];
                  header("Location: index.php");
                  exit();
              } else {
                  $loginError = "Неверный логин или пароль.";
              }
              mysqli_free_result($user_query);
          }
      }
  };
?>

<!DOCTYPE html>
<html>
<head>
    <title>Авторизация</title>
    <link rel="stylesheet" type="text/css" href="styles/avtoriz.css">
</head>
<body>

<div class="container">
    <h1>Авторизация</h1>

    <form method="POST" action="" class="registration-form">
        <div id="loginErrorDialog" class="error-dialog <?php if (!empty($loginError)) { echo 'show'; } ?>">
            <?php echo htmlspecialchars($loginError); ?>
        </div>

        <div class="form-group">
            <label for="log">Логин:</label>
            <input type="text" id="log" name="log" value="<?php echo isset($_POST['log']) ? htmlspecialchars($_POST['log']) : ''; ?>">
        </div>

        <div class="form-group">
            <label for="pas">Пароль:</label>
            <input type="password" id="pas" name="pas">
        </div>

        <button type="submit" name="enter">Войти</button>
    </form>

    <form action="registr.php" class="reg-form">
        <button type="submit">Регистрация</button>
    </form>
</div>

<script src="js/avtoriz.js"></script>

</body>
</html>