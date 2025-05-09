<?php
class Client {
    public $id;
    public $surname;
    public $name;
    public $address;
    public $birthdate;
    public $gender;
    public $credit;
    public $phone;

    public function __construct($surname, $name, $address, $birthdate, $gender, $credit, $phone, $id = null) {
        $this->surname = $surname;
        $this->name = $name;
        $this->address = $address;
        $this->birthdate = $birthdate;
        $this->gender = $gender;
        $this->credit = $credit;
        $this->phone = $phone;
        $this->id = $id;
    }

    public function save() {
        global $pdo;

        if ($this->id === null) {
            // Якщо клієнт ще не існує в базі, додаємо його
            $stmt = $pdo->prepare("INSERT INTO clients (surname, name, address, birthdate, gender, credit) 
                                   VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$this->surname, $this->name, $this->address, $this->birthdate, $this->gender, $this->credit]);
            $this->id = $pdo->lastInsertId(); // Отримуємо id нового клієнта
        }

        // Додаємо телефон клієнта в таблицю phones
        $stmt = $pdo->prepare("INSERT INTO phones (client_id, phone) VALUES (?, ?)");
        $stmt->execute([$this->id, $this->phone]);
    }

    public static function getById($id) {
        global $pdo;

        $stmt = $pdo->prepare("SELECT * FROM clients WHERE id = ?");
        $stmt->execute([$id]);
        $clientData = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($clientData) {
            $stmt = $pdo->prepare("SELECT phone FROM phones WHERE client_id = ?");
            $stmt->execute([$id]);
            $phones = $stmt->fetchAll(PDO::FETCH_COLUMN);

            $client = new Client(
                $clientData['surname'],
                $clientData['name'],
                $clientData['address'],
                $clientData['birthdate'],
                $clientData['gender'],
                $clientData['credit'],
                implode(", ", $phones),
                $clientData['id']
            );

            return $client;
        }

        return null;
    }

    

    public static function getAll() {
        global $pdo;

        $stmt = $pdo->query("SELECT * FROM clients");
        $clientsData = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $clients = [];
        foreach ($clientsData as $clientData) {
            $stmt = $pdo->prepare("SELECT phone FROM phones WHERE client_id = ?");
            $stmt->execute([$clientData['id']]);
            $phones = $stmt->fetchAll(PDO::FETCH_COLUMN);

            $clients[] = new Client(
                $clientData['surname'],
                $clientData['name'],
                $clientData['address'],
                $clientData['birthdate'],
                $clientData['gender'],
                $clientData['credit'],
                implode(", ", $phones),
                $clientData['id']
            );
        }

        return $clients;
    }

    public static function getByBirthdate($birthdate) {
        global $pdo;

        $stmt = $pdo->prepare("SELECT * FROM clients WHERE birthdate = ?");
        $stmt->execute([$birthdate]);
        $clientsData = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $clients = [];
        foreach ($clientsData as $clientData) {
            $stmt = $pdo->prepare("SELECT phone FROM phones WHERE client_id = ?");
            $stmt->execute([$clientData['id']]);
            $phones = $stmt->fetchAll(PDO::FETCH_COLUMN);

            $clients[] = new Client(
                $clientData['surname'],
                $clientData['name'],
                $clientData['address'],
                $clientData['birthdate'],
                $clientData['gender'],
                $clientData['credit'],
                implode(", ", $phones),
                $clientData['id']
            );
        }

        return $clients;
    }

    

    public function __toString() {
        return "Прізвище: $this->surname, Ім'я: $this->name, Адреса: $this->address, 
                Дата народження: $this->birthdate, Стать: $this->gender, 
                Сума кредиту: $this->credit, Телефон: $this->phone";
    }
}
?>
