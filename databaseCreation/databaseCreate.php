<?php
// Database connection configuration
$hostname = 'localhost';
$username = 'root';
$password = '';
$database = 'gantabyaproject';

// Create a new PDO instance for database connection
$pdo = new PDO("mysql:host=$hostname", $username, $password);

// SQL code to create and use the database
$pdo->exec("CREATE DATABASE IF NOT EXISTS `$database`");
$pdo->exec("USE `$database`");

// SQL code to create tables
$sql = "
    CREATE TABLE IF NOT EXISTS `admininfo` (
        `id` int(11) NOT NULL,
        `username` varchar(20) NOT NULL,
        `email` varchar(30) NOT NULL,
        `contact` bigint(10) DEFAULT NULL,
        `password` varchar(40) NOT NULL,
        PRIMARY KEY (`id`),
        UNIQUE KEY `username` (`username`),
        UNIQUE KEY `email` (`email`)
    );

    CREATE TABLE IF NOT EXISTS `carrierdetails` (
        -- table structure ...
        PRIMARY KEY (`id`),
        UNIQUE KEY `email` (`email`)
    );

    CREATE TABLE IF NOT EXISTS `consignordetails` (
        -- table structure ...
        PRIMARY KEY (`id`),
        UNIQUE KEY `email` (`email`)
    );

    CREATE TABLE IF NOT EXISTS `loaddetails` (
        -- table structure ...
        PRIMARY KEY (`id`),
        KEY `consignor_id` (`consignor_id`),
        CONSTRAINT `loaddetails_ibfk_1` FOREIGN KEY (`consignor_id`) REFERENCES `consignordetails` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
    );

    CREATE TABLE IF NOT EXISTS `shipment` (
        -- table structure ...
        PRIMARY KEY (`id`),
        KEY `carrier_id` (`carrier_id`),
        KEY `load_id` (`load_id`),
        CONSTRAINT `shipment_ibfk_1` FOREIGN KEY (`carrier_id`) REFERENCES `carrierdetails` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
        CONSTRAINT `shipment_ibfk_2` FOREIGN KEY (`consignor_id`) REFERENCES `loaddetails` (`consignor_id`) ON DELETE CASCADE ON UPDATE CASCADE,
        CONSTRAINT `shipment_ibfk_3` FOREIGN KEY (`load_id`) REFERENCES `loaddetails` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
    );

    -- Insert data into tables
    INSERT INTO `admininfo` (`id`, `username`, `email`, `contact`, `password`) VALUES
        (1, 'admin', 'admin@gmail.com', 9802528768, 'admin');

    -- ... (insert data into other tables)
";

// Execute the SQL code
$pdo->exec($sql);

echo "Database and tables created successfully.";
