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
    if ($user_query) { // Add check here
        $user = mysqli_fetch_assoc($user_query);
        $surname = htmlspecialchars($user['surname']);
        $name = htmlspecialchars($user['name']);
        mysqli_free_result($user_query);
    } else {
        $surname = 'Unknown';
        $name = 'User';
    }

    mysqli_close($connect);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Сведения о кафедре</title>
    <link rel="stylesheet" href="styles/view_department.css">
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

// Get department ID from GET request
if (isset($_GET['dp']) && is_numeric($_GET['dp'])) {
    $_SESSION['dp'] = (int)$_GET['dp']; // Sanitize the ID
} 

$department_id = $_SESSION['dp'];

// Get department name
$department_query = mysqli_query($connect, "SELECT name, description FROM departments WHERE id = '$department_id'");

if ($department_query && mysqli_num_rows($department_query) > 0) {
    $department = mysqli_fetch_assoc($department_query);
    $department_name = htmlspecialchars($department['name']);
    mysqli_free_result($department_query);
} else {
    echo "<p class='error-message'>Кафедра не найдена.</p>";
    exit;
}
?>

<div class="department-title-and-count"> <!-- New container -->
      <?php
      $teachers_query = mysqli_query($connect, "SELECT COUNT(*) AS teacher_count FROM `teachers` WHERE `department_id` = '$department_id'");
      $teachers_data = mysqli_fetch_assoc($teachers_query);
      $teacher_count = $teachers_data['teacher_count'];
      ?>

      <h1>
      <?php echo htmlspecialchars($department['name']); ?>
      <span class="teacher-count">(<?php echo $teacher_count; ?>)</span>
      </h1>
</div>
<?php echo "<h2>Описание</h2>";?>
<div class="description">
      <?php echo isset($department['description']) ? htmlspecialchars((string)$department['description']) : '';?>
</div>

<?php
// Get list of teachers in this department along with their education level
$teachers_query = mysqli_query($connect, "SELECT id, surname, name, patronymic, education_level FROM teachers WHERE department_id = '$department_id' ORDER BY surname, name, patronymic ASC");

if ($teachers_query) {
    $teacher_count = mysqli_num_rows($teachers_query); // Count teachers
    echo "<h2>Преподаватели</h2>";
    echo "<ul class='teachers-list'>";

    while ($teacher = mysqli_fetch_assoc($teachers_query)) {
        $fio = htmlspecialchars($teacher['surname'] . ' ' . $teacher['name'] . ' ' . $teacher['patronymic']);
        $education_level = htmlspecialchars($teacher['education_level']);

        echo "<li class='teacher-item'>";
        echo "<span class='teacher-name'>" . $fio . "</span>";
        echo "<span class='education-level'>" . $education_level . "</span>";
        echo "</li>";
    }

    echo "</ul>";
    mysqli_free_result($teachers_query);
} else {
    echo "<p class='error-message'>Ошибка при запросе преподавателей: " . mysqli_error($connect) . "</p>";
}

mysqli_close($connect);
?>
</div>
</body>
</html>