<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Курник</title>
  <link rel="stylesheet" href="main.css">
</head>
<body>

<?php
try {
      session_start();
      $host = 'MySQL-8.2';
      $user = 'root';
      $pass = '';
      $db = 'site';
      $conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
      $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  
      // Активация пользователя
      $stmt = $conn->prepare("UPDATE users SET activ_token = NULL, email = '".$_SESSION['email']."' WHERE id = :id");
      $stmt->bindParam(':id', $_SESSION['user']);
      $stmt->execute();
      header("Location: index.php");
  
  } catch(PDOException $e) {
      echo "Ошибка активации: " . $e->getMessage();
  }
  
  $conn = null;
?>
</body>
</html>