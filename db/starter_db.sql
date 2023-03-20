/*
SQLyog Ultimate v13.1.9 (64 bit)
MySQL - 10.4.24-MariaDB : Database - roomraccoon_db
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`roomraccoon_db` /*!40100 DEFAULT CHARACTER SET utf8mb4 */;

USE `roomraccoon_db`;

/*Table structure for table `cart` */

DROP TABLE IF EXISTS `cart`;

CREATE TABLE `cart` (
  `cart_id` int(11) NOT NULL AUTO_INCREMENT,
  `prod_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `prod_qty` int(11) NOT NULL,
  `cartDateTime` datetime DEFAULT current_timestamp(),
  `isCheckout` char(1) DEFAULT 'N',
  `isActive` char(1) DEFAULT 'Y',
  PRIMARY KEY (`cart_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

/*Data for the table `cart` */

/*Table structure for table `products` */

DROP TABLE IF EXISTS `products`;

CREATE TABLE `products` (
  `prod_id` int(11) NOT NULL AUTO_INCREMENT,
  `prod_name` varchar(255) NOT NULL,
  `prod_price` double(20,2) NOT NULL,
  `prod_rating` int(11) NOT NULL,
  `prod_img` varchar(255) DEFAULT NULL,
  `prod_descr` text NOT NULL,
  PRIMARY KEY (`prod_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4;

/*Data for the table `products` */

insert  into `products`(`prod_id`,`prod_name`,`prod_price`,`prod_rating`,`prod_img`,`prod_descr`) values 
(1,'Apple Watch Series 8',7999.00,2,'apple_watch_series_8.png',''),
(2,'Apple Watch SE Blue',4999.99,4,'apple_watch_se_blue.png',''),
(3,'Bluetooth Speaker',499.00,2,'bluetooth_speaker.jpg',''),
(4,'Plain Ear Buds',31.22,4,'ear_bud_set.jpg',''),
(5,'Mouse Pad',22.34,2,'mouse_pad.jpg',''),
(6,'Blue Power Bank',79.99,4,'power_bank.jpg',''),
(7,'Power Saver Power Bank with Light',245.33,5,'power_bank_2.jpg',''),
(8,'Skull Candy Headset',4750.21,4,'skullcandy1.jpg',''),
(9,'Skull Candy Earphones',254.32,5,'skullcandy2.jpg',''),
(10,'Skull Candy Ear Buds',113.23,5,'skullcandy3.jpg','');

/*Table structure for table `sales` */

DROP TABLE IF EXISTS `sales`;

CREATE TABLE `sales` (
  `sale_id` int(11) NOT NULL AUTO_INCREMENT,
  `prod_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `prod_qty` int(11) DEFAULT NULL,
  `saleGUID` varchar(255) NOT NULL,
  `saleDateTime` date NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`sale_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

/*Data for the table `sales` */

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_email` varchar(255) NOT NULL,
  `user_pwd` varchar(255) NOT NULL,
  `user_name` varchar(255) NOT NULL,
  `registerDateTime` datetime DEFAULT current_timestamp(),
  `lastLoginDateTime` datetime DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `user_email_2` (`user_email`),
  KEY `user_email` (`user_email`,`user_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

/*Data for the table `users` */

/* Procedure structure for procedure `pd_cart` */

/*!50003 DROP PROCEDURE IF EXISTS  `pd_cart` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `pd_cart`(aUserId int, aCartItemId int)
BEGIN
	
		-- set active to N (user deleted from cart)
		update `cart`
		set `isActive` = 'N'
		where `user_id` =  aUserId and 
		`cart_id` = aCartItemId;
		
		select 'valid' as aMsg;

	END */$$
DELIMITER ;

/* Procedure structure for procedure `pi_addToCart` */

/*!50003 DROP PROCEDURE IF EXISTS  `pi_addToCart` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `pi_addToCart`(aProdId int, aUserId int, aProdQty int)
BEGIN

		insert into `cart`(`prod_id`, `user_id`, `prod_qty`, `cartDateTime`)
			values(aProdId, aUserId, aProdQty, now());
			
		select 'valid' as aMsg;
	
	END */$$
DELIMITER ;

/* Procedure structure for procedure `pi_checkout` */

/*!50003 DROP PROCEDURE IF EXISTS  `pi_checkout` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `pi_checkout`(aUserId int)
BEGIN
		-- sale identifier
		declare aSaleUId text default '';
		set aSaleUId = UUID();
	
		-- take items from cart to finished sale
		insert into `sales`(`prod_id`, `user_id`, `prod_qty`, `saleGUID`, `saleDateTime`)
		select `prod_id`, aUserId, `prod_qty`, aSaleUId, now()
		from `cart` where `user_id` = aUserId and `isActive` = 'Y';
		
		-- remove items from cart
		update `cart`
		set `isCheckout` = 'Y'
		where `user_id` = aUserId;
		

	END */$$
DELIMITER ;

/* Procedure structure for procedure `pu_updateCart` */

/*!50003 DROP PROCEDURE IF EXISTS  `pu_updateCart` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `pu_updateCart`(aCartId int, aProdQty int)
BEGIN
	
		update `cart` 
		set `prod_qty` = aProdQty
		where `cart_id` = aCartId;

	END */$$
DELIMITER ;

/* Procedure structure for procedure `sp_getCartAmountTotal` */

/*!50003 DROP PROCEDURE IF EXISTS  `sp_getCartAmountTotal` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_getCartAmountTotal`(aUserId int)
BEGIN
	
		select sum(`cart`.`prod_qty`*`products`.`prod_price`) as Total
		from `cart`
		left join `products` on
		`cart`.`prod_id` = `products`.`prod_id`
		where `cart`.`user_id` = aUserId
		and `cart`.`isActive` = 'Y';

	END */$$
DELIMITER ;

/* Procedure structure for procedure `sp_getCartItems` */

/*!50003 DROP PROCEDURE IF EXISTS  `sp_getCartItems` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_getCartItems`(aUserId int)
BEGIN
	
		SELECT 
		`cart`.`cart_id`,`products`.`prod_name`, `products`.`prod_price`, `cart`.`prod_qty`, `products`.`prod_img`
		FROM `cart`
		left join `products`
		on `cart`.`prod_id` = `products`.`prod_id`
		WHERE `cart`.`user_id` = aUserId AND 
		`cart`.`isActive` = 'Y' AND 
		`cart`.`isCheckout` = 'N';

	END */$$
DELIMITER ;

/* Procedure structure for procedure `sp_getCartTotal` */

/*!50003 DROP PROCEDURE IF EXISTS  `sp_getCartTotal` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_getCartTotal`(aUserId int)
BEGIN

		select count(*) as aCartTotal 
		from `cart`
		where `user_id` = aUserId and 
		`isActive` = 'Y' and 
		`isCheckout` = 'N';

	END */$$
DELIMITER ;

/* Procedure structure for procedure `sp_getItemsPurchased` */

/*!50003 DROP PROCEDURE IF EXISTS  `sp_getItemsPurchased` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_getItemsPurchased`(aUserId int)
BEGIN
	
		SELECT 
		`products`.`prod_name`, `products`.`prod_price`, `sales`.`prod_qty`, `products`.`prod_img`
		FROM `sales`
		LEFT JOIN `products`
		ON `sales`.`prod_id` = `products`.`prod_id`
		WHERE `sales`.`user_id` = aUserId ;
	END */$$
DELIMITER ;

/* Procedure structure for procedure `sp_getProducts` */

/*!50003 DROP PROCEDURE IF EXISTS  `sp_getProducts` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_getProducts`()
BEGIN
	
		select * from `products`;

	END */$$
DELIMITER ;

/* Procedure structure for procedure `sp_getTechSteals` */

/*!50003 DROP PROCEDURE IF EXISTS  `sp_getTechSteals` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_getTechSteals`()
BEGIN
	
		SELECT * FROM `products` order by  rand() limit 5;

	END */$$
DELIMITER ;

/* Procedure structure for procedure `sp_getUserIdByEmail` */

/*!50003 DROP PROCEDURE IF EXISTS  `sp_getUserIdByEmail` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_getUserIdByEmail`(aUserEmail varchar(255))
BEGIN
	
	
		select `user_id` as aUserId
		from `users` where `user_email` = aUserEmail;

	END */$$
DELIMITER ;

/* Procedure structure for procedure `sp_loginUser` */

/*!50003 DROP PROCEDURE IF EXISTS  `sp_loginUser` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_loginUser`(aUserEmail varchar(255), aUserPwd varchar(255))
BEGIN
	
		declare aCnt int default 0;
		declare aMsg varchar(255) default '';
		
		select count(*) into aCnt 
		from `users`
		where `user_email` = aUserEmail and 
		`user_pwd` = password(aUserPwd);
		
		if aCnt > 0 then 
			set aMsg = 'valid';
			
			update `users`
			set `lastLoginDateTime` = now()
			where `user_email` = aUserEmail;
		end if;
		
		select aMsg;

	END */$$
DELIMITER ;

/* Procedure structure for procedure `sp_registerUser` */

/*!50003 DROP PROCEDURE IF EXISTS  `sp_registerUser` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_registerUser`(aUserEmail varchar(255), aUserPwd varchar(255), aUserName varchar(255))
BEGIN
	
		declare aCnt int default 0;
		declare aMsg varchar(255) default 'invalid';
		
		select count(*) into aCnt 
		from `users`
		where `user_email` = aUserEmail;
		
		if aCnt = 0 then
			set aMsg = 'valid';
			
			insert into `users`(`user_email`, `user_pwd`, `user_name`, `registerDateTime`)
				values(aUserEmail , password(aUserPwd) , aUserName, now());
		end if;
		
		select aMsg;

	END */$$
DELIMITER ;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
