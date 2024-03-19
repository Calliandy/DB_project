<!DOCTYPE html>
<html lang="zh-TW">
    <head>
        <meta charset="utf-8">
        <?php
            //建立資料庫連結
            $servername="localhost";
            $username = "root";
            $password = "wah";
            $dbname="WAH";

            $conn=new mysqli($servername, $username , $password, $dbname);

            if($conn->connect_error){
                die("資料庫連接失敗: ".$conn->connect_error);
            }

            if($_SERVER["REQUEST_METHOD"]=="POST"){
                //if(isset($_POST['registerBtn'])){
                    if(empty($_POST['userInputAccount'])||empty($_POST['userInputPassword'])){
                        echo "<script>alert('還敢忘記輸入阿');</script>";
                    }else{
                        $account = $_POST['userInputAccount'];
                        $userPassword = $_POST['userInputPassword'];

                        $sql="SELECT * FROM users WHERE account = '$account'";
                        $result = $conn ->query($sql);
                        if($result -> num_rows > 0){
                            echo "此使用者ID已有人使用";
                        }else{
                            $sql = "INSERT INTO users (account,password) VALUES ('$account','$userPassword')";
                            if($conn->query($sql)===TRUE){
                                header("Location: login.php");
                                exit();
                            } else {
                                echo "註冊失敗: ".$conn->error;
                            }
                        }
                    }
                //}
            }
            $conn->close();
        ?>
    </head>
    <body>
        <h1>註冊頁面</h1>
        <form method="POST" action="register.php">
            <table>
                <tr>
                    <td>使用者ID:</td>
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