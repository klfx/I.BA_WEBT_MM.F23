CREATE DATABASE `delivery`;

USE `delivery`;

CREATE TABLE `delivery` (
  `tracking_nr` varchar(30) NOT NULL UNIQUE,
  `delivery_option` int(1) NOT NULL,
  `last_change_date` datetime
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `delivery` (`tracking_nr`, `delivery_option`, `last_change_date`) VALUES
('990012345612345678', 1, NULL),
('990042042042042042', 1, NULL),
('990069696969696969', 1, NULL),
('990077777777777777', 1, NULL);