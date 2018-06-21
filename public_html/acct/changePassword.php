<?php
/**
 * Created by PhpStorm.
 * User: rtorok
 * Date: 6/20/18
 * Time: 3:19 PM
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

$id = (is_numeric($_POST['id']) ? (int)$_POST['id'] : 0);
$password = $_POST['password'];
$salt = $_POST['salt'];

$hash = password_hash($password, PASSWORD_DEFAULT);

//create connection
$servername = "localhost:3306";
$connection = new mysqli($servername, 'java', 'B584xha1eM*gFA', 'paintbrush_server');

if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

//echo $id;
// prepare database update
$stmt = $connection->prepare("UPDATE users SET password = ?, salt = ? WHERE id = ?");
$stmt->bind_param("ssi", $hash, $salt, $id);
$stmt->execute();
$error = $connection->error;
if (strlen($error) > 0) {
    echo $error;
} else {
    if ($id == 0) {
        echo "invalid id";
    } else {
        echo "done";
    }
}
