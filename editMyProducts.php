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
                    我的商品
                    <a href='myProducts.php'><button class='nav_search-btn'>回到管理商品介面</button></a>
                </h2>
                <form method="GET" action="editMyProducts.php">
                    <input name="keyword" placeholder="搜尋你的商品名稱"></input>
                    <button type="submit" name="searchBtn">搜尋</button>
                    <button onclick="window.history.back()">取消搜尋</button>
                </form>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="detail-box">
                    <?php
                        // 設定每頁顯示的資料筆數
                        $records_per_page = 5;

                        // 初始化搜尋條件
                        $search_keyword = '';

                        // 檢查是否有搜尋關鍵字
                        if (isset($_GET['keyword'])) {
                            $search_keyword = $_GET['keyword'];
                        }

                        // 獲取當前頁碼
                        if (isset($_GET['page']) && is_numeric($_GET['page'])) {
                            $current_page = (int)$_GET['page'];
                        } else {
                            $current_page = 1;
                        }

                        // 計算起始擷取的資料索引
                        $start_index = ($current_page - 1) * $records_per_page;

                        try {
                            // 準備 SQL 查詢，擷取指定範圍內的資料
                            $sql = "SELECT * FROM products";
                            $sellerID = $_SESSION['userID'];
                            // 添加搜尋條件
                            if (!empty($search_keyword)) {
                                $sql .= " WHERE productName LIKE :keyword";
                            }

                            if (!empty($sellerID)) {
                                if (!empty($search_keyword)) {
                                    $sql .= " AND sellerID = :sellerID";
                                } else {
                                    $sql .= " WHERE sellerID = :sellerID";
                                }
                            }

                            $sql .= " LIMIT :start_index, :records_per_page";

                            // 準備查詢
                            $stmt = $db->prepare($sql);

                            // 綁定參數
                            $stmt->bindParam(':sellerID', $sellerID);
                            $stmt->bindParam(':start_index', $start_index, PDO::PARAM_INT);
                            $stmt->bindParam(':records_per_page', $records_per_page, PDO::PARAM_INT);

                            // 添加搜尋參數
                            if (!empty($search_keyword)) {
                                $keyword = '%' . $search_keyword . '%';
                                $stmt->bindParam(':keyword', $keyword, PDO::PARAM_STR);
                            }

                            // 執行查詢
                            $stmt->execute();

                            // 檢查是否有資料
                            if ($stmt->rowCount() > 0) {
                                // 逐行讀取資料並輸出
                                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                    echo "<div style='background-color: black; padding: 10px; margin-bottom: 10px;'>";
                                    echo "<form method='POST'>";
                                    echo "商品名稱:<input type='text' name='productName' value='" . $row["productName"] . "'><br>";
                                    echo "商品價格:<input type='text' name='productPrice' value='" . $row["productPrice"] . "'><br>";
                                    echo "商品數量:<input type='text' name='productAmount' value='" . $row["productAmount"] . "'><br>";
                                    echo "商品介紹:<input type='text' name='productIntro' value='" . $row["productIntro"] . "'><br>";
                                    echo "<input type='hidden' name='productID' value='" . $row["PID"] . "'>";
                                    echo "<button type='submit' name='editProduct'>編輯商品</button>";
                                    echo "</form>";
                                    echo "</div>";
                                }
                            } else {
                                echo "0 筆結果";
                            }

                            // 獲取特定賣家的商品總數
                            if(!empty($search_keyword)){
                                $total_records_stmt = $db->prepare("SELECT COUNT(*) FROM products WHERE sellerID = :userID AND productName LIKE :keyword");
                                $total_records_stmt->bindParam(":userID",$_SESSION['userID']);
                                $total_records_stmt->bindParam(":keyword",$keyword);
                            }else{
                                $total_records_stmt = $db->prepare("SELECT COUNT(*) FROM products WHERE sellerID = :userID");
                                $total_records_stmt->bindParam(":userID",$_SESSION['userID']);
                            }
                            $total_records_stmt->execute();
                            $total_records = $total_records_stmt->fetchColumn();

                            // 計算總頁數
                            $total_pages = ceil($total_records / $records_per_page);

                            // 顯示分頁連結
                            echo "<br>分頁";
                            for ($i = 1; $i <= $total_pages; $i++) {
                                // 顯示分頁連結時也包含搜尋關鍵字
                                $page_link = "?page=$i";
                                if (!empty($search_keyword)) {
                                    $page_link .= "&keyword=$search_keyword";
                                }
                                // 檢查當前頁碼是否小於或等於總頁數，只有在這種情況下才生成分頁連結
                                if ($i <= $total_pages) {
                                    echo "<a href='$page_link'>$i</a> ";
                                }
                            }
                        } catch (PDOException $e) {
                            // Handle any errors
                            echo "Error: " . $e->getMessage();
                        }

                        // Handle edit product form submission
                        if (isset($_POST['editProduct'])) {
                            $productId = $_POST['productID'];
                            $productName = !empty($_POST['productName']) ? $_POST['productName'] : null;
                            $productPrice = !empty($_POST['productPrice']) ? $_POST['productPrice'] : null;
                            $productAmount = !empty($_POST['productAmount']) ? $_POST['productAmount'] : null;
                            $productIntro = !empty($_POST['productIntro']) ? $_POST['productIntro'] : null;

                            // Update the product with new values
                            try {
                                $sql = "UPDATE products SET productName = :productName, productPrice = :productPrice, productAmount = :productAmount, productIntro = :productIntro WHERE PID = :productID";
                                $stmt = $db->prepare($sql);
                                $stmt->bindParam(':productName', $productName);
                                $stmt->bindParam(':productPrice', $productPrice);
                                $stmt->bindParam(':productAmount', $productAmount);
                                $stmt->bindParam(':productIntro', $productIntro);
                                $stmt->bindParam(':productID', $productId);
                                $stmt->execute();
                                // Redirect to the same page to update the product list
                                echo "<script>alert('編輯商品成功'); window.location.href='editMyProducts.php'; </script>";
                                exit();
                            } catch (PDOException $e) {
                                // Handle any errors
                                echo "Error: " . $e->getMessage();
                            }
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