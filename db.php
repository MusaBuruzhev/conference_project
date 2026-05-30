<?php
$host = 'localhost';
$user = 'root';
$pass = 'root';  
$dbname = 'conference_db';

$conn = new mysqli($host, $user, $pass);

if ($conn->connect_error) {
    die("Ошибка подключения: " . $conn->connect_error);
}

$conn->query("CREATE DATABASE IF NOT EXISTS $dbname");
$conn->select_db($dbname);


$conn->query("CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    fio VARCHAR(255) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    email VARCHAR(100) NOT NULL,
    login VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
)");


$conn->query("CREATE TABLE IF NOT EXISTS requests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    room ENUM('auditorium', 'coworking', 'cinema') NOT NULL,
    date DATE NOT NULL,
    status ENUM('Новая', 'Мероприятие назначено', 'Мероприятие завершено') DEFAULT 'Новая',
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
)");


$conn->query("CREATE TABLE IF NOT EXISTS reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    request_id INT NOT NULL,
    review TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (request_id) REFERENCES requests(id) ON DELETE CASCADE
)");

echo "База данных и таблицы созданы успешно!";
?>