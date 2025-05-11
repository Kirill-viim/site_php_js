<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Инициализация переменных для значений полей
$surname = '';
$name = '';
$log = '';
$email = '';

// Массив ошибок
$errors = array(
    'log' => '',
    'email' => '',
    'pas' => ''
);
$general_error = '';
$activation_sent = false; // Флаг для отображения сообщения об отправке

if (isset($_POST['reg'])):
    $surname = $_POST['surname'];
    $name = $_POST['name'];
    $log = $_POST['log'];
    $pas = $_POST['pas'];
    $hash = md5($pas);
    $email = $_POST['email'];

    // Проверка на пустоту пароля
    if (empty($pas)) {
        $errors['pas'] = "Пароль не может быть пустым.";
    }

    // Подключение к базе данных и остальные операции только если пароль не пустой
    if (empty($errors['pas'])):
        // Подключение к базе данных
        $host = 'MySQL-8.2';
        $user = 'root';
        $pass = '';
        $db = 'site';

        try {
            $connect = new mysqli($host, $user, $pass, $db);
            if ($connect->connect_error):
                die("Ошибка подключения к базе данных: " . $connect->connect_error);
            endif;

            //Проверка на дубликаты
            $result = mysqli_query($connect, "SELECT * FROM `users` WHERE `login` = '$log' OR `email` = '$email'");
            if (mysqli_num_rows($result) > 0):
                while ($row = mysqli_fetch_assoc($result)):
                    if ($row['login'] == $log):
                        $errors['log'] = "Логин уже существует.";
                    endif;
                    if ($row['email'] == $email):
                        $errors['email'] = "Email уже существует.";
                    endif;
                endwhile;
            endif;

            if (empty($errors['log']) && empty($errors['email'])):

                mysqli_query($connect, "INSERT INTO `users` (`id`, `surname`, `name`, `login`, `password`, `access`, `email`, `activ_token`) VALUES (NULL, '$surname', '$name', '$log', '$hash', 0, '$email', NULL)");
                $userId = $connect->insert_id; // Получаем ID вставленной записи

                $conn = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                // Подключаем автозагрузчик
                require 'vendor/autoload.php';

                $mail = new PHPMailer(true);
                try {
                    // Настройки SMTP
                    $mail->isSMTP();
                    $mail->Host = 'smtp.mail.ru';  // Замените на ваш SMTP-сервер
                    $mail->SMTPAuth = true;
                    $mail->Username = 'kirvened@mail.ru';
                    // Пароль приложения Gmail
                    $mail->Password = 'TQw0M8Wjddjt7rkJGgwe'; // Замените на ваш пароль или пароль приложения
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Используем STARTTLS
                    $mail->Port = 587;  // Порт для STARTTLS

                    // Настройки письма
                    $mail->setFrom('kirvened@mail.ru', 'Meshi'); // Замените на ваш email и имя
                    $mail->addAddress($email, $name); // Замените на email и имя получателя

                    // Генерация токена активации
                    $token = bin2hex(random_bytes(32));

                    // Обновление токена в базе данных
                    $stmt = $conn->prepare("UPDATE users SET activ_token = :token WHERE id = :id");
                    $stmt->bindParam(':token', $token, PDO::PARAM_STR);
                    $stmt->bindParam(':id', $userId, PDO::PARAM_INT);  // Используем $userId
                    $stmt->execute();

                    // Отправка письма с токеном
                    $activation_link = "https://site/activation.php?token=" . $token; // ссылка
                    $message = "<p>Пожалуйста, перейдите по <a href='$activation_link'>ссылке</a> для активации вашего аккаунта: </p><a href='$activation_link'>Activate</a>";

                    $mail->Subject = 'Account activation';
                    $mail->Body = $message;
                    $mail->isHTML(true); // Если письмо в HTML формате
                    //$mail->SMTPDebug = SMTP::DEBUG_SERVER;  // Выводит подробную отладочную информацию
                    //  $mail->AltBody = 'Альтернативный текст, для тех, кто не может просмотреть HTML';
                    $mail->send();

                    $activation_sent = true; // Устанавливаем флаг успешной отправки

                } catch (Exception $e) {
                    $general_error = "Ошибка отправки письма: {$mail->ErrorInfo}";
                }

                $conn = null;
                $connect->close();
            endif;

        } catch (Exception $e) {
            $general_error = "Ошибка регистрации: " . $e->getMessage();
        }
    endif;
endif;

?>
<!DOCTYPE html>
<html>
<head>
    <title>Регистрация</title>
    <link rel="stylesheet" type="text/css" href="styles/registr.css">
</head>
<body>

<div class="container">
    <h1>Регистрация</h1>

    <form method="POST" action="" class="registration-form">
        <?php if (!empty($general_error) && empty($errors['log']) && empty($errors['email']) && empty($errors['pas'])): ?>
            <p class="error"><?php echo htmlspecialchars($general_error); ?></p>
        <?php endif; ?>

        <div class="form-group">
            <label for="surname">Фамилия:</label>
            <input type="text" id="surname" name="surname" value="<?php echo htmlspecialchars($surname); ?>">
        </div>

        <div class="form-group">
            <label for="name">Имя:</label>
            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($name); ?>">
        </div>

        <div class="form-group">
            <label for="login">Логин:</label>
            <input type="text" id="log" name="log" value="<?php echo htmlspecialchars($log); ?>"
                   class="<?php echo !empty($errors['log']) ? 'error-input' : ''; ?>">
            <?php if (!empty($errors['log'])): ?>
                <span class="error-message"><?php echo htmlspecialchars($errors['log']); ?></span>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label for="password">Пароль:</label>
            <input type="password" id="pas" name="pas"
                   class="<?php echo !empty($errors['pas']) ? 'error-input' : ''; ?>">
            <?php if (!empty($errors['pas'])): ?>
                <span class="error-message"><?php echo htmlspecialchars($errors['pas']); ?></span>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>"
                   class="<?php echo !empty($errors['email']) ? 'error-input' : ''; ?>">
            <?php if (!empty($errors['email'])): ?>
                <span class="error-message"><?php echo htmlspecialchars($errors['email']); ?></span>
            <?php endif; ?>
        </div>

        <button type="submit" name="reg">Зарегистрироваться</button>
    </form>
    <div id="activationSentDialog" class="success-dialog <?php if ($activation_sent) { echo 'show'; } ?>">
        Письмо с активацией отправлено на вашу почту
    </div>
</div>

<script src="js/registr.js"></script>

</body>
</html>