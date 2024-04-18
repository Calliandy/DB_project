<!DOCTYPE html>
<html lang="zh-TW">
    <head>
        <meta charset="utf-8">
        <?php
            require_once("db.php");
        ?>

        <?php
            if($_SERVER["REQUEST_METHOD"]=="POST"){
                if(isset($_POST['loginBtn'])){
                    header("Location: login.php");
                    exit();
                }
                if(isset($_POST['registerBtn'])){
                    if(empty($_POST['userInputAccount'])||empty($_POST['userInputPassword'])){
                        echo "<script>alert('請記得輸入您的註冊資訊');</script>";
                    }else{
                        $userID = $_POST['userInputID'];
                        $account = $_POST['userInputAccount'];
                        $userPassword = $_POST['userInputPassword'];

                        $sql="SELECT * FROM users WHERE account = '$account'";
                        $result = $conn ->query($sql);
                        if($result -> num_rows > 0){
                            echo "此使用者ID已有人使用";
                        }else{
                            $hashedPassword = password_hash($userPassword, PASSWORD_DEFAULT);
                            $sql = "INSERT INTO `users`(`account`, `password`, `role`) VALUES ('$account','$hashedPassword','user')";
                            if($conn->query($sql)===TRUE){
                                header("Location: login.php");
                                exit();
                            } else {
                                echo "註冊失敗: ".$conn->error;
                            }
                        }
                    }
                }
            }
            $conn->close();
        ?>
    </head>
    <body>
        <h1>註冊頁面</h1>
        <form method="POST" action="register.php">
            <table>
                <tr>
                    <td>帳號:</td>
                    <td><input type="text" maxlength="50" id="userInputAccount" name="userInputAccount"></td>
                </tr>
                <tr>
                    <td>密碼:</td>
                    <td><input type="password" maxlength="50" id="userInputPassword" name="userInputPassword"></td>
                </tr>
                <tr>
                    <td><button type="submit" name="registerBtn">註冊</button></td>
                    <button type="submit" name="loginBtn">登入頁面</button>
                </tr>
            </table>
        </form>
    </body>
</html>  