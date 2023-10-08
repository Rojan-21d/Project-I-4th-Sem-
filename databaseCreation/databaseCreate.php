<?php
// Database connection configuration
$hostname = 'localhost';
$username = 'root';
$password = '';
$database = 'gantabyaproject';

try {
    // Create a new PDO instance for database connection
    $pdo = new PDO("mysql:host=$hostname", $username, $password);

    // Set PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // SQL code to create and use the database
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$database`");
    $pdo->exec("USE `$database`");

    // SQL code to create `admininfo` table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS `admininfo` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `username` varchar(255) NOT NULL,
            `email` varchar(255) NOT NULL,
            `contact` bigint(10) DEFAULT NULL,
            `password` varchar(255) NOT NULL,
            PRIMARY KEY (`id`),
            UNIQUE KEY `username` (`username`),
            UNIQUE KEY `email` (`email`)
        )
    ");

    // Insert data into `admininfo` table
    $pdo->exec("
        INSERT INTO `admininfo` (`id`, `username`, `email`, `contact`, `password`) VALUES
        (1, 'admin', 'admin@gmail.com', 9800000000, 'admin')
    ");

    // SQL code to create `carrierdetails` table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS `carrierdetails` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `name` varchar(255) NOT NULL,
            `img_srcs` varchar(255) NOT NULL DEFAULT 'img/images/user-regular.png',
            `email` varchar(255) NOT NULL,
            `address` varchar(255) DEFAULT NULL,
            `contact` bigint(10) NOT NULL,
            `password` varchar(255) NOT NULL,
            PRIMARY KEY (`id`),
            UNIQUE KEY `email` (`email`)
        )
    ");

    // SQL code to create `consignordetails` table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS `consignordetails` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `name` varchar(255) NOT NULL,
            `img_srcs` varchar(255) NOT NULL DEFAULT 'img/images/user-regular.png',
            `email` varchar(255) NOT NULL,
            `contact` bigint(10) NOT NULL,
            `address` varchar(255) DEFAULT NULL,
            `password` varchar(255) NOT NULL,
            PRIMARY KEY (`id`),
            UNIQUE KEY `email` (`email`)
        )
    ");

    // SQL code to create `loaddetails` table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS `loaddetails` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `name` varchar(255) DEFAULT NULL,
            `dateofpost` datetime DEFAULT current_timestamp(),
            `origin` varchar(255) DEFAULT NULL,
            `destination` varchar(255) DEFAULT NULL,
            `distance` int(11) DEFAULT NULL,
            `description` varchar(500) DEFAULT NULL,
            `weight` int(11) DEFAULT NULL,
            `status` varchar(55) DEFAULT 'notBooked',
            `consignor_id` int(11) DEFAULT NULL,
            `img_srcs` varchar(124) DEFAULT NULL,
            PRIMARY KEY (`id`),
            KEY `consignor_id` (`consignor_id`),
            CONSTRAINT `loaddetails_ibfk_1` FOREIGN KEY (`consignor_id`) REFERENCES `consignordetails` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
        )
    ");

    // SQL code to create `shipment` table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS `shipment` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `load_id` int(11) NOT NULL,
            `consignor_id` int(11) NOT NULL,
            `carrier_id` int(11) NOT NULL,
            PRIMARY KEY (`id`),
            KEY `carrier_id` (`carrier_id`),
            KEY `load_id` (`load_id`),
            CONSTRAINT `shipment_ibfk_1` FOREIGN KEY (`carrier_id`) REFERENCES `carrierdetails` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
            CONSTRAINT `shipment_ibfk_2` FOREIGN KEY (`consignor_id`) REFERENCES `loaddetails` (`consignor_id`) ON DELETE CASCADE ON UPDATE CASCADE,
            CONSTRAINT `shipment_ibfk_3` FOREIGN KEY (`load_id`) REFERENCES `loaddetails` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
        )
    ");

    //For password reset
    $pdo->exec("
    ALTER TABLE `carrierdetails` 
    ADD `reset_otp_hash` VARCHAR(64) NULL DEFAULT NULL AFTER `password`, 
    ADD `reset_otp_expires_at` DATETIME NULL DEFAULT NULL AFTER `reset_otp_hash`, 
    ADD UNIQUE (`reset_otp_hash`); 
    ");

    $pdo->exec("
    ALTER TABLE `consignordetails` 
    ADD `reset_otp_hash` VARCHAR(64) NULL DEFAULT NULL AFTER `password`, 
    ADD `reset_otp_expires_at` DATETIME NULL DEFAULT NULL AFTER `reset_otp_hash`, 
    ADD UNIQUE (`reset_otp_hash`); 
    ");

    $pdo->exec("
    ALTER TABLE `admininfo` 
    ADD `reset_otp_hash` VARCHAR(64) NULL DEFAULT NULL AFTER `password`, 
    ADD `reset_otp_expires_at` DATETIME NULL DEFAULT NULL AFTER `reset_otp_hash`, 
    ADD UNIQUE (`reset_otp_hash`); 
    ");


    echo "Database and tables created successfully.";
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>
