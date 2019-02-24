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
$username = $_POST['username'];
$password = $_POST['password'];
$old = $_POST['old'];
$salt = $_POST['salt'];

//create connection
$servername = "localhost:3306";
$connection = new mysqli($servername, 'java', 'B584xha1eM*gFA', 'paintbrush_server');

if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

$stmt = $connection->prepare("SELECT password FROM users WHERE username = ? AND id = ?");
$stmt->bind_param("si", $username, $id);
$stmt->execute();

$allResults = $stmt->get_result();
$atleastone = false;
while($result = $allResults->fetch_row()) {
    $atleastone = true;
    //verify password
    $encryptedPassword = $result[0]; //password
    $match = password_verify($old, $encryptedPassword);
    if ($match) {
        $hash = password_hash($password, PASSWORD_DEFAULT);

        // prepare database update
        $stmt = $connection->prepare("UPDATE users SET password = ?, salt = ? WHERE id = ?");
        $stmt->bind_param("ssi", $hash, $salt, $id);
        $stmt->execute();
        $error = $connection->error;
        if (strlen($error) > 0) {
            echo $error;
        } else {
            echo "done";
        }
    }
}

if (!$atleastone) { //since the id is a primary key, there should only be 0 or 1 matches.
    echo "invalid input";
}

