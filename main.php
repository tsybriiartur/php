<?php

$filename = "clients.txt";

function readClients($filename) {
    if (!file_exists($filename)) return [];
    $data = file($filename, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    return array_map(fn($line) => explode("|", $line), $data);
}

function addClient($filename, $clientData) {
    $line = implode("|", $clientData) . "\n";
    file_put_contents($filename, $line, FILE_APPEND | LOCK_EX);
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["surname"])) {
    $surname = trim($_POST["surname"]);
    $name = trim($_POST["name"]);
    $address = trim($_POST["address"]);
    $birthdate = trim($_POST["birthdate"]);
    $gender = trim($_POST["gender"]);
    $credit = trim($_POST["credit"]);
    $phone = trim($_POST["phone"]);
    
    if ($surname && $name && $address && $birthdate && $gender && $credit && $phone) {
        addClient($filename, [$surname, $name, $address, $birthdate, $gender, $credit, $phone]);
        header("Location: " . $_SERVER["PHP_SELF"]);
        exit;
    } else {
        echo "<p class='error'>Будь ласка, заповніть всі поля!</p>";
    }
}

$clients = readClients($filename);

// Фільтрація за номером телефону
$searchDigits = $_GET['search'] ?? '';
$filteredClients = array_filter($clients, fn($client) => strpos($client[6], $searchDigits) !== false);

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
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Пошук клієнтів за номером телефону</h2>
        <form method="get">
            <input type="text" name="search" placeholder="Введіть цифри телефону" value="<?= htmlspecialchars($searchDigits) ?>">
            <button type="submit">Знайти</button>
        </form>
        
        <h2>Список клієнтів банку</h2>
        <table>
            <tr>
                <th>Прізвище</th>
                <th>Ім'я</th>
                <th>Адреса</th>
                <th>Дата народження</th>
                <th>Стать</th>
                <th>Сума кредиту (грн)</th>
                <th>Телефон</th>
            </tr>
            <?php foreach ($filteredClients as $client): ?>
                <tr>
                    <td><?= htmlspecialchars($client[0]) ?></td>
                    <td><?= htmlspecialchars($client[1]) ?></td>
                    <td><?= htmlspecialchars($client[2]) ?></td>
                    <td><?= htmlspecialchars($client[3]) ?></td>
                    <td><?= htmlspecialchars($client[4]) ?></td>
                    <td><?= htmlspecialchars($client[5]) ?></td>
                    <td><?= htmlspecialchars($client[6]) ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
        
        <h2>Додати клієнта</h2>
        <form method="post">
            <input type="text" name="surname" placeholder="Прізвище" required>
            <input type="text" name="name" placeholder="Ім'я" required>
            <input type="text" name="address" placeholder="Адреса" required>
            <input type="date" name="birthdate" required>
            <input type="text" name="gender" placeholder="Стать" required>
            <input type="number" name="credit" placeholder="Сума кредиту (грн)" required>
            <input type="text" name="phone" placeholder="Телефон" required>
            <button type="submit">Додати</button>
        </form>
    </div>
</body>
</html>