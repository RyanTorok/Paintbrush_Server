<?php
/**
 * Created by PhpStorm.
 * User: rtorok
 * Date: 6/18/18
 * Time: 4:36 PM
 */


error_reporting(E_ALL);
ini_set('display_errors', 1);

$username = $_POST['username'];
$passwordAttempt = $_POST['password'];
$needFile = $_POST['needfile'];

//create connection
$servername = 'localhost:3306';
$connection = new mysqli($servername, 'java', 'B584xha1eM*gFA', 'paintbrush_server');

if ($connection->connect_error) {
    die("Connection failed!");
}

$stmt = $connection->prepare("SELECT * FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();


$allResults = $stmt->get_result();
while($result = $allResults->fetch_row()) {
    //verify password
    $encryptedPassword = $result[2]; //password
    $match = password_verify($passwordAttempt, $encryptedPassword);
    if ($match) {
        echo $result[0] . " "; //id
        //get the rest of the user details
        if ($needFile == "true") {
            $serFile = $result[8];
            if (strlen($serFile) == 0)
                echo "true";
            else
                echo $serFile;
        } else {
            echo "true";
        }
        echo "\n";
    }
}