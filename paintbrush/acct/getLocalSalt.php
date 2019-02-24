<?php
/**
 * Created by PhpStorm.
 * User: rtorok
 * Date: 6/20/18
 * Time: 1:58 PM
 */


error_reporting(E_ALL);
ini_set('display_errors', 1);

$username = $_POST['username'];

//create connection
$servername = "localhost:3306";
$connection = new mysqli($servername, 'java', 'B584xha1eM*gFA', 'paintbrush_server');

if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

// get the user's salt

$stmt = $connection->prepare("SELECT salt FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$atLeastOne = false;
while ($row = $result->fetch_row()) {
    $atLeastOne = true;
    echo $row[0] . "\n";
}

if (!$atLeastOne) {
    //output a random string of characters, to hide that the username doesn't exist
    try {
        echo base64_encode(random_bytes(random_int(45, 65))) . "\n";
    } catch (Exception $e) {
    }
}

echo $connection->error;

