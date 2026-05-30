<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}
require_once('db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $request_id = $_POST['request_id'];
    $review = trim($_POST['review']);
    
    if (!empty($review)) {
        $stmt = $conn->prepare("INSERT INTO reviews (request_id, review) VALUES (?, ?)");
        $stmt->bind_param("is", $request_id, $review);
        $stmt->execute();
    }
}

header('Location: my_requests.php');
exit;
?>