<?php
include_once 'simple_html_dom.php';

// $servername = "localhost";
// $username = "root";
// $password = "12345";
// $dbname = "tovi";

// $conn = new mysqli($servername, $username, $password, $dbname);

// if ($conn->connect_error) {
// die("Connection failed: " . $conn->connect_error);
// } else {
// echo "Connection success" . PHP_EOL;
// }
function crawl($conn)
{
    $result = $conn->query("select id, url from url where status = 0");
//     $resultUrl = mysqli_fetch_all($result);
    $urlArr = array();
    $idArr = array();
    while ($row = mysqli_fetch_assoc($result)) {
       $urlArr[] = $row["url"];
       $idArr[] = $row["id"];
    }
//     for ($i = 0; $i < sizeof($resultUrl); $i ++) {
//         array_push($urlArr, $resultUrl[$i][0]);
//     }
    while ($urlArr != NULL) {
        // $file = fopen('D:\MySQL\mysql-8.0.20-winx64\mysql-8.0.20-winx64\data\tovi\data.txt', "a+");
        for ($i = 0; $i < sizeof($urlArr); $i++) {
            $urlLink = $urlArr[$i];
            $id = $idArr[$i];
            echo $urlLink . PHP_EOL;
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/84.0.4147.105 Safari/537.36 Edg/84.0.522.52',
                CURLOPT_URL => "$urlLink"
            ));
            curl_setopt($curl, CURLOPT_HEADER, true);
            $html = curl_exec($curl);
            curl_close($curl);
            $doc = str_get_html($html);
            if ($doc != NULL) {

                foreach ($doc->find("a") as $element) {
                    $link = $element->href;
                    $linkPath = parse_url($link, PHP_URL_PATH);
                    $linkScheme = parse_url($link, PHP_URL_SCHEME);
                    $linkHost = parse_url($link, PHP_URL_HOST);
                    if ($linkHost == NULL && $linkScheme == NULL) {
                        $linkHost = "apktrending.com";
                        $linkScheme = "https";
                    }

                    if (substr_count($linkPath, "/") > 0 && substr_count($linkPath, "/") < 3 && strcmp($linkHost, "apktrending.com") == 0) {
                        if (strpos($linkPath, "dev") != FALSE || substr_count($linkPath, "/") < 2 || strpos($linkPath, ".") == FALSE) {
                            $isList = 1;
                            $status = 0;
                            $urlLink1 = $linkScheme . "://" . $linkHost . $linkPath;
                            // $dataStr = $urlLink1 . "\t" . $isList . "\t" . $status . "\n";
                            // fwrite($file, $dataStr);
                            $stm = $conn->prepare("insert ignore into url (url, isList, status, pageCrawl) values (?, ?, ?, ?)");
                            $stm->bind_param("siii", $urlLink1, $isList, $status, $id);
                            $stm->execute();
                            $stm->close();
                            echo "Inserted isList links into file" . PHP_EOL;
                        } else {
                            $isList = 0;
                            $status = 0;
                            $urlLink1 = $linkScheme . "://" . $linkHost . $linkPath;
                            // fwrite($file, $dataStr);
                            $stm = $conn->prepare("insert ignore into url (url, isList, status, pageCrawl) values (?, ?, ?, ?)");
                            $stm->bind_param("siii", $urlLink1, $isList, $status, $id);
                            $stm->execute();
                            $stm->close();
                            echo "Inserted app links into file" . PHP_EOL;
                        }
                    }
                }
                if ($conn->query("update url set status =1 where id =" . $id) === TRUE) {
                    echo "Updated successfully" . PHP_EOL;
                } else {
                    echo $conn->error . PHP_EOL;
                }
            } else {
                echo "Khong tim thay trang!" . PHP_EOL;
            }
            // if($conn->query("Load data infile'data.txt' ignore into table url(url, isList, status)")===FALSE){
            // echo $conn->error;
        }
        $result = $conn->query("select id, url from url where status =0");
//         $resultUrl = mysqli_fetch_all($result);
        $urlLinkArr = array();
        $idLinkArr = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $urlLinkArr[] = $row["url"];
            $idLinkArr[] = $row["id"];
        }
//         for ($i = 0; $i < sizeof($resultUrl); $i ++) {
//             array_push($urlLinkArr, $resultUrl[$i][0]);
//         }
        array_splice($urlArr, 0, sizeof($urlArr) - 1, $urlLinkArr);
        array_splice($idArr, 0, sizeof($idArr) - 1, $idLinkArr);
    }
}
// $conn->exec("Load data infile'E:\TOVI\WorkSpace\PHP\crawlURL\fileUrl.txt' ignore into table url(url, isList)");

?>