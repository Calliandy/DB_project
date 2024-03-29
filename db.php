<?php
    //建立資料庫連結
    $servername="localhost";
    $username = "root";
    $password = "wah";
    $dbname="Users";

    $conn=new mysqli($servername, $username , $password, $dbname);

    if($conn->connect_error){
        die("資料庫連接失敗: ".$conn->connect_error);
    }
?>