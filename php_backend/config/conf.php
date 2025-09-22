

<?php 
$host = 'localhost';
$db = 'u986886379_edudb';
$user = 'u986886379_theta_fornix';
$pass = 'thetaFornix@admin00';
$dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";//Data Source Name tells php how to conn to db
$options = [PDO:: ATTR_ERRMODE => 
PDO:: ERRMODE_EXCEPTION];//just like try catch in js
try {
    $pdo = new PDO($dsn, $user, $pass, $options);//creating new db connection object
} catch (PDOException $e) {
    die("DB Connection failed: " . $e->getMessage());
}
?>