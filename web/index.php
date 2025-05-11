<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Главная</title>
    <link rel="stylesheet" href="styles/index.css">
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
if (!isset($_SESSION['user'])) header("Location: avtoriz.php");
else{
    $id = $_SESSION['user'];
    $user_query=mysqli_query($connect, "SELECT surname, name FROM users WHERE id='$id'");
    $user = mysqli_fetch_assoc($user_query);
    $surname=$user['surname'];
    $name=$user['name'];
    mysqli_free_result($user_query);
};
?>
<div class="header">
    <div class="emblem-name">
        <div class="emblem"><a href="index.php"> <img src="images/emblem.png"></a></div>
        <div class="name"><a href="index.php">  Донской Государственный</a><br><a href="index.php"> Технический Университет</a></div>
    </div>
    <div class="info">
            <a href="https://vk.com/donstu?from=groups" class="subscribe-button">Подписаться</a>
            <a href="profile.php" class="profile-button">Профиль</a>
    </div>
</div>

<div class="button-container">
    <?php
    if ($_SESSION['access']==1){
        echo "<form action='vedom.php'><button class='button'>Просмотр ведомости</button></form>";
        echo "<form action='add_group.php'><button class='button'>Добавить группу</button></form>";
        echo "<form action='add_teacher.php'><button class='button'>Добавить преподавателя</button></form>";
        echo "<form action='add_department.php'><button class='button'>Добавить кафедру</button></form>";
    }
    ?>
</div>
<div class="drop"></div><div class="drop"></div><div class="drop"></div><div class="drop"></div>
<div class="search-container">
    <form method="GET" action="">
        <label for="search_type">Поиск по:</label>
        <select name="search_type" id="search_type">
            <option value="group" <?php echo (isset($_GET['search_type']) && $_GET['search_type'] == 'group') ? 'selected' : ''; ?>>Группа</option>
            <option value="teacher" <?php echo (isset($_GET['search_type']) && $_GET['search_type'] == 'teacher') ? 'selected' : ''; ?>>Преподаватель</option>
            <option value="department" <?php echo (isset($_GET['search_type']) && $_GET['search_type'] == 'department') ? 'selected' : ''; ?>>Кафедра</option>
        </select>

        <label for="search_term">Запрос:</label>
        <input type="text" name="search_term" id="search_term" placeholder="Введите запрос" value="<?php echo isset($_GET['search_term']) ? htmlspecialchars($_GET['search_term']) : ''; ?>">

        <button type="submit" class="button2">Найти</button>
    </form>
</div>

<div class="group-list-container">
    <ul>
        <?php
        $search_term = isset($_GET['search_term']) ? $_GET['search_term'] : '';
        $search_type = isset($_GET['search_type']) ? $_GET['search_type'] : 'group';
        $query_groups = null;
        $query_teachers = null;
        $query_departments = null;

        if (!empty($search_term)) {
            $search_term = mysqli_real_escape_string($connect, $search_term);
        
            if ($search_type == 'group') {
                $query_groups = mysqli_query($connect, "SELECT * FROM `groups` WHERE `group_name` LIKE '%$search_term%'");
        
                if ($query_groups) {
                    if (mysqli_num_rows($query_groups) > 0) {
                        echo "<h3>Группы</h3>";
                        echo "<ul class='search-results'>"; // Add classes for styling
                        while ($row_group = mysqli_fetch_assoc($query_groups)) {
                            echo "<li class='clickable-item search-item' data-href='view_group.php?gp=" . htmlspecialchars($row_group['group_id']) . "'>";  // Add data-href and common class
                            echo "<a href='view_group.php?gp=" . htmlspecialchars($row_group['group_id']) . "'>" . htmlspecialchars($row_group['group_name']) . "</a>";
                            echo "</li>";
                        }
                        echo "</ul>"; // Close the list
                    } else {
                        echo "<p>Группы не найдены.</p>";
                    }
                } else {
                    echo "<p>Ошибка при выполнении запроса к группам.</p>";
                }
            } elseif ($search_type == 'teacher') {
                $query_teachers = mysqli_query($connect, "SELECT * FROM `teachers` WHERE CONCAT(`surname`, ' ', `name`, ' ', IFNULL(`patronymic`, '')) LIKE '%$search_term%'");
        
                if ($query_teachers) {
                    if (mysqli_num_rows($query_teachers) > 0) {
                        echo "<h3>Преподаватели</h3>";
                        echo "<ul class='search-results'>"; // Add classes for styling
                        while ($row_teacher = mysqli_fetch_assoc($query_teachers)) {
                            $full_name = htmlspecialchars($row_teacher['surname'] . ' ' . $row_teacher['name'] . ' ' . $row_teacher['patronymic']);
                            // Assuming you have a 'teacher_profile.php' page
                            echo "<li class='clickable-item search-item' data-href='view_teacher.php?tr=" . htmlspecialchars($row_teacher['id']) . "'>";
                            echo $full_name;
                            echo "</li>";
                        }
                        echo "</ul>"; // Close the list
                    } else {
                        echo "<p>Преподаватели не найдены.</p>";
                    }
                } else {
                    echo "<p>Ошибка при выполнении запроса к преподавателям.</p>";
                }
            } elseif ($search_type == 'department') {
                $query_departments = mysqli_query($connect, "SELECT id, name FROM `departments` WHERE `name` LIKE '%$search_term%'");
        
                if ($query_departments) {
                    if (mysqli_num_rows($query_departments) > 0) {
                        echo "<h3>Кафедры</h3>";
                        echo "<ul class='search-results'>"; // Add classes for styling
                        while ($row_department = mysqli_fetch_assoc($query_departments)) {
                            echo "<li class='clickable-item search-item' data-href='view_department.php?dp=" . htmlspecialchars($row_department['id']) . "'>";
                            echo htmlspecialchars($row_department['name']);
                            echo "</li>";
                        }
                        echo "</ul>"; // Close the list
                    } else {
                        echo "<p>Кафедры не найдены.</p>";
                    }
                } else {
                    echo "<p>Ошибка при выполнении запроса к кафедрам.</p>";
                }
            }
        } else {
            echo "<p>Введите запрос для поиска.</p>";
        }
        mysqli_close($connect);
        ?>
    </ul>
</div>

<script src="js/index.js"></script>

</body>
</html>