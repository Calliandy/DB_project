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
        session_start();
        include "db_connect.php";
        // 處理越權查看以及錯誤登入
        if (!isset($_SESSION['username'])) {
            echo "<script>alert('偵測到未登入'); window.location.href = 'login.php';</script>";
            exit();
        } else if ($_SESSION['role'] != "user") {
            echo "<script>alert('權限錯誤'); window.location.href = 'logout.php';</script>";
            exit();
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

        <section class="about_section layout_padding">
            <div class="container  ">
            <div class="heading_container heading_center">
                <h2>
                    結帳頁面
                </h2>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="detail-box">
                        <?php
                            try {
                                // 準備 SQL 查詢，擷取資料
                                $sql = "SELECT carts.*, products.productName, products.productCover, products.productPrice 
                                        FROM carts 
                                        INNER JOIN products ON carts.PID = products.PID WHERE carts.buyerID = :userID";

                                // 準備查詢
                                $stmt = $db->prepare($sql);
                                $stmt->bindParam(":userID",$_SESSION['userID']);
                                // 初始化總價格
                                $totalPrice = 0;

                                $stmt->execute();

                                // 輸出表格標題
                                echo "<table><tr><th>產品名稱</th><th>數量</th><th>單價</th><th>總價</th><th>圖片</th><th>操作</th></tr>";

                                // 處理每一行資料
                                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                    $productName = htmlspecialchars($row['productName']);
                                    $productAmount = htmlspecialchars($row['amount']);
                                    $productPrice = htmlspecialchars($row['productPrice']);
                                    $productTotalPrice = $row['amount'] * $row['productPrice'];
                                    $productID = $row['PID'];

                                    // 輸出該產品的資料
                                    echo "<tr>";
                                    echo "<td>$productName</td>";
                                    echo "<td>&nbsp;&nbsp;$productAmount&nbsp;&nbsp;</td>";
                                    echo "<td>&nbsp;&nbsp;$productPrice&nbsp;&nbsp;</td>";
                                    echo "<td>&nbsp;&nbsp;$productTotalPrice&nbsp;&nbsp;</td>";
                                    echo '<td><img src="data:image/jpeg;base64, '. base64_encode($row["productCover"]) .'" style="max-width: 500px; max-height: 500px;"></td><br>';
                                    echo "<td><form action='checkout.php' method='post'>";
                                    echo "<input type='hidden' name='productAmount' value='$productAmount'>";
                                    echo "<input type='hidden' name='productID' value='$productID'>";
                                    echo "<input type='hidden' name='productTotalPrice' value='$productTotalPrice'>";
                                    echo "<input type='submit' name='checkout' value='結帳'>";
                                    echo "</form></td>";
                                    echo "</tr>";

                                    // 加總價格
                                    $totalPrice += $productTotalPrice;

                                    // 添加分隔行
                                    echo "<tr><td colspan='4'></td></tr>";
                                }

                                // 輸出總價格
                                echo "</table>";
                                echo "<br>商品總價格: $" . $totalPrice;

                            } catch (PDOException $e) {
                                // 處理錯誤
                                echo "Error: " . $e->getMessage();
                            }
                        ?>
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