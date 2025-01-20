<?php
function connect_db() {
  
    $dbPath = 'C:/xampp/htdocs/Attendance-Tracker/db.sqlite'; 
    $db = new SQLite3($dbPath);
    if (!$db) {
        die('Database connection failed: ' . $db->lastErrorMsg());
    }
    return $db;
}
?>
