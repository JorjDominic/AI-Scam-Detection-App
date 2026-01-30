<?php
/**
 * Database Connection File
 * Using PDO (Industry Standard)
 */

declare(strict_types=1);

// 🔐 Database credentials
$DB_HOST = 'localhost';
$DB_NAME = 'aiscamdb';
$DB_USER = 'root';
$DB_PASS = ''; // default XAMPP empty

// 🧠 PDO options (BEST PRACTICE)
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // throw errors
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // associative arrays
    PDO::ATTR_EMULATE_PREPARES   => false,                   // real prepared statements
];

try {
$pdo = new PDO(
    "mysql:host=localhost;port=3307;dbname=aiscamdb;charset=utf8mb4",
    "root",
    "",
    $options
);
} catch (PDOException $e) {
    // ❌ NEVER expose DB error details in production
    error_log($e->getMessage());
    die('Database connection failed.');
}
