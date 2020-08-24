<?php
include_once ('simple_html_dom.php');
include_once ('insert.php');
// include_once ('getdateupload.php');

function getapksupport()
{
    $servername = "188.166.231.109";
    $database = "tovi";
    $username = "root";
    $password = "tovi";

    $conn = mysqli_connect($servername, $username, $password, $database);
    // Check connection
    if (! $conn) {
        die("Connection failed: " . mysqli_connect_error() . PHP_EOL);
    }
    echo "Connected successfully" . PHP_EOL;

    $sql = "SELECT appid FROM list_app LIMIT 5000,200";
    $result = mysqli_query($conn, $sql);
    $listapp = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $listapp[] = $row["appid"];

        // var_dump($appid);
    }
    $conn->close();

    foreach ($listapp as $key => $appid) {
        try {

            echo "Linkappid : https://apk.support/download-app/" . $appid . PHP_EOL;

            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://googleplay.androidcontents.com/_covid19_/z.php",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => "google_id=" . $appid . "&x=downapk&device_id=&av_u=0&model=&hl=en&de_av=&aav=4.4&android_ver=0&tbi=0",
//                 CURLOPT_HTTPHEADER => array(
//                     "authority: googleplay.androidcontents.com",
//                     "user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/84.0.4147.105 Safari/537.36",
//                     "content-type: application/x-www-form-urlencoded",
//                     "accept: */*",
//                     "origin: https://apk.support",
//                     "sec-fetch-site: cross-site",
//                     "sec-fetch-mode: cors",
//                     "sec-fetch-dest: empty",
//                     "referer: https://apk.support/download-app/com.clindcompany.clind.fabulosofred",
//                     "accept-language: vi,en-US;q=0.9,en;q=0.8",
//                     "Cookie: __cfduid=d6cfa5414d41c80ac1d898590076aa8c31596614096"
//                 )
            ));

            $response = curl_exec($curl);
            echo $response;
            die;
            
            curl_close($curl);

            date_default_timezone_set('asia/ho_chi_minh');
            $startTime = date("Y-m-d h:i:sa");
            // print_r($aaaa);
            // die;

            $startv = strpos($response, 'Version ');
            $endv = strpos($response, ' /..', $startv);
            $version = substr($response, $startv + 8, $endv - $startv - 8);
            if ($version == "N/A") {
                $version = "NA";
            }
            // echo "Version == " . $version . PHP_EOL;

            $startd = strpos($response, 'uploadDate":</span> "');
            if ($startd == true) {
                $endd = strpos($response, '",</li>', $startd);
                $time = substr($response, $startd + 21, $endd - $startd - 21);
            } else {
                $startd = strpos($response, 'Updated: <b>');
                $endd = strpos($response, '</b></li>', $startd);
                $time = substr($response, $startd + 12, $endd - $startd - 12);
            }
            $dateUp = DateTime::createFromFormat('F d, Y', $time);
            if ($dateUp == true) {
                $uploadDate = $dateUp->format('Y-m-d');
            } else {
                $dateUp = DateTime::createFromFormat('F d,Y', $time);
                if ($dateUp == true) {
                    $uploadDate = $dateUp->format('Y-m-d');
                } else {
                    $curld = curl_init();
                    $linkurl = 'https://apk.support/app/' . $appid;
                    echo $linkurl . PHP_EOL;
                    curl_setopt_array($curld, array(
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => "",
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 30,
                        CURLOPT_FOLLOWLOCATION => true,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => "GET",
                        CURLOPT_URL => $linkurl,
                        CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/84.0.4147.89 Safari/537.36',
                        CURLOPT_SSL_VERIFYPEER => false
                    ));

                    $respd = curl_exec($curld);
                    $httpcode = curl_getinfo($curld, CURLINFO_HTTP_CODE);
                    print_r($httpcode) . PHP_EOL;

                    curl_close($curld);
                    $domd = str_get_html($respd);

                    $linkget = $domd->find('div[class="nboxinfo"]', 0);
                    $timedate = $linkget->find('span', 0)->plaintext;
                    $dateUp = DateTime::createFromFormat('F d, Y', $timedate);
                    $uploadDate = $dateUp->format('Y-m-d');
                    
                }
            }
            // echo "Date == " . $uploadDate . PHP_EOL;

            $startl = strpos($response, 'href="');
            $endl = strpos($response, '" onclick', $startl);
            $linkdownapk = substr($response, $startl + 6, $endl - $startl - 6);
            echo "LinkDownload : " . $linkdownapk . PHP_EOL;
            var_dump(strpos($linkdownapk, 'xinchaocacban'));
            die;
            if(strpos($linkdownapk, 'http')==TRUE){
                echo "dung";
                die;
                    
            }else{
                echo "sai";
                die;
                    
            }
            echo "LinkDownload : " . $linkdownapk . PHP_EOL;

            $des = "apk_cup/" . $appid . '.apk';
            $fp = fopen($des, 'wb');
            // echo $des;die;
            $ch = curl_init($linkdownapk);
            curl_setopt_array($ch, array(
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_FILE => $fp,
                CURLOPT_HTTPHEADER => array(
                    "Sec-Fetch-Dest:  document",
                    "Upgrade-Insecure-Requests:  1",
                    "User-Agent:  Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/84.0.4147.89 Safari/537.36"
                )
            ));
            // curl_setopt($ch, CURLOPT_FILE, $fp);

            curl_exec($ch);
            $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            fclose($fp);
            if ($statusCode == 200) {
                echo "***********************************DOWNLOAD SUCCES***********************************";
                echo PHP_EOL;
            } else {
                echo "+++++++++++++++++++++++++++++++++++DOWNLOAD FAILED+++++++++++++++++++++++++++++++++++";
                echo PHP_EOL;
            }

            $data = filesize($des);
            $sha1sum = sha1_file($des);

            $filedata = '/var/lib/mysql/tovi/datasupport.txt';

            $infor = $appid . "\t" . $des . "\t" . $uploadDate . "\t" . $data . "\t" . $version . "\t" . $sha1sum . "\t" . $startTime . "\n";
            $fileinsert = fopen($filedata, "a+");
            fwrite($fileinsert, $infor);
            fclose($fileinsert);

            $monk = $appid . "\t" . $linkdownapk . "\n";
            $fileapk = "apklink.txt";
            $filelinkdown = fopen($fileapk, "a+");
            fwrite($filelinkdown, $monk);
            fclose($filelinkdown);
            echo "Wrote file!!!!!" . PHP_EOL;
        } catch (Exception $e) {
            $loi = fopen("loi.txt", "a") or die("Unable to open file!");
            fwrite($loi, $e->getMessage . "\n");
        }
    }
    insert();
}

getapksupport();

?>