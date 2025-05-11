<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Информация о группе</title>
    <link rel="stylesheet" href="styles/view_group.css">
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
if (!isset($_SESSION['vedom'])) {
  $_SESSION['vedom'] = array();
}
if (!isset($_SESSION['user'])) header("Location: avtoriz.php");
else{
    $id = $_SESSION['user'];
    $user_query=mysqli_query($connect, "SELECT surname, name FROM users WHERE id='$id'");
    $user = mysqli_fetch_assoc($user_query);
    $surname=$user['surname'];
    $name=$user['name'];
    mysqli_free_result($user_query);
};

// Handle adding student to ведомость
if (isset($_GET['add_to_vedom'])) {
    $student_id = $_GET['add_to_vedom'];
    if (!isset($_SESSION['vedom'])) {
        $_SESSION['vedom'] = array();
    }
    if (!in_array($student_id, $_SESSION['vedom'])) {
        $_SESSION['vedom'][] = $student_id;
    }
}

?>
<div class="container">
    <div class="header">
        <div class="header-left">
            <a href="index.php" class="home-button"><div>←</div><div class="symbol">🏛️</div></a>
        </div>
        <div class="info">
            <a href="https://vk.com/donstu?from=groups" class="subscribe-button">Подписаться</a>
            <a href="profile.php" class="profile-button">Профиль</a>
        </div>
    </div>

    <?php
    if (isset($_GET['gp'])){
        $_SESSION['gp'] = $_GET['gp'];
    }
    $gp = $_SESSION['gp'];
    $gp_query = mysqli_query($connect, "SELECT * FROM `groups` WHERE `group_id` = '$gp'");
    $gp_data = mysqli_fetch_assoc($gp_query);
    ?>


    <div class="group-actions">
        <?php
        if ($_SESSION['access'] == 1) {
            echo "<a href='edit_group.php?gp=" . $gp . "' class='button'>Редактировать</a>";
            echo "<a href='add_student.php?gp=" . $gp . "' class='button'>Добавить студента</a>";
            echo "<a href='delete_student.php?gp=" . $gp . "' class='button'>Удалить студента</a>";
        }
        ?>
    </div>

    <div class="group-title-and-count"> <!-- New container -->
        <?php
        $students_query = mysqli_query($connect, "SELECT COUNT(*) AS student_count FROM `students` WHERE `gp` = '$gp'");
        $students_data = mysqli_fetch_assoc($students_query);
        $student_count = $students_data['student_count'];
        ?>

        <h1>
            <?php echo htmlspecialchars($gp_data['group_name']); ?>
            <span class="student-count">(<?php echo $student_count; ?>)</span>
        </h1>
    </div>

    <div class="group-details">
        
        <ul class="student-list">
            <?php
            $students_query = mysqli_query($connect, "SELECT * FROM `students` WHERE `gp` = '$gp' ORDER BY `surname` ASC, `name` ASC, `patronymic` ASC");
            while ($student = mysqli_fetch_assoc($students_query)) {

                // Check if 'stud_id' key exists
                if (!isset($student['stud_id'])) {
                    echo "<p>Error: Student ID not found in database!</p>";
                    continue; // Skip this student
                }

                $student_id = htmlspecialchars($student['stud_id']);
                $surname = isset($student['surname']) ? htmlspecialchars($student['surname']) : '';
                $name = isset($student['name']) ? htmlspecialchars($student['name']) : '';
                $patronymic = isset($student['patronymic']) ? htmlspecialchars($student['patronymic']) : '';

                echo "<li class='student-item'>";
                echo "<span class='student-name'>" . $surname . ' ' . $name . ' ' . $patronymic . "</span>";
                if ($_SESSION['access'] == 1) {
                    echo "<a href='view_group.php?gp=$gp&add_to_vedom=$student_id' class='add-vedom-button'>Добавить в ведомость</a>"; // Add student button
                }
                echo "</li>";
            }
            ?>
        </ul>
    </div>
</div>
</body>
</html>