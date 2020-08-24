<?php

function draw(){
    echo mb_convert_encoding("https://apktrending.com/%E3%82%AC%E3%83%BC%E3%83%AB%E3%83%95%E3%83%AC%E3%83%B3%E3%83%89ar/jp.co.cyberagent.gf.ar", 'UTF-8', array('EUC-JP', 'SHIFT-JIS', 'AUTO'));
}
draw();
?>
