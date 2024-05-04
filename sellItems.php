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
            上架您的商品
            </h2>
        </div>
        <div class="row">
            <div class="col-md-6">
            <div class="detail-box">
                <form method="POST" action="">
                    <p>商品名稱:</p><input type="text" maxlength="50" id="productName" name="productName"><br>
                    <p>價格:</p><input type="text" maxlength="50" id="productPrice" name="productPrice"><br>
                    <p>想賣幾個:</p><input type="text" maxlength="50" id="productAmount" name="productAmount"><br>
                    <p>商品介紹</p><input type="text" maxlength="255" id="productIntro" name="productIntro"><br>
                    <p>商品封面圖片:</p><input type="file" id="productCover" name="productCover" required><br>
                    <button type="submit" name="uploadBtn">刊登</button>
                    <button type="reset" name="resetBtn">重設資訊</button>                
                </form>
                </p>
                <h3>
                    <?php
                        if ($_SERVER["REQUEST_METHOD"] == "POST") {
                            if (isset($_POST['uploadBtn'])) {
                                if (empty($_POST['productName']) || empty($_POST['productPrice']) || empty($_POST['productAmount']) || empty($_POST['productIntro'])) {
                                    echo "<script>alert('您的商品資訊不完整');</script>";
                                } else {
                                    $productName = $_POST['productName'];
                                    $productPrice = $_POST['productPrice'];
                                    $productAmount = $_POST['productAmount'];
                                    $productIntro = $_POST['productIntro'];
                                    $productCover = $_POST['productCover'];
                                    $sellerID = $_SESSION['userID'];
                                    
                                    try {
                                        // Prepare SQL statement
                                        $stmt = $db->prepare("SELECT * FROM products WHERE productName = :productName");
                                        $stmt->bindParam(':productName', $productName);
                                        
                                        // Execute the query
                                        $stmt->execute();
                                        
                                        // Fetch the row
                                        $product = $stmt->fetch(PDO::FETCH_ASSOC);
                                        
                                        // Prepare INSERT statement
                                        $stmt = $db->prepare("INSERT INTO `products` (`productName`, `productPrice`, `productAmount`, `productIntro`, `productCover`, `sellerID`) VALUES (:productName, :productPrice, :productAmount, :productIntro, :productCover, :sellerID)");
                                        
                                        // Bind parameters
                                        $stmt->bindParam(':productName', $productName);
                                        $stmt->bindParam(':productPrice', $productPrice);
                                        $stmt->bindParam(':productAmount', $productAmount);
                                        $stmt->bindParam(':productIntro', $productIntro);
                                        $stmt->bindParam(':productCover', $productCover);
                                        $stmt->bindParam(':sellerID', $sellerID);
                                        
                                        // Execute the query
                                        if ($stmt->execute()) {
                                            echo "商品" . $productName . "上傳成功!";
                                        } else {
                                            echo "上傳失敗 :(";
                                        }
                                    } catch (PDOException $e) {
                                        echo "Error: " . $e->getMessage();
                                    }
                                }
                            }
                        }
                    ?>
                </h3>
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