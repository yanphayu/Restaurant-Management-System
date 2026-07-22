
<?php 

$username = "root";
$password = "1234";

$dbname = "rms_mid";
$host   = "localhost";

$dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,      
    PDO::ATTR_EMULATE_PREPARES   => false,                 
];

$pdo = null;

try 
{
    $pdo = new PDO($dsn,$username,$password,$options);
} 
catch (PDOException $e) 
{
    error_log($e->getMessage()); 
    die("Database connection error. Please try again later."); 
}

?>