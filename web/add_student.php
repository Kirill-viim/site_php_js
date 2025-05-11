<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Добавить студента</title>
    <link rel="stylesheet" href="styles/add_student.css">
</head>
<body>
<?php
$host = 'MySQL-8.2';
$user = 'root';
$pass = '';
$db = 'site';
$connect = mysqli_connect($host, $user, $pass, $db);
?>

<?php
session_start();
$error = '';
if (isset($_POST['redacted'])) {
    $gp = $_SESSION['gp']; // Get group ID from session

    if ($_POST['redacted'] == 'cancel') {
        header("Location: view_group.php?gp=" . $gp);
        exit;
    }

    if ($_POST['redacted'] == 'save') {
        $surname = trim($_POST['surname']);
        $name = trim($_POST['name']);
        $patronymic = trim($_POST['patronymic']);

        // Validation
        if (empty($surname)) {
            $error = 'Поле "Фамилия" не заполнено.';
        } elseif (empty($name)) {
            $error = 'Поле "Имя" не заполнено.';
        } elseif (empty($patronymic)) {
            $error = 'Поле "Отчество" не заполнено.';
        } elseif (strlen($surname) > 50 || strlen($name) > 50 || strlen($patronymic) > 50) {
            $error = 'Длина полей "Фамилия", "Имя" и "Отчество" не должна превышать 50 символов.';
        }

        if (empty($error)) {
            // Insert data into the database
            $query = "INSERT INTO `students` (`stud_id`, `surname`, `name`, `patronymic`, `gp`)
                      VALUES (NULL, '$surname', '$name', '$patronymic', '$gp')";
            if (mysqli_query($connect, $query)) {
                header("Location: view_group.php?gp=" . $gp);
                exit;
            } else {
                $error = 'Ошибка при добавлении студента: ' . mysqli_error($connect);
            }
        }
    }
}
?>

<?php
if (!isset($_SESSION['user'])) header("Location: avtoriz.php");
else {
    $id = $_SESSION['user'];
    $user_query = mysqli_query($connect, "SELECT surname, name FROM users WHERE id='$id'");
    $user = mysqli_fetch_assoc($user_query);
    $surname = $user['surname'];
    $name = $user['name'];
    mysqli_free_result($user_query);
}
?>

<div class="container">
    <div class="header">
    <div class="header-left">
            <a href="index.php" class="home-button"><div>←</div><div class="symbol">🏛️</div></a>
        </div>
        <div class="info">
            <a href="profile.php" class="profile-button">Профиль</a>
        </div>
    </div>

    <div class="content">
        <?php
        $group_query = mysqli_query($connect, "SELECT * FROM `groups` WHERE group_id='" . $_SESSION['gp'] . "'");
        $row_group = mysqli_fetch_assoc($group_query);
        echo "<h1>Добавление студентов в группу " . htmlspecialchars($row_group['group_name']) . "</h1>";
        mysqli_free_result($group_query);
        ?>

        <form method='post'>
            <div class="form-group">
                <label for="surname">Фамилия:</label>
                <input type='text' name='surname' id="surname">
            </div>
            <div class="form-group">
                <label for="name">Имя:</label>
                <input type='text' name='name' id="name">
            </div>
            <div class="form-group">
                <label for="patronymic">Отчество:</label>
                <input type='text' name='patronymic' id="patronymic">
            </div>

            <div class="button-group">
                <button type='submit' name='redacted' value='save' class="button save-button">Добавить</button>
                <button type='submit' name='redacted' value='cancel' class="button cancel-button">Назад</button>
            </div>
        </form>

        <!-- Диалоговое окно для ошибок -->
        <div id="errorDialog" class="error-dialog">
            <?php echo $error; ?>
        </div>
    </div>
</div>

<?php mysqli_close($connect); ?>

<script src="js/add_student.js"></script>
</body>
</html>