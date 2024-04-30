<!DOCTYPE html>
<html>

<head>
    <!-- Basic -->
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <!-- Mobile Metas -->
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <!-- Site Metas -->
    <meta name="keywords" content="" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <link rel="shortcut icon" href="images/favicon.png" type="">

    <title> 丹尼斯的煉油廠 </title>

    <!-- bootstrap core css -->
    <link rel="stylesheet" type="text/css" href="css/bootstrap.css" />

    <!-- fonts style -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700;900&display=swap" rel="stylesheet">

    <!--owl slider stylesheet -->
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" />

    <!-- font awesome style -->
    <link href="css/font-awesome.min.css" rel="stylesheet" />

    <!-- Custom styles for this template -->
    <link href="css/style.css" rel="stylesheet" />
    <!-- responsive style -->
    <link href="css/responsive.css" rel="stylesheet" />
    <?php
        include "db_connect.php";
        session_start();
    ?>
    <?php
        if (!isset($_SESSION['username'])) {
            echo "<script>alert('偵測到未登入'); window.location.href = 'login.php';</script>";
                    exit(); 
        }
        try {
            $stmt = $db->prepare("SELECT * FROM users WHERE userID = :userID");
            $stmt->bindParam(':userID', $_SESSION['userID']);
            $stmt->execute();
        
            if ($stmt->rowCount() > 0) {
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
                $role = $user['role'];
                $username = $user['username'];
                $account = $user['account'];
            } else {
                //echo "No user found with that username.";
            }
        } catch (PDOException $e) {
            die("Database error: " . $e->getMessage());
        }
        if (($_SERVER['REQUEST_METHOD'] === "POST")&&(isset($_POST['update']))){ //update stands for the field name
            $fieldToUpdate = $_POST['update'];
            $updateValue = $_POST[$fieldToUpdate]?? '';
            // echo "<script>alert('".$fieldToUpdate.$updateValue."');</script>";

            if ($fieldToUpdate === 'password') { //處理更改密碼需要加密的部分
                if (($_POST['password'] === $_POST['confirmPassword'])) {
                    $updateValue = password_hash($_POST['password'], PASSWORD_DEFAULT);
                    try { //更新資料庫
                        $stmt = $db->prepare("UPDATE users SET `$fieldToUpdate` = :updateValue WHERE userID = :userID");
                        $stmt->bindParam(':updateValue', $updateValue);
                        $stmt->bindParam(':userID', $_SESSION['userID']);
                        $stmt->execute();
                
                        if ($stmt->rowCount() > 0) {
                            echo "<script>alert('更新成功'); window.location.href = 'aboutme.php';</script>";
                        } else {
                            echo "<script>alert('無變更導致的未更新'); window.history.back();</script>";
                        }
                    } catch (PDOException $e) {
                        die("Database error during update: " . $e->getMessage());
                    }
                } else {
                    echo "<script>alert('密碼與確認密碼不相同'); window.history.back();</script>";
                    exit();
                }
            }
            if ($fieldToUpdate === 'account') { //處理更改密碼需要加密的部分
                $stmt = $db->prepare("SELECT * FROM users WHERE account = :account");
                $stmt->bindParam(':account', $_POST['account']);
                $stmt->execute();
                if($stmt -> rowCount()>0){
                    echo "<script>alert('帳號重複!'); window.history.back();</script>";  
                }else{
                    try { //更新資料庫
                        $stmt = $db->prepare("UPDATE users SET `$fieldToUpdate` = :updateValue WHERE userID = :userID");
                        $stmt->bindParam(':updateValue', $updateValue);
                        $stmt->bindParam(':userID', $_SESSION['userID']);
                        $stmt->execute();
                
                        if ($stmt->rowCount() > 0) {
                            echo "<script>alert('更新成功'); window.location.href = 'aboutme.php';</script>";
                        } else {
                            echo "<script>alert('無變更導致的未更新'); window.history.back();</script>";
                        }
                    } catch (PDOException $e) {
                        die("Database error during update: " . $e->getMessage());
                    }
                }
            }

            if ($fieldToUpdate === 'username') { //處理更改密碼需要加密的部分
                $stmt = $db->prepare("SELECT * FROM users WHERE username = :username");
                $stmt->bindParam(':username', $_POST['username']);
                $stmt->execute();
                if($stmt -> rowCount()>0){
                    echo "<script>alert('名稱重複!'); window.history.back();</script>";  
                }else{
                    try { //更新資料庫
                        $stmt = $db->prepare("UPDATE users SET `$fieldToUpdate` = :updateValue WHERE userID = :userID");
                        $stmt->bindParam(':updateValue', $updateValue);
                        $stmt->bindParam(':userID', $_SESSION['userID']);
                        $stmt->execute();
                
                        if ($stmt->rowCount() > 0) {
                            $_SESSION['username']=$updateValue;
                            echo "<script>alert('更新成功'); window.location.href = 'aboutme.php';</script>";
                        } else {
                            echo "<script>alert('無變更導致的未更新'); window.history.back();</script>";
                        }
                    } catch (PDOException $e) {
                        die("Database error during update: " . $e->getMessage());
                    }
                }
            }
        }        

    ?>
</head>

