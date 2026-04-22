<?php
define('DB_HOST', 'mysql.railway.internal');
define('DB_USER', 'root');
define('DB_PASS', 'zuBTVBjloIzjHfLeKkstVHMKUyRoopit');
define('DB_NAME', 'railway');

function getDB() {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if ($conn->connect_error) {
        http_response_code(500);
        die(json_encode(['error' => 'DB failed: ' . $conn->connect_error]));
    }
    $conn->set_charset('utf8mb4');
    return $conn;
}
?>
