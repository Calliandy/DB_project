<html lang="zh-TW">
    <head>
        <meta charset="utf-8">
        <?php
            require_once("db.php");
        ?>
    </head>
    <body>
        <h1 class="topic">使用者登入</h1>
        
        <?php
            session_start();
            if($_SERVER["REQUEST_METHOD"]=="POST"){
                if(isset($_POST['loginBtn'])){
                    if(empty($_POST['userInputAccount'])||empty($_POST['userInputPassword'])){
                        echo "看來有人忘記輸入囉";
                    }else{
                        $account = $_POST['userInputAccount'];
                        $userPassword = $_POST['userInputPassword'];
                        $hashedPassword=password_hash($userPassword,PASSWORD_DEFAULT);
                        //執行登入相關操作
                        $sql="SELECT * from users WHERE account='$account'";
                        $result = mysqli_query($conn,$sql);
                        $rowResult=mysqli_fetch_assoc($result);
                        if(($result-> num_rows==1)&& (password_verify($userPassword,$rowResult['password']))){
                            //登入成功
                            $_SESSION['username']=$account;
                            header("Location: menu.php");
                            exit();
                        } else{
                            echo"登入失敗";
                        }
                    }
                } elseif(isset($_POST['signupBtn'])){
                    header("Location: register.php");
                    exit();
                }
            }
        ?>

        <form method="POST" action="">
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
                    <td><button type="submit" name="loginBtn">登入</button></td>
                    <td><button type="submit" name="signupBtn">註冊</button></td>
                </tr>
            </table>
        </form>

        <?php
            $conn->close();
        ?>
    </body>
</html>  