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
        if (!isset($_SESSION['username'])) {
            echo "<script>alert('偵測到未登入'); window.location.href = 'login.php';</script>";
            exit(); 
        }
    ?>
    <?php
    
        // 檢查是否有表單提交
        if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['topupAmount'])) {
            // 獲取表單提交的儲值金額
            $topupAmount = $_POST['topupAmount'];
    
            // 驗證輸入的值是否為數字
            if (!is_numeric($topupAmount)) {
                echo "<script>alert('請輸入有效的數字'); window.location.href = 'topUp.php';</script>";
                exit();
            }
    
            // 獲取當前使用者的ID
            $userID = $_SESSION['userID'];
    
            try {
                // 查詢使用者當前的餘額
                $balance_stmt = $db->prepare("SELECT balance FROM users WHERE userID = :userID");
                $balance_stmt->bindParam(':userID', $userID);
                $balance_stmt->execute();
                $currentBalance = $balance_stmt->fetchColumn();
    
                // 更新使用者的餘額
                $newBalance = $currentBalance + $topupAmount;
                $update_balance_stmt = $db->prepare("UPDATE users SET balance = :newBalance WHERE userID = :userID");
                $update_balance_stmt->bindParam(':newBalance', $newBalance);
                $update_balance_stmt->bindParam(':userID', $userID);
                $update_balance_stmt->execute();
    
                // 儲值成功提示
                echo "<script>alert('儲值成功！'); window.location.href = 'topUp.php';</script>";
            } catch (PDOException $e) {
                // 處理錯誤
                echo "Error: " . $e->getMessage();
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
                    <li class="nav-item ">
                        <a class="nav-link" href="myDetail.php">我的購物明細 </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="aboutme.php"> 
                            <i class="fa fa-user" aria-hidden="true"></i> <?php echo $_SESSION['username'];?>的個人資訊
                        </a>
                    </li>
                    <li class="nav-item ">
                        <?php
                            // 在這裡顯示使用者的餘額
                            // 假設$_SESSION['userID']儲存了當前使用者的ID
                            $userID = $_SESSION['userID'];

                            // 查詢用戶的餘額
                            $balance_stmt = $db->prepare("SELECT balance FROM users WHERE userID = :userID");
                            $balance_stmt->bindParam(':userID', $userID);
                            $balance_stmt->execute();
                            $balance = $balance_stmt->fetchColumn();

                            // 顯示用戶餘額
                            echo '<a class="nav-link" href="topUp.php">餘額：' . $balance . '(點擊前往儲值頁面)</a>';
                        ?>
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

    <!-- about section -->

    <section class="about_section layout_padding">
        <div class="container  ">
            <div class="heading_container heading_center">
                <h2>儲值頁面</h2>
            </div>
            <div class="row">
                <div class="col-md-6 ">
                    <div class="img-box">
                        <img src="images/about_img.jpg" alt="">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="detail-box">
                        <!-- 修改此處 -->
                        <form action="topUp.php" method="post">
                            <label for="topupAmount">儲值金額：</label>
                            <input type="number" id="topupAmount" name="topupAmount" required>
                            <button type="submit">確認儲值</button>
                        </form>
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