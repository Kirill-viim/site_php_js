<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Редактировать группу</title>
    <link rel="stylesheet" href="styles/edit_group.css">
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
$group_id = $_SESSION['gp'];
$error = '';
$change = TRUE;
if (isset($_POST['id']) && isset($_POST['redacted'])) {
    $id = $_POST['id'];
    if ($_POST['redacted'] == 'cancel') {
        header("Location: view_group.php?gp=" . $group_id); // Pass the group ID
        exit;
    }
    if ($_POST['redacted'] == 'save') {
        $new_name = $_POST['new_name'];
        if (empty($new_name)) {
            $error = 'Поле не заполнено';
        } elseif (strlen($new_name) > 10) {
            $error = 'Название группы не должно превышать 10 символов';
        } else {
            $groups = mysqli_query($connect, "SELECT * FROM `groups`");
            while ($group = mysqli_fetch_assoc($groups)) {
                if ($new_name == $group['group_name']) {
                    $error = 'Группа с таким именем существует';
                    break;
                }
            }
        }
        if ($error == '') {
            mysqli_query($connect, "UPDATE `groups` SET `group_name` = '$new_name' WHERE `groups`.`group_id` = '$id'");
            $change = FALSE;
            header("Location: view_group.php?gp=" . $group_id); // Pass the group ID
            exit;
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
        $group_query = mysqli_query($connect, "SELECT * FROM `groups` WHERE group_id='$group_id'");
        $row_group = mysqli_fetch_assoc($group_query);
        echo "<h1>Редактирование группы " . htmlspecialchars($row_group['group_name']) . "</h1>";
        mysqli_free_result($group_query);
        ?>

        <form method='post'>
            <?php
            echo "<input type='hidden' name='id' value='$group_id'>";
            ?>
            <div class="form-group">
                <label for="new_name">Новое название группы:</label>
                <input type='text' name='new_name' id="new_name">
            </div>

            <div class="button-group">
                <button type='submit' name='redacted' value='save' class="button save-button">Сохранить</button>
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

<script>
    // JavaScript для отображения и скрытия диалогового окна
    document.addEventListener("DOMContentLoaded", function() {
        const errorDialog = document.getElementById('errorDialog');
        if (errorDialog.textContent.trim() !== '') {
            errorDialog.classList.add('show'); // Показать диалоговое окно

            setTimeout(function() {
                errorDialog.classList.remove('show'); // Скрыть диалоговое окно
                setTimeout(function() {
                    errorDialog.style.display = 'none'; // Полностью скрыть элемент
                }, 500); // Подождать окончание анимации
            }, 5000); // Скрыть через 5 секунд
        }
    });
</script>
</body>
</html>