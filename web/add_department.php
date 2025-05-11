<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Добавить кафедру</title>
    <link rel="stylesheet" href="styles/add_department.css">
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
        header("Location: index.php"); // Или туда, где список кафедр
        exit;
    }
    if ($_POST['redacted'] == 'save') {
        $new_name = trim($_POST['new_name']);
        $new_descriptionn = trim($_POST['new_descriptionn']);

        if (empty($new_name)) {
            $error = 'Поле не заполнено';
        } elseif (strlen($new_name) > 100) { // Adjust max length as needed
            $error = 'Название кафедры не должно превышать 100 символов';
        } else {
            // Check if a department with the same name already exists
            $query = "SELECT * FROM `departments` WHERE `department_name` = '$new_name'";
            $result = mysqli_query($connect, $query);

            if ($result && mysqli_num_rows($result) > 0) {
                $error = 'Кафедра с таким именем уже существует';
            } else {

                $query = "INSERT INTO `departments` (`department_id`, `department_name`, `description`) VALUES (NULL, '$new_name', '$new_description')";
                if (mysqli_query($connect, $query)) {
                    header("Location: index.php"); // Или туда, где список кафедр
                    exit;
                } else {
                    $error = 'Ошибка при добавлении кафедры: ' . mysqli_error($connect);
                }
            }

           if ($result) mysqli_free_result($result);
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
        <h1>Добавление кафедры</h1>
        <form method='post'>
            <div class="form-group">
                <label for="new_name">Название</label>
                <input type='text' name='new_name' id="new_name">
            </div>
            <div class="form-group">
                <label for="new_description">Описание</label>
                <textarea name='new_description' id="new_description"></textarea>
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

<script src="js/add_department.js"></script>
</body>
</html>