<body class="sub_page">

    <div class="hero_area">

        <div class="hero_bg_box">
        <div class="bg_img_box">
            <img src="images/hero-bg.png" alt="">
        </div>
        </div>

        <!-- header section strats -->
        <header class="header_section">
        <div class="container-fluid">
            <nav class="navbar navbar-expand-lg custom_nav-container ">
            <a class="navbar-brand" href="index.html">
                <span>
                丹尼斯 inc.
                </span>
            </a>

            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class=""> </span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav  ">
                        <li class="nav-item ">
                            <a class="nav-link" href="goods.php">商品頁面 </a>
                        </li>
                        <li class="nav-item ">
                            <a class="nav-link" href="sellItems.php">刊登商品 </a>
                        </li>
                        <li class="nav-item ">
                            <a class="nav-link" href="myProducts.php">我的商品 </a>
                        </li>
                        <li class="nav-item ">
                            <a class="nav-link" href="myCart.php">我的購物車 </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="aboutme.php"> <i class="fa fa-user" aria-hidden="true"></i> <?php echo $_SESSION['username'];?>的個人資訊</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="logout.php"> <i class="fa fa-user" aria-hidden="true"></i> 登出</a>
                        </li> 
                        <form class="form-inline">
                            <button class="btn  my-2 my-sm-0 nav_search-btn" type="submit">
                            <i class="fa fa-search" aria-hidden="true"></i>
                            </button>
                        </form>
                    </ul>
                </div>
            </nav>
        </div>
        </header>
        <!-- end header section -->
    </div>

    <!-- about section -->

    <section class="about_section layout_padding">
        <div class="container  ">
        <div class="heading_container heading_center">
            <h2>
            關於我
            </h2>
        </div>
        <div class="row">
            <div class="col-md-6">
            <div class="detail-box">
            <table>
                <tr>
                    <form action="aboutme.php" method="post" autocomplete="off">
                        <th>使用者名稱</th>
                        <td><input type="text" name="username" class="form-control border-0" value="<?php echo $username;?>" ></td>
                        <td><button type="submit" name="update" value="username">更改</button></td>
                    </form>
                </tr>
                <tr>
                    <form action="aboutme.php" method="post" autocomplete="off">
                        <th>帳號</th>
                        <td><input type="text" name="account" class="form-control border-0" value="<?php echo $account;?>" ></td>
                        <td><button type="submit" name="update" value="account">更改</button></td>
                    </form>
                </tr>
                <form action="aboutme.php" method="post" autocomplete="off">
                <tr>
                    <th>密碼</th>
                    <td><input type="password" name="password" ></td>
                    <td rowspan="2" ><button type="submit" name="update" value="password">更改</button></td>
                </tr>
                <tr>
                    <th>再次確認密碼</th>
                    <td><input type="password" name="confirmPassword" ></td>
                </tr>
                </form>
            </table>
                <p>
                    nihao
                </p>
            </div>
            </div>
        </div>
        </div>
    </section>

    <!-- end about section -->

    <!-- info section -->

    <section class="info_section layout_padding2">
        <div class="container">
        <div class="row">
            <div class="col-md-6 col-lg-3 info_col">
            <div class="info_contact">
                <h4>
                Address
                </h4>
                <div class="contact_link_box">
                <a href="">
                    <i class="fa fa-map-marker" aria-hidden="true"></i>
                    <span>
                    Arabia
                    </span>
                </a>
                <a href="">
                    <i class="fa fa-phone" aria-hidden="true"></i>
                    <span>
                    Call +01 1234567890
                    </span>
                </a>
                <a href="">
                    <i class="fa fa-envelope" aria-hidden="true"></i>
                    <span>
                    demo@gmail.com
                    </span>
                </a>
                </div>
            </div>
            <div class="info_social">
                <a href="">
                <i class="fa fa-facebook" aria-hidden="true"></i>
                </a>
                <a href="">
                <i class="fa fa-twitter" aria-hidden="true"></i>
                </a>
                <a href="">
                <i class="fa fa-linkedin" aria-hidden="true"></i>
                </a>
                <a href="">
                <i class="fa fa-instagram" aria-hidden="true"></i>
                </a>
            </div>
            </div>
            <div class="col-md-6 col-lg-3 info_col">
            <div class="info_detail">
                <h4>
                Info
                </h4>
                <p>
                uwu
                </p>
            </div>
            </div>
            <div class="col-md-6 col-lg-2 mx-auto info_col">
            <div class="info_link_box">
                <h4>
                Links
                </h4>
                <div class="info_links">
                <a class="active" href="index.html">
                    Home
                </a>
                </div>
            </div>
            </div>
        </div>
        </div>
    </section>

    <!-- end info section -->

    <!-- footer section -->
    <section class="footer_section">
        <div class="container">
        <p>
            &copy; <span id="displayYear"></span> All Rights Reserved By
            <a href="https://html.design/">Free Html Templates</a>
        </p>
        </div>
    </section>
    <!-- footer section -->

    <!-- jQery -->
    <script type="text/javascript" src="js/jquery-3.4.1.min.js"></script>
    <!-- popper js -->
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous">
    </script>
    <!-- bootstrap js -->
    <script type="text/javascript" src="js/bootstrap.js"></script>
    <!-- owl slider -->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js">
    </script>
    <!-- custom js -->
    <script type="text/javascript" src="js/custom.js"></script>
    <!-- Google Map -->
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCh39n5U-4IoWpsVGUHWdqB6puEkhRLdmI&callback=myMap">
    </script>
    <!-- End Google Map -->

</body>

</html>
        