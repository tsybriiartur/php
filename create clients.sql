CREATE DATABASE clients_db;
USE clients_db;
CREATE TABLE clients (
    id INT AUTO_INCREMENT PRIMARY KEY,
    surname VARCHAR(100),
    name VARCHAR(100),
    address TEXT,
    birthdate DATE,
    gender VARCHAR(10),
    credit DECIMAL(10,2),
    phone VARCHAR(20)
);
