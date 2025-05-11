<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Ведомость</title>
    <link rel="stylesheet" href="styles/vedom.css">
</head>
<body>
<?php
$host = 'MySQL-8.2';
$user = 'root';
$pass = '';
$db = 'site';
$connect = mysqli_connect($host, $user, $pass, $db);

if (!$connect) {
    die("Ошибка подключения: " . mysqli_connect_error());
}

session_start();
if (!isset($_SESSION['vedom'])) {
    $_SESSION['vedom'] = [];
}
if (isset($_POST['clear'])) {
    $_SESSION['vedom'] = [];
}

if (!isset($_SESSION['user'])) {
    header("Location: avtoriz.php");
    exit;
} else {
    $id = $_SESSION['user'];
    $user_query = mysqli_query($connect, "SELECT surname, name FROM users WHERE id='$id'");

    if ($user_query && mysqli_num_rows($user_query) > 0) {
        $user = mysqli_fetch_assoc($user_query);
        $surname = htmlspecialchars($user['surname']);
        $name = htmlspecialchars($user['name']);
        mysqli_free_result($user_query);
    } else {
        // Handle the case where user data could not be retrieved
        $surname = 'Unknown';
        $name = 'User';
    }
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
    <?php $unique_student_count = count(array_unique($_SESSION['vedom'])); ?>
    <h1>
        Ведомость
    </h1>
    <form method='POST' action='delete.php'>
        <table class="vedom-table">
            <thead>
            <tr>
                <th>Группа</th>
                <th>Фамилия</th>
                <th>Имя</th>
                <th>Отчество</th>
            </tr>
            </thead>
            <tbody>
            <?php
            $all_groups = mysqli_query($connect, "SELECT * FROM `groups` ORDER BY `groups`.`group_name` ASC");

            if ($all_groups) {
                while ($group = mysqli_fetch_assoc($all_groups)) {
                    $gp_id = $group['group_id'];
                    // Get surname, name, and patronymic from the students table
                    $students_query = mysqli_query($connect, "SELECT surname, name, patronymic, stud_id FROM `students` WHERE gp='$gp_id' ORDER BY surname, name, patronymic ASC");

                    if ($students_query) {
                        while ($student = mysqli_fetch_assoc($students_query)) {
                            if (in_array($student['stud_id'], $_SESSION['vedom'])) {
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($group['group_name']) . "</td>";
                                echo "<td>" . htmlspecialchars($student['surname']) . "</td>";
                                echo "<td>" . htmlspecialchars($student['name']) . "</td>";
                                echo "<td>" . htmlspecialchars($student['patronymic']) . "</td>";
                                echo "</tr>";
                            }
                        }
                        mysqli_free_result($students_query);
                    } else {
                        echo "<tr><td colspan='4'>Ошибка при запросе студентов: " . mysqli_error($connect) . "</td></tr>";
                    }
                }
                mysqli_free_result($all_groups);
            } else {
                echo "<tr><td colspan='4'>Ошибка при запросе групп: " . mysqli_error($connect) . "</td></tr>";
            }
            ?>
            </tbody>
        </table>
    </form>

    <div class="buttons">
        <form method='post'>
            <button type='submit' name='clear' class="clear-button">Очистить ведомость</button>
        </form>
        
        <form action='index.php'>
            <button class="back-button">Назад</button>
        </form>
    </div>
</div>

<?php mysqli_close($connect); ?>

<!-- Include React and the compiled JSX -->
<script src="https://unpkg.com/react@18/umd/react.development.js" crossorigin></script>
<script src="https://unpkg.com/react-dom@18/umd/react-dom.development.js" crossorigin></script>
<script src="js/main.js"></script> <!-- Corrected path -->
<script src="js/renderStudentCount.js"></script> <!-- Corrected path -->

</body>
</html>