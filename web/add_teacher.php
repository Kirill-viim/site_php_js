<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Добавить преподавателя</title>
    <link rel="stylesheet" href="styles/add_teacher.css">
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
    if ($_POST['redacted'] == 'cancel') {
        header("Location: index.php");  // Replace with the correct URL
        exit;
    }
    if ($_POST['redacted'] == 'save') {
        $surname = trim($_POST['surname']);
        $name = trim($_POST['name']);
        $patronymic = trim($_POST['patronymic']);
        $department_id = $_POST['department_id']; // Get department ID from form

        // Validation
        if (empty($surname)) {
            $error = 'Поле "Фамилия" не заполнено.';
        } elseif (empty($name)) {
            $error = 'Поле "Имя" не заполнено.';
        } elseif (empty($patronymic)) {
            $error = 'Поле "Отчество" не заполнено.';
        } elseif (empty($department_id)) {
            $error = 'Выберите кафедру.';
        } elseif (strlen($surname) > 50 || strlen($name) > 50 || strlen($patronymic) > 50) {
            $error = 'Длина полей "Фамилия", "Имя" и "Отчество" не должна превышать 50 символов.';
        }

        if (empty($error)) {
            $query = "INSERT INTO `teachers` (`teacher_id`, `surname`, `name`, `patronymic`, `department_id`)
                      VALUES (NULL, '$surname', '$name', '$patronymic', '$department_id')";

            if (mysqli_query($connect, $query)) {
                header("Location: index.php"); // Replace with the correct URL
                exit;
            } else {
                $error = 'Ошибка при добавлении преподавателя: ' . mysqli_error($connect);
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
        <h1>Добавление преподавателя</h1>
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

            <div class="form-group">
                <label for="department_id">Кафедра:</label>
                <select name="department_id" id="department_id">
                    <option value="">-- Выберите кафедру --</option>
                    <?php
                    $query_departments = mysqli_query($connect, "SELECT id, name FROM departments ORDER BY name");
                    while ($row_department = mysqli_fetch_assoc($query_departments)) {
                        echo "<option value='" . htmlspecialchars($row_department['id']) . "'>" . htmlspecialchars($row_department['name']) . "</option>";
                    }
                    mysqli_free_result($query_departments);
                    ?>
                </select>
            </div>

            <div class="button-group">
                <button type='submit' name='redacted' value='save' class="button save-button">Добавить</button>
                <button type='submit' name='redacted' value='cancel' class="button cancel-button">Назад</button>
            </div>
        </form>

        <div id="errorDialog" class="error-dialog">
            <?php echo $error; ?>
        </div>
    </div>
</div>

<?php mysqli_close($connect); ?>

<script src="js/add_teacher.js"></script>
</body>
</html>