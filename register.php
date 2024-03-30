<!DOCTYPE html>
<html lang="zh-TW">
    <head>
        <meta charset="utf-8">
        <?php
            require_once("db.php");
        ?>
        <?php
            if($_SERVER["REQUEST_METHOD"]=="POST"){
                if(isset($_POST['registerBtn'])){
                    if(empty($_POST['userInputAccount'])||empty($_POST['userInputPassword'])||empty($_POST['userInputID'])){
                        echo "<script>alert('還敢忘記輸入阿');</script>";
                    }else{
                        $userID = $_POST['userInputID'];
                        $account = $_POST['userInputAccount'];
                        $userPassword = $_POST['userInputPassword'];

                        $sql="SELECT * FROM users WHERE user_ID = '$userID'";
                        $result = $conn ->query($sql);
                        if($result -> num_rows > 0){
                            echo "此使用者ID已有人使用";
                        }else{
                            $sql = "INSERT INTO `users`(`user_ID`, `account`, `password`, `role`) VALUES ('$userID','$account','$userPassword','user')";
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
                    <td>使用者名稱:</td>
                    <td><input type="text" maxlength="50" id="userInputID" name="userInputID"></td>
                </tr>
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
                </tr>
            </table>
        </form>
    </body>
</html>  