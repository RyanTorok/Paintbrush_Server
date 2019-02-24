<?php
/**
 * Created by PhpStorm.
 * User: rtorok
 * Date: 6/18/18
 * Time: 2:01 PM
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

$username = $_POST['username'];
$password = $_POST['password'];
$salt = $_POST['salt'];
$first = $_POST['first'];
$last = $_POST['last'];
$email = $_POST['email'];
$schoolCode = $_POST['schoolcode'];

$hash = password_hash($password, PASSWORD_DEFAULT);

//create connection
$servername = "localhost:3306";
$connection = new mysqli($servername, 'java', 'B584xha1eM*gFA', 'paintbrush_server');

if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

//check validity of school code if there is one
if (strlen($schoolCode) > 0) {

    $stmt = $connection->prepare("SELECT schoolcode FROM users WHERE schoolcode = ? ;");
    $stmt->bind_param("s", $schoolCode);
    $stmt->execute();

    $exists = false;
    if ($fetch = $stmt->fetch()) {
        $exists = true;
    }

    if (!$exists) {
        die("badSC");
    }
} else {
    $schoolCode = "0";
}


// prepare database insertion
$stmt = $connection->prepare("INSERT INTO users VALUE (null, ?, ?, ?, ?, ?, ?, ?, null );");
$stmt->bind_param("sssssss", $username, $hash, $salt, $first, $last, $email, $schoolCode);
$stmt->execute();
echo $connection->error;
$result = $stmt->fetch();

echo "done";