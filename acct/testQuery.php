<?php
/**
 * Created by PhpStorm.
 * User: rtorok
 * Date: 6/19/18
 * Time: 10:41 PM
 */
error_reporting(E_ALL);
ini_set('display_errors', 1);


//create connection
$servername = "localhost:3306";
$connection = new mysqli($servername, 'java', 'B584xha1eM*gFA', 'paintbrush_server');

if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

try {
    $bytes = random_bytes(50);
} catch (Exception $e) {
}
echo $bytes . "<br>";

// do a test query
$stmt = $connection->prepare("INSERT INTO users VALUE (null, 'test4', 'pass', ?, 'first', 'last', 'email', '0', null );");
$stmt->bind_param("s", $bytes);
$stmt->execute();

//$stmt = $connection->prepare("SELECT 1,2,3 FROM dual;");
$stmt = $connection->prepare("SELECT salt FROM users WHERE username = 'test4' ;");
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_row();
echo $row[0] . "<br>";

echo $bytes == $row[0];

