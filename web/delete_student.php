<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Удалить студента</title>
    <link rel="stylesheet" href="styles/delete_student.css">
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
        $del_id = $_POST['del_id'];
        if (empty($del_id)) {
            $error = 'Не выбран студент для удаления.';
        }
        if (empty($error)) {
            $query = "DELETE FROM `students` WHERE stud_id='$del_id'";
            if (mysqli_query($connect, $query)) {
                header("Location: view_group.php?gp=" . $gp);
                exit;
            } else {
                $error = 'Ошибка при удалении студента: ' . mysqli_error($connect);
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
        echo "<h1>Удаление студентов группы " . htmlspecialchars($row_group['group_name']) . "</h1>";
        mysqli_free_result($group_query);
        ?>

        <form method='post'>
            <div class="form-group">
                <label for="del_id">Выберите студента для удаления:</label>
                <select name="del_id" id="del_id">
                    <option value="">-- Выберите студента --</option>
                    <?php
                    $query_students = mysqli_query($connect, "SELECT stud_id, surname, name, patronymic FROM students WHERE gp='" . $_SESSION['gp'] . "' ORDER BY surname, name, patronymic");
                    while ($row_student = mysqli_fetch_assoc($query_students)) {
                        $fio = htmlspecialchars($row_student['surname'] . ' ' . $row_student['name'] . ' ' . $row_student['patronymic']);
                        echo "<option value='" . htmlspecialchars($row_student['stud_id']) . "'>" . $fio . "</option>";
                    }
                    mysqli_free_result($query_students);
                    ?>
                </select>
            </div>

            <div class="button-group">
                <button type='submit' name='redacted' value='save' class="button save-button">Удалить</button>
                <button type='submit' name='redacted' value='cancel' class="button cancel-button">Назад</button>
            </div>
        </form>

        <div id="errorDialog" class="error-dialog">
            <?php echo $error; ?>
        </div>
    </div>
</div>

<?php mysqli_close($connect); ?>

<script src="js/delete_student.js"></script>
</body>
</html>