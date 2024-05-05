-- phpMyAdmin SQL Dump
-- version 5.1.1deb5ubuntu1
-- https://www.phpmyadmin.net/
--
-- 主機： localhost:3306
-- 產生時間： 2024 年 05 月 05 日 23:03
-- 伺服器版本： 10.6.16-MariaDB-0ubuntu0.22.04.1
-- PHP 版本： 8.1.2-1ubuntu2.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 資料庫: `DEoil`
--

-- --------------------------------------------------------

--
-- 資料表結構 `carts`
--

CREATE TABLE `carts` (
  `cartID` int(10) NOT NULL,
  `PID` int(10) NOT NULL,
  `buyerID` varchar(50) NOT NULL,
  `amount` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- 資料表結構 `orderDetail`
--

CREATE TABLE `orderDetail` (
  `detailID` int(10) NOT NULL,
  `orderID` int(10) NOT NULL,
  `PID` int(10) NOT NULL,
  `amount` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `orderDetail`
--

INSERT INTO `orderDetail` (`detailID`, `orderID`, `PID`, `amount`) VALUES
(1, 1, 12, 3),
(2, 2, 12, 5);

-- --------------------------------------------------------

--
-- 資料表結構 `orders`
--

CREATE TABLE `orders` (
  `orderID` int(10) NOT NULL,
  `sellerID` int(10) NOT NULL,
  `buyerID` int(10) NOT NULL,
  `totalPrice` int(10) NOT NULL,
  `date` date NOT NULL,
  `isDelivered` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `orders`
--

INSERT INTO `orders` (`orderID`, `sellerID`, `buyerID`, `totalPrice`, `date`, `isDelivered`) VALUES
(1, 219, 128, 1500, '2024-05-04', 'NO'),
(2, 219, 128, 2500, '2024-05-04', 'NO');

-- --------------------------------------------------------

--
-- 資料表結構 `products`
--

CREATE TABLE `products` (
  `PID` int(10) NOT NULL,
  `productName` varchar(50) NOT NULL,
  `productPrice` int(10) NOT NULL,
  `productAmount` int(10) NOT NULL,
  `productIntro` varchar(255) NOT NULL,
  `productCover` varchar(255) NOT NULL,
  `sellerID` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `products`
--

INSERT INTO `products` (`PID`, `productName`, `productPrice`, `productAmount`, `productIntro`, `productCover`, `sellerID`) VALUES
(10, '宅逼', 100, 15, '帥帥帥', 'shield.jpg', 128),
(12, '讚讚的吊飾', 500, 42, '讚讚的喔', 'money.jpg', 219),
(13, '1', 1, 1, '1', 'avater.jpg', 128),
(14, '2', 2, 2, '2', 'avater.jpg', 128),
(15, '3', 3, 3, '3', 'EMON.jpg', 128),
(16, '4', 4, 4, '4', 'favicon.jpg', 128),
(17, '5', 5, 5, '5', 'money.jpg', 128);

-- --------------------------------------------------------

--
-- 資料表結構 `users`
--

CREATE TABLE `users` (
  `userID` int(10) NOT NULL COMMENT 'key',
  `username` varchar(50) NOT NULL,
  `account` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `balance` int(10) NOT NULL,
  `role` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `users`
--

INSERT INTO `users` (`userID`, `username`, `account`, `password`, `balance`, `role`) VALUES
(128, 'GT', 'Junting', '$2y$10$il/wEj7j2NYGFD1tFjY8Z.QhMjSvZtLvMrMuNHWpKzd3xpoud02aa', 316925, 'user'),
(129, '管理員', 'admin', '$2y$10$NDRo4l8yTzMgT8VOhokRPuEU38k33bFbasiotQmGRAYdN7Alg6vNe', 10000, 'admin'),
(211, 'test2', 'test2', '$2y$10$KSQLJXi9MKpy/J5WsRmog.FqL1IDQDaY4R6CV4EoBZ4F8rxXUAsuC', 10000, 'user'),
(219, 'test1', 'test1', '$2y$10$OlyyIkz5TmwEbC51UbR7aepLe84rVT8x04JxVqT0POD3y0rbNQ70.', 33500, 'user'),
(220, 'test3', 'test3', '$2y$10$M799Vd6ek1yWbmrDqlaWqehfBn/MOEziIw5e9uB5FHBuFqJBg8nDC', 10000, 'user'),
(221, 'test4', 'test4', '$2y$10$EGJlh0u9gEX4MZlnouWZw.MpILQhnUh3Kjck1wrDeS98Bt2OnLr86', 10000, 'user'),
(222, 'test5', 'test5', '$2y$10$lUf.L3.0YeQY0sl/vUxYguymmHH2/XEHPe0cl9MAaraAxuSkK/aGO', 10000, 'user'),
(223, '1234', '1234', '$2y$10$VlgZZOys8XkISqNefRsw9eSP1w9oOenSsiOLczs8iQZiDEMJU43RK', 10000, 'user');

--
-- 已傾印資料表的索引
--

--
-- 資料表索引 `carts`
--
ALTER TABLE `carts`
  ADD PRIMARY KEY (`cartID`);

--
-- 資料表索引 `orderDetail`
--
ALTER TABLE `orderDetail`
  ADD PRIMARY KEY (`detailID`);

--
-- 資料表索引 `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`orderID`);

--
-- 資料表索引 `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`PID`);

--
-- 資料表索引 `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`userID`);

--
-- 在傾印的資料表使用自動遞增(AUTO_INCREMENT)
--

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `carts`
--
ALTER TABLE `carts`
  MODIFY `cartID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `orderDetail`
--
ALTER TABLE `orderDetail`
  MODIFY `detailID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `orders`
--
ALTER TABLE `orders`
  MODIFY `orderID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `products`
--
ALTER TABLE `products`
  MODIFY `PID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `users`
--
ALTER TABLE `users`
  MODIFY `userID` int(10) NOT NULL AUTO_INCREMENT COMMENT 'key', AUTO_INCREMENT=224;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
