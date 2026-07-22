<?php

$host = "localhost";
$dbname = "rms_mid";
$username = "root";
$password = "Horng160806";

try {

    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $username,
        $password
    );

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch(PDOException $e) {

    die($e->getMessage());
}