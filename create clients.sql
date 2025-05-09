CREATE DATABASE clients_db;

USE clients_db;

-- Створення таблиці clients
CREATE TABLE clients (
    id INT AUTO_INCREMENT PRIMARY KEY,
    surname VARCHAR(255) NOT NULL,
    name VARCHAR(255) NOT NULL,
    address VARCHAR(255) NOT NULL,
    birthdate DATE NOT NULL,
    gender VARCHAR(10) NOT NULL,
    credit DECIMAL(10, 2) NOT NULL
);

-- Створення таблиці phones для збереження телефонів клієнтів
CREATE TABLE phones (
    id INT AUTO_INCREMENT PRIMARY KEY,
    client_id INT NOT NULL,
    phone VARCHAR(20) NOT NULL,
    FOREIGN KEY (client_id) REFERENCES clients(id) ON DELETE CASCADE
);
