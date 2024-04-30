<?php
    session_start();

    include "db_connect.php"; // 包含資料庫連接程式碼

    // 處理越權查看以及錯誤登入
    if (!isset($_SESSION['username'])) {
        echo "<script>alert('偵測到未登入'); window.location.href = 'login.php';</script>";
        exit();
    } else if ($_SESSION['role'] != "admin") {
        echo "<script>alert('無權訪問'); window.location.href = 'logout.php';</script>";
        exit();
    }

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
        $sql = "SELECT * FROM users";

        // 添加搜尋條件
        if (!empty($search_keyword)) {
            $sql .= " WHERE username LIKE :keyword";
        }

        $sql .= " LIMIT :start_index, :records_per_page";

        // 準備查詢
        $stmt = $db->prepare($sql);

        // 綁定參數
        $stmt->bindParam(':start_index', $start_index, PDO::PARAM_INT);
        $stmt->bindParam(':records_per_page', $records_per_page, PDO::PARAM_INT);

        // 添加搜尋參數
        if (!empty($search_keyword)) {
            $keyword = '%' . $search_keyword . '%';
            $stmt->bindParam(':keyword', $keyword, PDO::PARAM_STR);
        }

        $stmt->execute();

        // 檢查是否有資料
        if ($stmt->rowCount() > 0) {
            // 輸出資料表格
            echo "<table><tr><th>ID</th><th>身分組</th><th>帳號</th><th>操作</th></tr>";
            while ($user = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($user['account']) . "&nbsp&nbsp</td>";
                echo "<td>" . htmlspecialchars($user['role']) . "&nbsp&nbsp</td>";
                echo "<td>" . htmlspecialchars($user['username']) . "&nbsp&nbsp</td>";
                echo "<td><form action=\"manageUsers.php\" method=\"post\" onsubmit=\"return confirmDelete();\">
                        <input type=\"hidden\" name=\"deleteID\" value=\"" . $user['userID'] . "\">
                        <button type=\"submit\" value=\"deleteUser\" style=\"background-color: #FF0000; color: white;\">刪除使用者</button>
                        </form></td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "0 筆結果";
        }

        // 獲取總共的資料筆數
        $total_records_stmt = $db->query("SELECT COUNT(*) FROM users");
        $total_records = $total_records_stmt->fetchColumn();

        // 計算總頁數
        $total_pages = ceil($total_records / $records_per_page);

        // 顯示分頁連結
        echo "<br>分頁";
        for ($i = 1; $i <= $total_pages; $i++) {
            echo "<a href='?page=$i'>$i</a> ";
        }
    } catch (PDOException $e) {
        // 處理錯誤
        echo "Error: " . $e->getMessage();
    }

    // 處理刪除使用者的表單提交
    if (($_SERVER['REQUEST_METHOD'] === "POST") && (isset($_POST['deleteID']))) {
        $deleteUserID = $_POST['deleteID'];
        $stmt = $db->prepare("DELETE FROM `users` WHERE userID = :deleteID");
        $stmt->bindParam(':deleteID', $deleteUserID);
        $stmt->execute();
        header("location: manageUsers.php");
    }
?>

