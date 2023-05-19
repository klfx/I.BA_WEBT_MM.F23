CREATE DATABASE `delivery`;

USE `delivery`;

CREATE TABLE `delivery` (
  `tracking_nr` varchar(30) NOT NULL UNIQUE,
  `delivery_option` int(1) NOT NULL,
  `last_change_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;