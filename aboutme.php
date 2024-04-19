<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="utf-8">
    <?php
        require_once("db.php");
    ?>
    <?php
        session_start();
        echo "hi ," . $_SESSION['username'];
    ?>
    </head>
    <body>
        <h1>編輯使用者資料</h1>
        <?php
            if (!isset($_SESSION['username'])) {
                echo "<script>alert('偵測到未登入'); window.location.href = 'login.php';</script>";
                exit(); 
            }
            $username=$_SESSION['username'];
            $sql="SELECT * from users WHERE username='$username'";
            $result = mysqli_query($conn,$sql);
            $user=mysqli_fetch_assoc($result);
            $account=$user['account'];
            echo "使用者名稱:   ". $username ."   <button>";
            echo "帳號     :   " . $account ."<br>";
            echo ""
        ?>
    </body>
</html>