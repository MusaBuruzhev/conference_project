<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}
require_once('db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $request_id = intval($_POST['request_id']);
    $review = trim($_POST['review']);
    
    if (!empty($review)) {
        $stmt = $conn->prepare("SELECT requests.user_id, requests.status, reviews.id AS review_id FROM requests LEFT JOIN reviews ON requests.id = reviews.request_id WHERE requests.id = ?");
        $stmt->bind_param("i", $request_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        if ($row && $row['user_id'] === $_SESSION['user_id'] && $row['status'] === 'Мероприятие завершено' && empty($row['review_id'])) {
            $stmt = $conn->prepare("INSERT INTO reviews (request_id, review) VALUES (?, ?)");
            $stmt->bind_param("is", $request_id, $review);
            $stmt->execute();
        }
    }
}

header('Location: my_requests.php');
exit;
?>