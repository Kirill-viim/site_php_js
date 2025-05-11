<?php
session_start();

// Check if user is logged in, redirect if not
if (!isset($_SESSION['user'])) {
    header("Location: avtoriz.php");
    exit;
} else {
    $id = $_SESSION['user'];
    $connect = mysqli_connect('MySQL-8.2', 'root', '', 'site');

    if (!$connect) {
        die("Ошибка подключения: " . mysqli_connect_error());
    }

    $user_query = mysqli_query($connect, "SELECT surname, name FROM users WHERE id='$id'");
    if ($user_query) {
        $user = mysqli_fetch_assoc($user_query);
        $surname = htmlspecialchars($user['surname']);
        $name = htmlspecialchars($user['name']);
        mysqli_free_result($user_query);
    } else {
        $surname = 'Unknown';
        $name = 'User';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Информация о преподавателе</title>
    <link rel="stylesheet" href="styles/view_teacher.css">
</head>
<body>
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
$host = 'MySQL-8.2';
$user = 'root';
$pass = '';
$db = 'site';
$connect = mysqli_connect($host, $user, $pass, $db);

if (!$connect) {
    die("Ошибка подключения: " . mysqli_connect_error());
}

// Get teacher ID from GET request
if (isset($_GET['tr']) && is_numeric($_GET['tr'])) {
    $teacher_id = (int)$_GET['tr']; // Sanitize the ID
} else {
    echo "<p class='error-message'>Неверный ID преподавателя.</p>";
    exit;
}

// Get teacher information and department
$teacher_query = mysqli_query($connect, "
    SELECT 
        t.surname, 
        t.name, 
        t.patronymic, 
        t.education_level, 
        t.email,
        d.name as name2
    FROM teachers t
    INNER JOIN departments d ON t.department_id = d.id
    WHERE t.id = '$teacher_id'
");

if ($teacher_query && mysqli_num_rows($teacher_query) > 0) {
    $teacher = mysqli_fetch_assoc($teacher_query);
    echo "<h1>Сведения о преподавателе и контакты</h1>";

    

    echo "<div class='teacher-block'>";
    echo "<p><strong>ФИО:</strong> " . htmlspecialchars($teacher['surname'] . ' ' . $teacher['name'] . ' ' . $teacher['patronymic']) . "</p>";
    echo "</div>";

    echo "<div class='teacher-block'>";
    echo "<p><strong>Кафедра:</strong> " . htmlspecialchars($teacher['name2']) . "</p>";
    echo "</div>";

    echo "<div class='teacher-block'>";
    echo "<p><strong>Уровень образования:</strong> " . htmlspecialchars($teacher['education_level']) . "</p>";
    echo "</div>";

    echo "<div class='teacher-block'>";
    echo "<p><strong>Email:</strong> " . htmlspecialchars($teacher['email']) . "</p>";
    echo "</div>";

    mysqli_free_result($teacher_query);
} else {
    echo "<p class='error-message'>Преподаватель не найден.</p>";
}

mysqli_close($connect);
?>
</div>
</body>
</html>