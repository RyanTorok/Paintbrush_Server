<?php
/**
 * Created by PhpStorm.
 * User: rtorok
 * Date: 6/21/18
 * Time: 1:26 PM
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

$id = (is_numeric($_POST['id']) ? (int)$_POST['id'] : 0);
$serFile = $_POST['serfile'];

//create connection
$servername = "localhost:3306";
$connection = new mysqli($servername, 'java', 'B584xha1eM*gFA', 'paintbrush_server');

if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}


// prepare database update
$stmt = $connection->prepare("UPDATE users SET userData = ? WHERE id = ?");
$stmt->bind_param("si", $serFile, $id);
$stmt->execute();
$error = $connection->error;
if (strlen($error) > 0) {
    echo $error . "\n";
}
echo "done";
