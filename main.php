<?php
require_once 'db.php'; // Підключення до бази даних
require_once 'Client.php'; // Підключення класу Client

// Додавання нового клієнта
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["surname"])) {
    $surname = trim($_POST["surname"]);
    $name = trim($_POST["name"]);
    $address = trim($_POST["address"]);
    $birthdate = $_POST["birthdate"];
    $gender = trim($_POST["gender"]);
    $credit = trim($_POST["credit"]);
    $phone = trim($_POST["phone"]);

    if ($surname && $name && $address && $birthdate && $gender && $credit && $phone) {
        $client = new Client($surname, $name, $address, $birthdate, $gender, $credit, $phone);
        $client->save();
        header("Location: " . $_SERVER["PHP_SELF"]);
        exit;
    } else {
        echo "<p class='error'>Будь ласка, заповніть всі поля!</p>";
    }
}

// Пошук клієнтів за датою народження
$searchDate = $_GET['search_date'] ?? '';
$clients = [];
if ($searchDate) {
    $clients = Client::getByBirthdate($searchDate);
} else {
    $clients = Client::getAll();
}

?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Клієнти банку</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #e3f2fd;
            margin: 0;
            padding: 20px;
            text-align: center;
        }
        .container {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            max-width: 850px;
            margin: auto;
        }
        h2 {
            color: #0277bd;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            background: white;
        }
        table, th, td {
            border: 1px solid #ccc;
        }
        th, td {
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #0288d1;
            color: white;
        }
        form {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
        }
        input, button {
            padding: 12px;
            margin: 5px;
            width: 100%;
            max-width: 450px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        button {
            background-color: #00796b;
            color: white;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
        }
        button:hover {
            background-color: #004d40;
        }
        .error {
            color: red;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Додати нового клієнта</h2>
        <form method="POST" action="">
            <input type="text" name="surname" placeholder="Прізвище" required>
            <input type="text" name="name" placeholder="Ім'я" required>
            <input type="text" name="address" placeholder="Адреса" required>
            <input type="date" name="birthdate" placeholder="Дата народження" required>
            <input type="text" name="gender" placeholder="Стать" required>
            <input type="number" name="credit" placeholder="Сума кредиту" required>
            <input type="text" name="phone" placeholder="Телефон" required>
            <button type="submit">Додати клієнта</button>
        </form>

        <h2>Пошук клієнтів за датою народження</h2>
        <form method="GET" action="">
            <input type="date" name="search_date" placeholder="Дата народження">
            <button type="submit">Пошук</button>
        </form>

        <h3>Клієнти:</h3>
        <table>
            <thead>
                <tr>
                    <th>Прізвище</th>
                    <th>Ім'я</th>
                    <th>Адреса</th>
                    <th>Дата народження</th>
                    <th>Стать</th>
                    <th>Сума кредиту</th>
                    <th>Телефон</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($clients as $client): ?>
                <tr>
                    <td><?= htmlspecialchars($client->surname) ?></td>
                    <td><?= htmlspecialchars($client->name) ?></td>
                    <td><?= htmlspecialchars($client->address) ?></td>
                    <td><?= htmlspecialchars($client->birthdate) ?></td>
                    <td><?= htmlspecialchars($client->gender) ?></td>
                    <td><?= htmlspecialchars($client->credit) ?></td>
                    <td><?= htmlspecialchars($client->phone) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
