<?php
session_start();
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $studentName = trim($_POST['studentName']);
    $studentId = (int)$_POST['studentId'];


    $db = connect_db();
    $stmt = $db->prepare("SELECT name FROM students WHERE id = ? AND name = ?");
    $stmt->bindValue(1, $studentId, SQLITE3_INTEGER);
    $stmt->bindValue(2, $studentName, SQLITE3_TEXT);

    $result = $stmt->execute();

    if ($result->fetchArray()) {
        echo 'success';
    } else {
        echo 'error';
    }
}
?>
