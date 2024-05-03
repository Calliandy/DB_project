<?php
    session_start();
    include "db_connect.php";
?>
<?php
    try {
        // 準備 SQL 查詢，擷取資料
        $sql = "SELECT products.productName, products.productAmount, products.productPrice
                FROM carts 
                INNER JOIN products ON carts.productID = products.productID";

        // 準備查詢
        $stmt = $db->query($sql);

        // 初始化總價格
        $totalPrice = 0;

        // 輸出表格標題
        echo "<table><tr><th>產品名稱</th><th>數量</th><th>單價</th></tr>";

        // 處理每一行資料
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $productName = htmlspecialchars($row['productName']);
            $productAmount = htmlspecialchars($row['productAmount']);
            $productPrice = htmlspecialchars($row['productPrice']);
            $productTotalPrice = $row['productAmount'] * $row['productPrice'];

            // 輸出該產品的資料;
            echo "<tr>";
            echo "<td>$productName</td>";
            echo "<td>&nbsp;&nbsp;$productAmount&nbsp;&nbsp;</td>";
            echo "<td>&nbsp;&nbsp;$productPrice&nbsp;&nbsp;</td>";
            echo "</tr>";

            // 加總價格
            $totalPrice += $productTotalPrice;

            // 添加分隔行
            echo "<tr><td colspan='3'><hr></td></tr>";
        }

        // 輸出總價格
        echo "</table>";
        echo "<br>商品總價格: $" . $totalPrice;

    } catch (PDOException $e) {
        // 處理錯誤
        echo "Error: " . $e->getMessage();
    }    
?>






