<?php
/**
 * Created by PhpStorm.
 * User: rtorok
 * Date: 9/28/18
 * Time: 9:44 AM
 */

$id = parseInt($_POST['id']);
$username = $_POST['username'];
$password = $_POST['password'];

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
    //verify password
    $encryptedPassword = $result[0]; //password
    $match = password_verify($old, $encryptedPassword);
    if ($match) {
        $atleastone = true;
    }
}

if (!$atleastone) { //since the id is a primary key, there should only be 0 or 1 matches.
    echo "authentication failure";
    exit(-1);
}

function parseInt($string) {
    return is_numeric($string) ? (int) $string : 0;
}
