<?php
// Check if constants are not defined before defining them
if (!defined('DB_HOSTNAME')) {
    define('DB_HOSTNAME', 'localhost');
}

if (!defined('DB_USERNAME')) {
    define('DB_USERNAME', 'root');
}

if (!defined('DB_PASSWORD')) {
    define('DB_PASSWORD', '');
}

if (!defined('DB_DATABASE')) {
    define('DB_DATABASE', 'db_graphicspos');
}

// Establish database connection
$conn = new mysqli(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
