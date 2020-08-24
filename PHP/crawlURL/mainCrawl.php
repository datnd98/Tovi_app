<?php
include 'crawlUrl.php';

$servername = "localhost";
$username = "root";
$password = "12345";
$dbname = "tovi";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else {
    echo "Connection success" . PHP_EOL;
        crawl($conn);
//     crawlEnemy($conn);
    $conn->close();
}

?>