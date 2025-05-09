<?php
require_once 'db.php'; // Підключення до бази даних

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
        // Додавання клієнта в таблицю clients
        $stmt = $pdo->prepare("INSERT INTO clients (surname, name, address, birthdate, gender, credit) 
                               VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$surname, $name, $address, $birthdate, $gender, $credit]);
        $clientId = $pdo->lastInsertId(); // Отримуємо id нового клієнта

        // Додавання телефону в таблицю phones
        $stmt = $pdo->prepare("INSERT INTO phones (client_id, phone) VALUES (?, ?)");
        $stmt->execute([$clientId, $phone]);

        header("Location: " . $_SERVER["PHP_SELF"]);
        exit;
    } else {
        echo "<p class='error'>Будь ласка, заповніть всі поля!</p>";
    }
}

// Пошук за номером телефону
$searchDigits = $_GET['search'] ?? '';
$query = "SELECT clients.*, phones.phone FROM clients 
          JOIN phones ON clients.id = phones.client_id 
          WHERE phones.phone LIKE ?";
$stmt = $pdo->prepare($query);
$stmt->execute(["%$searchDigits%"]);
$clients = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
            <?php foreach ($clients as $client): ?>
                <tr>
                    <td><?= htmlspecialchars($client['surname']) ?></td>
                    <td><?= htmlspecialchars($client['name']) ?></td>
                    <td><?= htmlspecialchars($client['address']) ?></td>
                    <td><?= htmlspecialchars($client['birthdate']) ?></td>
                    <td><?= htmlspecialchars($client['gender']) ?></td>
                    <td><?= htmlspecialchars($client['credit']) ?></td>
                    <td><?= htmlspecialchars($client['phone']) ?></td>
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
