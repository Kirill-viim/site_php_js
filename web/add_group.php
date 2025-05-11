<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Добавить группу</title>
    <link rel="stylesheet" href="styles/add_group.css">
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
        header("Location: index.php");
        exit;
    }
    if ($_POST['redacted'] == 'save') {
        $new_name = trim($_POST['new_name']); // Trim whitespace

        if (empty($new_name)) {
            $error = 'Поле не заполнено';
        } elseif (strlen($new_name) > 10) {
            $error = 'Название группы не должно превышать 10 символов';
        } else {
            $groups = mysqli_query($connect, "SELECT * FROM `groups`");
            while ($group = mysqli_fetch_assoc($groups)) {
                if ($new_name == $group['group_name']) {
                    $error = 'Группа с таким именем уже существует';
                    break;
                }
            }
        }

        if (empty($error)) {
            $query = "INSERT INTO `groups` (`group_id`, `group_name`) VALUES (NULL, '$new_name')";
            if (mysqli_query($connect, $query)) {
                header("Location: index.php");
                exit;
            } else {
                $error = 'Ошибка при добавлении группы: ' . mysqli_error($connect);
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
        <h1>Добавление группы</h1>
        <form method='post'>
            <div class="form-group">
                <label for="new_name">Название новой группы:</label>
                <input type='text' name='new_name' id="new_name">
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

<script src="js/add_group.js"></script>
</body>
</html>