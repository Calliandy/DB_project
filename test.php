<?php

    include "db.php";
    // 假設您已經建立了與資料庫的連線

    // 設定每頁顯示的資料筆數
    $records_per_page = 10;

    // 獲取當前頁碼
    if (isset($_GET['page']) && is_numeric($_GET['page'])) {
        $current_page = (int)$_GET['page'];
    } else {
        $current_page = 1;
    }

    // 計算起始擷取的資料索引
    $start_index = ($current_page - 1) * $records_per_page;

    // 準備 SQL 查詢，擷取指定範圍內的資料
    $sql = "SELECT * FROM users LIMIT $start_index, $records_per_page";

    // 執行查詢
    $result = mysqli_query($conn, $sql);

    // 檢查是否有資料
    if (mysqli_num_rows($result) > 0) {
        // 逐行讀取資料並輸出
        while ($row = mysqli_fetch_assoc($result)) {
            echo "ID: " . $row["user_ID"] . " - Name: " . $row["username"] . "<br>";
            // 根據您的資料表結構，輸出其他欄位
        }
    } else {
        echo "0 筆結果";
    }

    // 釋放結果集
    mysqli_free_result($result);

    // 獲取總共的資料筆數
    $total_records_sql = "SELECT COUNT(*) FROM users";
    $total_records_result = mysqli_query($conn, $total_records_sql);
    $total_records_row = mysqli_fetch_row($total_records_result);
    $total_records = $total_records_row[0];

    // 計算總頁數
    $total_pages = ceil($total_records / $records_per_page);

    // 顯示分頁連結
    echo "<br>";
    for ($i = 1; $i <= $total_pages; $i++) {
        echo "<a href='?page=$i'>$i</a> ";
    }
?>
