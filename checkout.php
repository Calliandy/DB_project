<?php
// 確認用戶是否已登入，並且檢查是否按下了結帳按鈕
session_start();
include "db_connect.php";

if (isset($_SESSION['userID']) && isset($_POST['checkout'])) {
    $user_ID = $_SESSION['userID'];
    
    try {
        // 獲取商品資訊
        $product_ID = $_POST['productID'];
        $productTotalPrice = $_POST['productTotalPrice'];

        // 獲取用戶餘額
        $balance_stmt = $db->prepare("SELECT balance FROM users WHERE userID = :user_ID");
        $balance_stmt->bindParam(':user_ID', $user_ID);
        $balance_stmt->execute();
        $balance = $balance_stmt->fetchColumn();

        // 檢查餘額是否足夠支付
        if ($balance >= $productTotalPrice) {
            // 扣除用戶餘額
            $new_balance = $balance - $productTotalPrice;
            $update_balance_stmt = $db->prepare("UPDATE users SET balance = :new_balance WHERE userID = :user_ID");
            $update_balance_stmt->bindParam(':new_balance', $new_balance);
            $update_balance_stmt->bindParam(':user_ID', $user_ID);
            $update_balance_stmt->execute();

            // 更新賣家餘額
            $update_seller_balance_stmt = $db->prepare("UPDATE users 
                                                        INNER JOIN products ON users.userID = products.sellerID 
                                                        SET users.balance = users.balance + (:productTotalPrice) 
                                                        WHERE products.PID = :product_ID");
            $update_seller_balance_stmt->bindParam(':productTotalPrice', $productTotalPrice);
            $update_seller_balance_stmt->bindParam(':product_ID', $product_ID);
            $update_seller_balance_stmt->execute();

            // 減少商品庫存
            $update_product_amount_stmt = $db->prepare("UPDATE products 
                                        INNER JOIN carts ON carts.PID = products.PID 
                                        SET products.productAmount = products.productAmount - carts.amount 
                                        WHERE carts.PID = :product_ID AND carts.buyerID = :user_ID");
            $update_product_amount_stmt->bindParam(':user_ID', $user_ID);
            $update_product_amount_stmt->bindParam(':product_ID', $product_ID);
            $update_product_amount_stmt->execute();

            // 清空購物車中的商品
            $clear_cart_stmt = $db->prepare("DELETE FROM carts WHERE PID = :product_ID AND buyerID = :user_ID");
            $clear_cart_stmt->bindParam(':user_ID', $user_ID);
            $clear_cart_stmt->bindParam(':product_ID', $product_ID);
            $clear_cart_stmt->execute();

            echo "<script>alert('結帳成功!'); window.location.href='check.php';</script>";
        } else {
            echo "<script>alert('餘額不足'); window.location.href = 'check.php';</script>";
            exit();
        }

    } catch (PDOException $e) {
        // 處理錯誤
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "<script>alert('未登入或未點擊結帳按鈕！');</script>";
}
?>
