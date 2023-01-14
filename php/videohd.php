<?php
if(!empty($_GET["url"])){
  $url = $_GET["url"];
  $file_dir = $url; // 你的mp4文件地址
        ob_end_clean();
        ob_start();
        $handler    = fopen($file_dir, 'r+b');
        $file_size  = filesize($file_dir);
        //声明头信息，将二进制流信息输出来
        Header("Content-type: application/octet-stream");
        Header("Accept-Ranges: bytes");
        Header("Accept-Length: ".$file_size);
        Header("Content-Disposition: attachment; filename=" . basename( $file_dir));
        // 输出文件内容
        echo fread($handler,$file_size);
        fclose($handler);
        ob_end_flush();
        exit;
}
?>