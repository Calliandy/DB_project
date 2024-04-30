<?php
    session_start();
    include "db_connect.php";

    // if ($_SERVER['REQUEST_METHOD'] === "POST"){
    //     echo "POST";
    //     if (isset($_POST['productId'])){
    //         echo "PID";
    //         if(isset($_POST['amount'])){
    //             echo "PA";
    //         }
    //         if(isset($_POST['productName'])){
    //             echo "PNAME";
    //         }
    //         if(isset($_POST['productAmount'])){
    //             echo "PAM";
    //         }
    //     }
    // }
    if (isset($_SESSION['username'])) {
        if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['productId']) && isset($_POST['amount']) && isset($_POST['productName']) && isset($_POST['productAmount'])) {
            $productId = $_POST['productId'];
            $amount = $_POST['amount'];
            $productName = $_POST['productName'];
            $productAmount = $_POST['productAmount'];

            if ($amount > $productAmount) {
                echo "購買數量超出庫存。";
            } else {
                // 執行插入操作
                $stmt = $db->prepare("INSERT INTO shoppingcart (buyerID, productID, productName, amount) VALUES (:userID, :productID, :productName, :amount)");
                $stmt->bindParam(':userID', $_SESSION['userID']);
                $stmt->bindParam(':productID', $productId);
                $stmt->bindParam(':productName', $productName);
                $stmt->bindParam(':amount', $amount);
                
                if ($stmt->execute()) {
                    echo "商品已成功加入購物車！";
                } else {
                    echo "添加到購物車失敗。";
                }
            }
        } else {
            echo "無效的請求。";
        }
    } else {
        echo "請先登入。";
    }
?>