<?php
    //建立資料庫連結
    $servername="localhost";
    $username = "root";
    $password = "wah";
    $dbname="Users";

    $conn=new mysqli($servername, $username , $password, $dbname);

    if($conn){
        echo"Connect! ";
    }else{
        echo "Unconnect!";
    }
?>