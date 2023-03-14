/*
SQLyog Ultimate v8.55 
MySQL - 5.7.24 : Database - db_hotelv3
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
/*Table structure for table `additional_costs` */

DROP TABLE IF EXISTS `additional_costs`;

CREATE TABLE `additional_costs` (
  `additional_cost_id` int(11) NOT NULL AUTO_INCREMENT,
  `room_reservation_id` int(11) DEFAULT NULL,
  `additional_cost_type` enum('discount','request','loss or damage') DEFAULT NULL,
  `additional_cost_description` text,
  `additional_cost_price` bigint(20) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`additional_cost_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `additional_costs` */

/*Table structure for table `cleaning_histories` */

DROP TABLE IF EXISTS `cleaning_histories`;

CREATE TABLE `cleaning_histories` (
  `cleaning_history_id` int(11) NOT NULL AUTO_INCREMENT,
  `room_id` int(11) DEFAULT NULL,
  `cleaning_description` text,
  `cleaning_status` enum('VR','VC','VD','OD','OC','OO') DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`cleaning_history_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `cleaning_histories` */

/*Table structure for table `extend_histories` */

DROP TABLE IF EXISTS `extend_histories`;

CREATE TABLE `extend_histories` (
  `extend_history_id` int(11) NOT NULL AUTO_INCREMENT,
  `reservation_id` int(11) DEFAULT NULL,
  `extend_before` date DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`extend_history_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `extend_histories` */

/*Table structure for table `floors` */

DROP TABLE IF EXISTS `floors`;

CREATE TABLE `floors` (
  `floor_id` int(11) NOT NULL AUTO_INCREMENT,
  `floor_name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`floor_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

/*Data for the table `floors` */

insert  into `floors`(`floor_id`,`floor_name`) values (1,'First Floor'),(2,'Second Floor'),(3,'Third Floor '),(4,'Fourth Floor'),(5,'Fifth Floor'),(6,'Sixth Floor');

/*Table structure for table `guests` */

DROP TABLE IF EXISTS `guests`;

CREATE TABLE `guests` (
  `guest_id` int(11) NOT NULL AUTO_INCREMENT,
  `guest_name` varchar(255) DEFAULT NULL,
  `identity_type` enum('KTP','SIM','Pasport') DEFAULT NULL,
  `identity_number` varchar(100) DEFAULT NULL,
  `national` varchar(255) DEFAULT NULL,
  `birth_date` date DEFAULT NULL,
  `guest_address` text,
  `phone_number` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`guest_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

/*Data for the table `guests` */

insert  into `guests`(`guest_id`,`guest_name`,`identity_type`,`identity_number`,`national`,`birth_date`,`guest_address`,`phone_number`,`email`) values (1,'Andi Lewis Pratama','KTP','123456789','Indonesia',NULL,NULL,NULL,NULL);

/*Table structure for table `night_audits` */

DROP TABLE IF EXISTS `night_audits`;

CREATE TABLE `night_audits` (
  `night_audit_id` int(11) NOT NULL AUTO_INCREMENT,
  `night_audit_date` date DEFAULT NULL,
  `night_audit_time` datetime DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`night_audit_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

/*Data for the table `night_audits` */

insert  into `night_audits`(`night_audit_id`,`night_audit_date`,`night_audit_time`,`user_id`) values (1,'2021-12-04','2021-12-04 08:03:40',4),(2,'2021-12-05','2021-12-04 08:45:55',4);

/*Table structure for table `payment_histories` */

DROP TABLE IF EXISTS `payment_histories`;

CREATE TABLE `payment_histories` (
  `payment_history_id` int(11) NOT NULL AUTO_INCREMENT,
  `reservation_id` int(11) DEFAULT NULL,
  `payment_id` int(11) DEFAULT NULL,
  `payment_date` date DEFAULT NULL,
  `payment_number` varchar(255) DEFAULT NULL,
  `payment_type` enum('deposit','onstay','refund','remaining') DEFAULT NULL,
  `payment_desciption` text,
  `total_payment` bigint(20) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`payment_history_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `payment_histories` */

/*Table structure for table `payments` */

DROP TABLE IF EXISTS `payments`;

CREATE TABLE `payments` (
  `payment_id` int(11) NOT NULL AUTO_INCREMENT,
  `payment_name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`payment_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

/*Data for the table `payments` */

insert  into `payments`(`payment_id`,`payment_name`) values (1,'Cash'),(2,'Credit Card'),(3,'CL');

/*Table structure for table `requests` */

DROP TABLE IF EXISTS `requests`;

CREATE TABLE `requests` (
  `request_id` int(11) NOT NULL AUTO_INCREMENT,
  `request_name` varchar(255) DEFAULT NULL,
  `request_price` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`request_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

/*Data for the table `requests` */

insert  into `requests`(`request_id`,`request_name`,`request_price`) values (1,'Extra Bed',150000),(2,'Minuman Aqua Sedang',50000);

/*Table structure for table `reservation_histories` */

DROP TABLE IF EXISTS `reservation_histories`;

CREATE TABLE `reservation_histories` (
  `reservation_history_id` int(11) NOT NULL AUTO_INCREMENT,
  `reservation_id` int(11) DEFAULT NULL,
  `reservation_status` enum('Reservation','Stay','Finished','Cancelled') DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`reservation_history_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `reservation_histories` */

/*Table structure for table `reservations` */

DROP TABLE IF EXISTS `reservations`;

CREATE TABLE `reservations` (
  `reservation_id` int(11) NOT NULL AUTO_INCREMENT,
  `reservation_number` varchar(255) DEFAULT NULL,
  `segment_id` int(11) DEFAULT NULL,
  `guest_id` int(11) DEFAULT NULL,
  `adult_guest` int(11) DEFAULT NULL,
  `child_guest` int(11) DEFAULT NULL,
  `checkin_schedule` date DEFAULT NULL,
  `checkout_schedule` date DEFAULT NULL,
  `checkin_time` datetime DEFAULT NULL,
  `checkout_time` datetime DEFAULT NULL,
  `deposit` bigint(20) DEFAULT NULL,
  `bill_number` varchar(255) DEFAULT NULL,
  `receipt_type` enum('bill','invoice') DEFAULT NULL,
  `payment_id` int(11) DEFAULT NULL,
  `reservation_status` enum('Reservation','Stay','Finished','Cancelled') DEFAULT NULL,
  PRIMARY KEY (`reservation_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `reservations` */

/*Table structure for table `room_change_histories` */

DROP TABLE IF EXISTS `room_change_histories`;

CREATE TABLE `room_change_histories` (
  `room_change_history_id` int(11) NOT NULL AUTO_INCREMENT,
  `room_reservation_id` int(11) DEFAULT NULL,
  `room_change_type` enum('switch room','change price') DEFAULT NULL,
  `room_change_date` date DEFAULT NULL,
  `room_rate_id` int(11) DEFAULT NULL,
  `room_id` int(11) DEFAULT NULL,
  `room_price` bigint(20) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`room_change_history_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `room_change_histories` */

/*Table structure for table `room_plans` */

DROP TABLE IF EXISTS `room_plans`;

CREATE TABLE `room_plans` (
  `room_plan_id` int(11) NOT NULL AUTO_INCREMENT,
  `room_plan_name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`room_plan_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

/*Data for the table `room_plans` */

insert  into `room_plans`(`room_plan_id`,`room_plan_name`) values (1,'Public Rates');

/*Table structure for table `room_rates` */

DROP TABLE IF EXISTS `room_rates`;

CREATE TABLE `room_rates` (
  `room_rate_id` int(11) NOT NULL AUTO_INCREMENT,
  `room_type_id` int(11) DEFAULT NULL,
  `room_plan_id` int(11) DEFAULT NULL,
  `session_id` int(11) DEFAULT NULL,
  `room_price` bigint(11) DEFAULT NULL,
  PRIMARY KEY (`room_rate_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;

/*Data for the table `room_rates` */

insert  into `room_rates`(`room_rate_id`,`room_type_id`,`room_plan_id`,`session_id`,`room_price`) values (1,1,1,NULL,258000),(2,2,1,NULL,258000),(3,3,1,NULL,445000),(4,4,1,NULL,528000),(5,5,1,NULL,528000),(6,6,1,NULL,750000),(7,7,1,NULL,750000),(8,8,1,NULL,888000),(9,9,1,NULL,1488000),(10,10,1,NULL,3045000);

/*Table structure for table `room_reservation_histories` */

DROP TABLE IF EXISTS `room_reservation_histories`;

CREATE TABLE `room_reservation_histories` (
  `room_reservation_history_id` int(11) NOT NULL AUTO_INCREMENT,
  `room_reservation_id` int(11) DEFAULT NULL,
  `reservation_status` enum('Check-In','Check-Out') DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`room_reservation_history_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `room_reservation_histories` */

/*Table structure for table `room_reservations` */

DROP TABLE IF EXISTS `room_reservations`;

CREATE TABLE `room_reservations` (
  `room_reservation_id` int(11) NOT NULL AUTO_INCREMENT,
  `reservation_id` int(11) DEFAULT NULL,
  `room_rate_id` int(11) DEFAULT NULL,
  `room_id` int(11) DEFAULT NULL,
  `room_price` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`room_reservation_id`)
) ENGINE=InnoDB AUTO_INCREMENT=71 DEFAULT CHARSET=latin1;

/*Data for the table `room_reservations` */

/*Table structure for table `room_types` */

DROP TABLE IF EXISTS `room_types`;

CREATE TABLE `room_types` (
  `room_type_id` int(11) NOT NULL AUTO_INCREMENT,
  `room_type_name` varchar(150) DEFAULT NULL,
  PRIMARY KEY (`room_type_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;

/*Data for the table `room_types` */

insert  into `room_types`(`room_type_id`,`room_type_name`) values (1,'Standar King'),(2,'Standar Twin'),(3,'J.Superior King'),(4,'Superior King'),(5,'Superior Twin'),(6,'Deluxe King'),(7,'Deluxe Twin'),(8,'Grand Deluxe'),(9,'Suite'),(10,'President Suite');

/*Table structure for table `rooms` */

DROP TABLE IF EXISTS `rooms`;

CREATE TABLE `rooms` (
  `room_id` int(11) NOT NULL AUTO_INCREMENT,
  `floor_id` int(11) DEFAULT NULL,
  `room_number` varchar(15) DEFAULT NULL,
  `room_type_id` int(11) DEFAULT NULL,
  `room_status` enum('VR','VC','VD','OD','OC','OO') DEFAULT NULL,
  PRIMARY KEY (`room_id`)
) ENGINE=InnoDB AUTO_INCREMENT=157 DEFAULT CHARSET=latin1;

/*Data for the table `rooms` */

insert  into `rooms`(`room_id`,`floor_id`,`room_number`,`room_type_id`,`room_status`) values (1,1,'101',1,'VR'),(2,1,'103',1,'VR'),(3,1,'105',1,'VR'),(4,1,'107',1,'VR'),(5,1,'109',1,'VR'),(6,1,'111',1,'VR'),(7,1,'115',1,'VR'),(8,1,'117',1,'VR'),(9,1,'119',1,'VR'),(10,1,'102',2,'VR'),(11,1,'104',2,'VR'),(12,1,'108',2,'VR'),(13,1,'110',2,'VR'),(14,1,'112',2,'VR'),(15,1,'114',2,'VR'),(16,1,'116',2,'VR'),(17,1,'118',2,'VR'),(18,1,'106',3,'VR'),(19,1,'121',3,'VR'),(20,1,'123',3,'VR'),(21,2,'228',4,'VR'),(22,2,'230',4,'VR'),(23,2,'232',4,'VR'),(24,2,'234',4,'VR'),(25,2,'236',4,'VR'),(26,2,'239',4,'VR'),(27,2,'243',4,'VR'),(28,2,'244',4,'VR'),(29,2,'249',4,'VR'),(30,2,'250',4,'VR'),(31,2,'251',4,'VR'),(32,2,'227',5,'VR'),(33,2,'233',5,'VR'),(34,2,'235',5,'VR'),(35,2,'237',5,'VR'),(36,2,'238',5,'VR'),(37,2,'240',5,'VR'),(38,2,'241',5,'VR'),(39,2,'242',5,'VR'),(40,2,'245',5,'VR'),(41,2,'246',5,'VR'),(42,2,'247',5,'VR'),(43,2,'248',5,'VR'),(44,2,'252',5,'VR'),(45,2,'253',5,'VR'),(46,2,'254',5,'VR'),(47,2,'256',5,'VR'),(48,2,'201',6,'VR'),(49,2,'202',6,'VR'),(50,2,'204',6,'VR'),(51,2,'205',6,'VR'),(52,2,'206',6,'VR'),(53,2,'207',6,'VR'),(54,2,'208',6,'VR'),(55,2,'210',6,'VR'),(56,2,'217',6,'VR'),(57,2,'220',6,'VR'),(58,2,'225',6,'VR'),(59,3,'301',6,'VR'),(60,3,'302',6,'VR'),(61,3,'303',6,'VR'),(62,3,'304',6,'VR'),(63,3,'305',6,'VR'),(64,3,'306',6,'VR'),(65,3,'307',6,'VR'),(66,3,'308',6,'VR'),(67,3,'309',6,'VR'),(68,3,'310',6,'VR'),(69,3,'314',6,'VR'),(70,4,'401',6,'VR'),(71,4,'402',6,'VR'),(72,4,'406',6,'VR'),(73,4,'407',6,'VR'),(74,4,'408',6,'VR'),(75,4,'410',6,'VR'),(76,5,'501',6,'VR'),(77,5,'502',6,'VR'),(78,5,'503',6,'VR'),(79,5,'504',6,'VR'),(80,5,'505',6,'VR'),(81,5,'506',6,'VR'),(82,5,'507',6,'VR'),(83,5,'508',6,'VR'),(84,5,'511',6,'VR'),(85,5,'514',6,'VR'),(86,5,'517',6,'VR'),(87,5,'521',6,'VR'),(88,6,'601',6,'VR'),(89,6,'602',6,'VR'),(90,6,'603',6,'VR'),(91,6,'604',6,'VR'),(92,6,'605',6,'VR'),(93,6,'606',6,'VR'),(94,6,'607',6,'VR'),(95,6,'608',6,'VR'),(96,6,'610',6,'VR'),(97,6,'621',6,'VR'),(98,2,'203',7,'VR'),(99,2,'209',7,'VR'),(100,2,'211',7,'VR'),(101,2,'212',7,'VR'),(102,2,'214',7,'VR'),(103,2,'215',7,'VR'),(104,2,'216',7,'VR'),(105,2,'218',7,'VR'),(106,2,'219',7,'VR'),(107,2,'221',7,'VR'),(108,2,'222',7,'VR'),(109,2,'223',7,'VR'),(110,2,'224',7,'VR'),(111,3,'311',7,'VR'),(112,3,'312',7,'VR'),(113,3,'315',7,'VR'),(114,3,'316',7,'VR'),(115,3,'317',7,'VR'),(116,3,'318',7,'VR'),(117,3,'319',7,'VR'),(118,3,'320',7,'VR'),(119,3,'321',7,'VR'),(120,3,'322',7,'VR'),(121,3,'324',7,'VR'),(122,4,'403',7,'VR'),(123,4,'404',7,'VR'),(124,4,'405',7,'VR'),(125,4,'409',7,'VR'),(126,4,'411',7,'VR'),(127,4,'412',7,'VR'),(128,4,'414',7,'VR'),(129,4,'415',7,'VR'),(130,4,'416',7,'VR'),(131,4,'417',7,'VR'),(132,4,'418',7,'VR'),(133,4,'420',7,'VR'),(134,5,'509',7,'VR'),(135,5,'510',7,'VR'),(136,5,'512',7,'VR'),(137,5,'515',7,'VR'),(138,5,'519',7,'VR'),(139,5,'522',7,'VR'),(140,5,'524',7,'VR'),(141,6,'609',7,'VR'),(142,6,'611',7,'VR'),(143,6,'615',7,'VR'),(144,6,'617',7,'VR'),(145,6,'619',7,'VR'),(146,4,'419',8,'VR'),(147,4,'421',8,'VR'),(148,4,'422',8,'VR'),(149,4,'424',8,'VR'),(150,5,'516',8,'VR'),(151,5,'518',8,'VR'),(152,5,'520',8,'VR'),(153,2,'226',9,'VR'),(154,6,'616',9,'VR'),(155,6,'618',9,'VR'),(156,6,'614',10,'VR');

/*Table structure for table `segments` */

DROP TABLE IF EXISTS `segments`;

CREATE TABLE `segments` (
  `segment_id` int(11) NOT NULL AUTO_INCREMENT,
  `segment_name` varchar(255) DEFAULT NULL,
  `segment_type` enum('tentative','guaranted','non-guaranted') DEFAULT NULL,
  PRIMARY KEY (`segment_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

/*Data for the table `segments` */

insert  into `segments`(`segment_id`,`segment_name`,`segment_type`) values (1,'Walk In','tentative'),(2,'Phone','guaranted'),(3,'Walk In','non-guaranted');

/*Table structure for table `sessions` */

DROP TABLE IF EXISTS `sessions`;

CREATE TABLE `sessions` (
  `session_id` int(11) NOT NULL AUTO_INCREMENT,
  `session_name` varchar(255) DEFAULT NULL,
  `start_session` date DEFAULT NULL,
  `end_session` date DEFAULT NULL,
  PRIMARY KEY (`session_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `sessions` */

/*Table structure for table `shifts` */

DROP TABLE IF EXISTS `shifts`;

CREATE TABLE `shifts` (
  `shift_id` int(11) NOT NULL AUTO_INCREMENT,
  `shift_name` varchar(255) DEFAULT NULL,
  `start_shift` time DEFAULT NULL,
  `end_shift` time DEFAULT NULL,
  PRIMARY KEY (`shift_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

/*Data for the table `shifts` */

insert  into `shifts`(`shift_id`,`shift_name`,`start_shift`,`end_shift`) values (1,'Pagi','00:00:00','18:00:00'),(2,'Malam','18:00:00','00:00:00');

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_fullname` varchar(255) DEFAULT NULL,
  `user_photo` text,
  `username` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `user_access` enum('admin','housekeeping','frontoffice','nightaudit') DEFAULT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

/*Data for the table `users` */

insert  into `users`(`user_id`,`user_fullname`,`user_photo`,`username`,`password`,`user_access`) values (1,'admin','789290_penguin_512x512.png','admin','admin','admin'),(2,'frontoffice',NULL,'user1','user1','frontoffice'),(3,'housekeeping',NULL,'user2','user2','housekeeping'),(4,'nightaudit',NULL,'user3','user3','nightaudit');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
