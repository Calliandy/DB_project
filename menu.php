<!DOCTYPE html>
<html lang="zh-TW">
    <head>
        <?php
            require_once "db.php";
        ?>
        <?php
            if (!isset($_SESSION['username'])) {
                echo "<script>alert('偵測到未登入'); window.location.href = 'login.php';</script>";
                exit(); 
            }
            session_start();
            echo "hi ," . $_SESSION['username'];
        ?>
        <meta charset="utf-8">
        <style>

        </style>
    </head>
    <body>
        <h1>選單</h1>
        <a href="goods.php">商品頁面</a>
        <a href="aboutme.php">個人資料</a>
        <a href="logout.php">登出</a>
    </body>
</html>