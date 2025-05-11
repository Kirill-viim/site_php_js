<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

// Database connection details
$servername = "MySQL-8.2";
$username = "root";
$password = "";
$dbname = "site";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = $_SESSION['user'];

// Handle form submission (update profile)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $surname = htmlspecialchars($_POST['surname']);
    $name = htmlspecialchars($_POST['name']);
    $phone = htmlspecialchars($_POST['phone']);
    $department = htmlspecialchars($_POST['department']);
    $fav_obj = htmlspecialchars($_POST['fav_obj']);
    $extra = htmlspecialchars($_POST['extra']);

    $sql_update = "UPDATE users SET surname='$surname', name='$name', phone='$phone', department='$department', fav_obj='$fav_obj', extra='$extra' WHERE id='$user_id'";

    if ($conn->query($sql_update) === TRUE) {
        $update_message = "Профиль успешно обновлен!";
    } else {
        $update_message = "Ошибка обновления профиля: " . $conn->error;
    }
}

// SQL query to fetch user data
$sql = "SELECT surname, name, email, phone, department, fav_obj, extra FROM users WHERE id = '$user_id'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Fetch user data
    $row = $result->fetch_assoc();

    // Prepare the data to be sent as JSON
    $user_data = array(
        'surname' => htmlspecialchars($row['surname']),
        'name' => htmlspecialchars($row['name']),
        'email' => htmlspecialchars($row['email']),
        'phone' => htmlspecialchars($row['phone']),
        'department' => htmlspecialchars($row['department']),
        'fav_obj' => htmlspecialchars($row['fav_obj']),
        'extra' => htmlspecialchars($row['extra'])
    );

    $json_data = json_encode($user_data);

} else {
    // If no user found, set an error message
    $json_data = json_encode(array('error' => 'Пользователь не найден'));
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Профиль пользователя</title>
    <link rel="stylesheet" href="styles/profile.css">
</head>
<body>

    <div class="header fixed-header">
        <a href="index.php" class="home-button">На главную</a>
    </div>

    <div id="profile-container"></div>

    <!-- Include React libraries -->
    <script src="https://unpkg.com/react@18/umd/react.development.js" crossorigin></script>
    <script src="https://unpkg.com/react-dom@18/umd/react-dom.development.js" crossorigin></script>

    <script>
        const userDataFromPHP = <?php echo $json_data; ?>;

        function Profile({ userData }) {
            if (!userData) {
                return React.createElement('div', null, 'Нет данных о профиле.');
            }

            if (userData.error) {
                return React.createElement('div', null, 'Ошибка: ' + userData.error);
            }

            const handleSubmit = (event) => {
                event.preventDefault();

                const formData = new FormData(event.target);
                fetch(window.location.href, {
                    method: 'POST',
                    body: formData
                })
                .then(response => {
                    if (response.ok) {
                        alert('Профиль успешно обновлен!');
                        window.location.reload();
                    } else {
                        alert('Ошибка обновления профиля.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Ошибка обновления профиля.');
                });
            };

            return React.createElement(
                'div',
                { className: 'profile-container' },
                React.createElement(
                    'div',
                    { className: 'profile-header' },
                    React.createElement('h1', null, 'Профиль пользователя'),
                    React.createElement('span', { className: 'profile-email' }, userData.email)
                ),
                React.createElement('form', {onSubmit: handleSubmit},
                    React.createElement('div', {className: 'profile-info'},
                        React.createElement('label', {htmlFor: 'name'}, React.createElement('strong', null, 'Имя ')),
                        React.createElement('input', {type: 'text', id: 'name', name: 'name', className: 'edit-input', defaultValue: userData.name})
                    ),
                    React.createElement('div', {className: 'profile-info'},
                        React.createElement('label', {htmlFor: 'surname'}, React.createElement('strong', null, 'Фамилия ')),
                        React.createElement('input', {type: 'text', id: 'surname', name: 'surname', className: 'edit-input', defaultValue: userData.surname})
                    ),
                    React.createElement('div', {className: 'profile-info'},
                        React.createElement('label', {htmlFor: 'phone'}, React.createElement('strong', null, 'Телефон ')),
                        React.createElement('input', {type: 'text', id: 'phone', name: 'phone', className: 'edit-input', defaultValue: userData.phone})
                    ),
                    React.createElement('div', {className: 'profile-info'},
                        React.createElement('label', {htmlFor: 'department'}, React.createElement('strong', null, 'Отдел ')),
                        React.createElement('input', {type: 'text', id: 'department', name: 'department', className: 'edit-input', defaultValue: userData.department})
                    ),
                    React.createElement('div', {className: 'profile-info'},
                        React.createElement('label', {htmlFor: 'fav_obj'}, React.createElement('strong', null, 'Любимый предмет ')),
                        React.createElement('input', {type: 'text', id: 'fav_obj', name: 'fav_obj', className: 'edit-input', defaultValue: userData.fav_obj})
                    ),
                    React.createElement('div', {className: 'profile-info'},
                        React.createElement('label', {htmlFor: 'extra'}, React.createElement('strong', null, 'Дополнительная информация ')),
                        React.createElement('textarea', {id: 'extra', name: 'extra', className: 'edit-input'}, userData.extra)
                    ),
                    React.createElement('button', {type: 'submit', className: 'save-button'}, 'Сохранить')
                )
            );
        }

        // Render the React component in the DOM
        document.addEventListener('DOMContentLoaded', () => {
            const container = document.getElementById('profile-container');
            if (container) {
                const root = ReactDOM.createRoot(container);
                root.render(React.createElement(Profile, { userData: userDataFromPHP }));
            } else {
                console.error("Container with id 'profile-container' not found.");
            }
        });
    </script>

    <div class="footer fixed-footer">
        <a href="avtoriz.php" class="logout-button">Выйти</a>
    </div>

</body>
</html>