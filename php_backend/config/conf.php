

<?php 
$host = 'localhost';
$db = 'EduDB';
$user = 'root';
$pass = '';
$dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";//Data Source Name tells php how to conn to db
$options = [PDO:: ATTR_ERRMODE => 
PDO:: ERRMODE_EXCEPTION];//just like try catch in js
$pdo = new PDO($dsn, $user, $pass, $options);//creating new db connection object
?>