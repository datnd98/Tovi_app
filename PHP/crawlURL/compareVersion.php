<?php
include_once 'simple_html_dom.php';

function get_headers_from_curl_response($response)
{
    $headers = array();

    foreach (explode("\r\n", $response) as $i => $line)
        if ($i === 0)
            $headers['http_code'] = $line;
        else {
            list ($key, $value) = explode(': ', $line);

            $headers[$key] = $value;
        }

    return $headers;
}

function crawlEnemy($conn)
{
    date_default_timezone_set('asia/ho_chi_minh');
    $urlApkmonk = "https://www.apkmonk.com/app/";
    $urlApkpure = "https://apkpure.com/"; // random String
    $urlApkcombo = "https://apkcombo.com/app/"; // random String
    $urlApksupport = "https://apk.support/app/";
    $urlChplay = "https://play.google.com/store/apps/details?id=";

    $tmpUrl = array();
    $tmpId = array();
    $sql = "SELECT id, url FROM url WHERE isList = 0 ORDER BY RAND() LIMIT 10";
    $resultUrl = $conn->query($sql);
//     $row = mysqli_fetch_all($resultUrl);
    while ($row = mysqli_fetch_assoc($resultUrl)) {
        $tmpUrl[] = $row["url"];
        $tmpId[] = $row["id"];
    }
    // for ($i = 0; $i < sizeof($tmp); $i ++) {
    // $tmp1 = explode("/", $tmp[$i]);
    // array_push($appids, $tmp1[4]);
    // }

    for($i = 0; $i < sizeof($tmpUrl); $i++) {
        $id = $tmpId[$i];
        $urlTrending = $tmpUrl[$i];
        $tmpUrl1 = explode("/", $urlTrending);
        $appid = $tmpUrl1[4];
        $appStr = $tmpUrl1[3];
        echo $appid . PHP_EOL;

            // crawl apktrending
        print_r($urlTrending);
        echo PHP_EOL;
        $curlTrending2 = curl_init();
        curl_setopt_array($curlTrending2, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_URL => $urlTrending,
            CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/84.0.4147.105 Safari/537.36 Edg/84.0.522.52',
            CURLOPT_HEADER => TRUE
        ));
        $htmlTrending = curl_exec($curlTrending2);
        $headerTrending2_size = curl_getinfo($curlTrending2, CURLINFO_HEADER_SIZE);
        $headerTrending2 = substr($htmlTrending, 0, $headerTrending2_size);
        $resTime = curl_getinfo($curlTrending2, CURLINFO_TOTAL_TIME);
        curl_close($curlTrending2);
        $header = get_headers_from_curl_response($headerTrending2);
        $http_code = intval(explode(" ", $header["http_code"])[1]);
        var_dump($http_code);
        $cache_status = $header["CF-Cache-Status"];
        var_dump($cache_status);
        $cf_ray = explode("-", $header["CF-RAY"])[1];
        var_dump($cf_ray);
        var_dump($resTime);
        if ($http_code >= 400) {
            echo "loi 404" . PHP_EOL;
            $versionApktrending = "0";
        } else {
            $docTrending = str_get_html($htmlTrending);
            $title = $docTrending->find("title", 0)->innertext;
            var_dump($title);
            $plain_text = $docTrending->find("table[class=table table-responsive border rounded mb-1] td.pl-4", 1)->plaintext;
            $versionApktrending = explode(" (", $plain_text)[0];
            $dateTrendingStr = $docTrending->find("span strong", 1)->plaintext;
            $dateTrending = strtotime($dateTrendingStr);
            print_r($versionApktrending);
            echo PHP_EOL;
        }

        // crawl apkpure
        $urlPure = $urlApkpure . $appStr . "/" . $appid;
        echo $urlPure . PHP_EOL;
        $curlPure1 = curl_init();
        curl_setopt_array($curlPure1, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_URL => $urlPure,
            CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/84.0.4147.105 Safari/537.36 Edg/84.0.522.52'
        ));
        $htmlPure = curl_exec($curlPure1);
        $statusCodePure = curl_getinfo($curlPure1, CURLINFO_HTTP_CODE);
        curl_close($curlPure1);
        if ($statusCodePure >= 400) {
            echo "loi 404" . PHP_EOL;
            $comparePure = "0";
        } else {
            $docPure = str_get_html($htmlPure);
            $versionApkpure = trim($docPure->find('span[itemprop=version]', 0)->plaintext);
            print_r($versionApkpure);
            echo PHP_EOL;
            $datePureStr = $docPure->find("div.additional p[itemprop=datePublished]", 0)->plaintext;
            $datePure = strtotime($datePureStr);
            if ($versionApktrending != $versionApkpure) {
                if ($datePure > $dateTrending) {
                    $comparePure = "-1";
                } elseif ($datePure < $dateTrending) {
                    $comparePure = "2";
                }
            } else {
                $comparePure = "1";
            }
        }

        // crawl apkcombo
        $linkUrlApkcombo = $urlApkcombo . $appid;
        $curlCombo1 = curl_init();
        curl_setopt_array($curlCombo1, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_URL => $linkUrlApkcombo,
            CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/84.0.4147.105 Safari/537.36 Edg/84.0.522.52',
            CURLOPT_HEADER => true
        ));
        $htmlCombo1 = curl_exec($curlCombo1);
        $headerCombo1_size = curl_getinfo($curlCombo1, CURLINFO_HEADER_SIZE);
        $headerCombo1 = substr($htmlCombo1, 0, $headerCombo1_size);
        curl_close($curlCombo1);
        $pattern = '/(location: )(.+)(\n)/';
        $resultCombo = "";
        preg_match($pattern, $headerCombo1, $resultCombo);

        $urlCombo = "https://apkcombo.com" . trim($resultCombo[2]);
        print_r($urlCombo);
        echo PHP_EOL;
        $curlCombo2 = curl_init();
        curl_setopt_array($curlCombo2, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_URL => $urlCombo,
            CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/84.0.4147.105 Safari/537.36 Edg/84.0.522.52'
        ));
        $htmlCombo = curl_exec($curlCombo2);
        $statusCodeCombo = curl_getinfo($curlCombo2, CURLINFO_HTTP_CODE);
        curl_close($curlCombo2);
        if ($statusCodeCombo >= 400) {
            echo "loi 404" . PHP_EOL;
            $compareCombo = "0";
        } else {
            $docCombo = str_get_html($htmlCombo);
            $versionApkcombo = trim($docCombo->find("a.is-link", 1)->plaintext);
            print_r($versionApkcombo);
            echo PHP_EOL;
            $dateComboStr = $docCombo->find("meta[itemprop=datePublished]", 0)->content;
            $dateCombo = strtotime($dateComboStr);
            if ($versionApktrending != $versionApkcombo) {
                if ($dateCombo < $dateTrending) {
                    $compareCombo = "2";
                } elseif ($dateCombo > $dateTrending) {
                    $compareCombo = "-1";
                }
            } else {
                $compareCombo = "1";
            }
        }

        // crawl apkmonk
        $urlMonk = $urlApkmonk . $appid . "/";
        print_r($urlMonk);
        echo PHP_EOL;
        $curlMonk = curl_init();
        curl_setopt_array($curlMonk, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_URL => $urlMonk,
            CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/84.0.4147.105 Safari/537.36 Edg/84.0.522.52'
        ));
        $htmlMonk = curl_exec($curlMonk);
        $statusCodeMonk = curl_getinfo($curlMonk, CURLINFO_HTTP_CODE);
        curl_close($curlMonk);
        print_r($statusCodeMonk);
        echo PHP_EOL;
        if ($statusCodeMonk >= 400) {
            echo "loi 404" . PHP_EOL;
            $compareMonk = "0";
        } else {
            $docMonk = str_get_html($htmlMonk);
            $versionApkmonk = trim($docMonk->find("table span", 0)->plaintext);
            print_r($versionApkmonk);
            echo PHP_EOL;
            $dateMonkStr = $docMonk->find("table span", 1)->plaintext;
            $dateMonk = strtotime($dateMonkStr);
            if ($versionApktrending != $versionApkmonk) {
                if ($dateMonk < $dateTrending) {
                    $compareMonk = "2";
                } elseif ($dateMonk > $dateTrending) {
                    $compareMonk = "-1";
                }
            } else {
                $compareMonk = "1";
            }
        }

        // crawl apksupport
        $urlSupport = $urlApksupport . $appid;
        print_r($urlSupport);
        echo PHP_EOL;
        $curlSupport = curl_init();
        curl_setopt_array($curlSupport, array(
            CURLOPT_URL => "https://googleplay.androidcontents.com/_apk.support_/z.php",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "google_id=" . $appid . "&x=downapk&device_id=&av_u=0&model=&hl=en&de_av=&aav=4.4&android_ver=0&tbi=0",
            CURLOPT_HTTPHEADER => array(
                "authority: googleplay.androidcontents.com",
                "user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/84.0.4147.105 Safari/537.36",
                "content-type: application/x-www-form-urlencoded",
                "accept: */*",
                "origin: https://apk.support",
                "sec-fetch-site: cross-site",
                "sec-fetch-mode: cors",
                "sec-fetch-dest: empty",
                "referer: https://apk.support/download-app/com.clindcompany.clind.fabulosofred",
                "accept-language: vi,en-US;q=0.9,en;q=0.8",
                "Cookie: __cfduid=d6cfa5414d41c80ac1d898590076aa8c31596614096"
            )
        ));
        $htmlSupport = curl_exec($curlSupport);
        echo $htmlSupport . PHP_EOL;
        $statusCodeSupport = curl_getinfo($curlSupport, CURLINFO_HTTP_CODE);
        curl_close($curlSupport);
        $startV = strpos($htmlSupport, "Version ");
        $endV = strpos($htmlSupport, "/...", $startV);
        $versionApkSupport = substr($htmlSupport, $startV + 8, $endV - $startV - 8);
        print_r($versionApkSupport);
        echo PHP_EOL;

        $startD = strpos($htmlSupport, 'uploadDate":</span> "');
        if ($startD == true) {
            $endD = strpos($htmlSupport, '",</li>', $startD);
            $time = substr($htmlSupport, $startD + 21, $endD - $startD - 21);
        } else {
            $startD = strpos($htmlSupport, 'Updated: <b>');
            $endD = strpos($htmlSupport, '</b></li>', $startD);
            $time = substr($htmlSupport, $startD + 12, $endD - $startD - 12);
        }
        $dateSupport = strtotime($time);
        print_r($dateSupport);die;
        echo PHP_EOL;
        if ($statusCodeSupport >= 400) {
            echo "loi 404" . PHP_EOL;
            $compareSupport = "0";
        } else {
            // $docSupport = str_get_html($htmlSupport);
            // $versionApkSupport = trim($docSupport->find("span.vers", 0)->plaintext);
            // print_r($versionApkSupport);
            // echo PHP_EOL;
            // $dateSupportStr = $docSupport->find("div.napkinfo span.htlgb", 0)->plaintext;
            // $dateSupport = strtotime($dateSupportStr);
            if ($versionApktrending != $versionApkSupport) {
                if ($dateSupport < $dateTrending) {
                    $compareSupport = "2";
                } elseif ($dateSupport > $dateTrending) {
                    $compareSupport = "-1";
                }
            } else {
                $compareSupport = "1";
            }
        }

        // crawl chplay
        $urlCh = $urlChplay . $appid . "&hl=en";
        print_r($urlCh);
        echo PHP_EOL;
        $curlCh = curl_init();
        curl_setopt_array($curlCh, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_URL => $urlCh,
            CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/84.0.4147.105 Safari/537.36 Edg/84.0.522.52'
        ));
        $htmlCh = curl_exec($curlCh);
        $statusCodeCh = curl_getinfo($curlCh, CURLINFO_HTTP_CODE);
        curl_close($curlCh);
        if ($statusCodeCh >= 400) {
            echo "loi 404" . PHP_EOL;
            $compareCh = "0";
        } else {
            $docCh = strval($htmlCh);
            $regex1 = "/>AF_initDataCallback\({key: \'ds:5\'(.*?)\}\);<\/script>/s";
            $str1 = "";
            preg_match($regex1, $docCh, $str1);
            if (count($str1) > 0) {
                $arr = explode('data:', $str1[1])[1];
            }
            $str1 = json_decode($arr, TRUE);
            $dateCh = $str1[0][12][8][0];

            $regex2 = "/>AF_initDataCallback\({key: \'ds:8\'(.*?)\}\);<\/script>/s";
            $str2 = "";
            preg_match($regex2, $docCh, $str2);
            if (count($str2) > 0) {
                $arr = explode('data:', $str2[1])[1];
            }
            $str2 = json_decode($arr, TRUE);
            $versionCh = $str2[1];
            print_r($versionCh);
            echo PHP_EOL;
            if (strpos($versionCh, "device") != FALSE) {
                $compareCh = "varies";
            } else {
                if ($versionCh != $versionApktrending) {
                    if ($dateCh < $dateTrending) {
                        $compareCh = "2";
                    } elseif ($dateCh > $dateTrending) {
                        $compareCh = "-1";
                    }
                } else {
                    $compareCh = "1";
                }
            }
        }

        $time = strval(date("Y-m-d H:i:s"));
        print_r($time);
        echo PHP_EOL;
        echo PHP_EOL;
        $stm = $conn->prepare("insert ignore into versioncompare (appid, urlID, apktrending, apkpure, apkmonk, apkcombo, apksupport, chplay, dateCheck, statusCode, cacheStatus, cfRay, title, resTime) values (?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
        $stm->bind_param("sisssssssisssd", $appid, $id, $versionApktrending, $comparePure, $compareMonk, $compareCombo, $compareSupport, $compareCh, $time, $http_code, $cache_status, $cf_ray, $title, $resTime);
        $stm->execute();
        $stm->close();
        
    }
}
?>