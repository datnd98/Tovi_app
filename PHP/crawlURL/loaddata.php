<?php
$servername = "";
$username = "root";
$password = "tovi123@";
$dbname = "tovi";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else {
    echo "Connection success" . PHP_EOL;
    $tmpUrl = array();
    $tmpId = array();
    $sql = "SELECT id, url FROM url WHERE type = 'GA'";
    $resultUrl = $conn->query($sql);
    // $row = mysqli_fetch_all($resultUrl);
    while ($row = mysqli_fetch_assoc($resultUrl)) {
        $tmpUrl[] = $row["url"];
        $tmpId[] = $row["id"];
    }

    for ($i = 0; $i < sizeof($tmpUrl); $i ++) {
        $id = $tmpId[$i];
        $url = $tmpUrl[$i];
        $tmpUrl1 = explode("/", $url);
        if (sizeof($tmpUrl1) == 5) {
            if ($tmpUrl1[3] == "dev") {
                $conn->query("update url set isList = 1 where id =" . $id);
            }
        } else {
            $conn->query("update url set isList = 1 where id =" . $id);
        }
    }
}
?>