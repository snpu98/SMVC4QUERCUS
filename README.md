SMVC4QUERCUS
============

Semi MVC For QUERCUS


Author : Jin-Hwan, Kim

License : GPL v3


This MVC framework include example.

You must create next on mysql and edit WebContent/_lib/config.php for running example.


	CREATE DATABASE `db_test`

	USE `db_test`;

	DROP TABLE IF EXISTS `t_test`;

	CREATE TABLE `t_test` (
  	`f_idx` int(10) unsigned NOT NULL AUTO_INCREMENT,
  	`f_key` varchar(1024) NOT NULL,
  	`f_val` varchar(1024) NOT NULL,
  	PRIMARY KEY (`f_idx`)
	) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
	


I hope this help you.
