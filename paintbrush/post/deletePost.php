<?php
/**
 * Created by PhpStorm.
 * User: rtorok
 * Date: 9/13/18
 * Time: 11:53 PM
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

$id = parseInt($_POST['id']);
$username = $_POST['username'];
$password = $_POST['password'];
$classId = parseInt($_POST['classId']);
$classItemId = parseInt($_POST['classItemId']);
$postId = parseInt($_POST['postId']);

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
        //add post to database
        $stmt = $connection->prepare("DELETE FROM posts WHERE classId = ? AND classItemId = ? AND postId = ? AND userId = ?");
        $stmt->bind_param('iii', $classId, $classItemId, $postId, $id);
        $stmt->execute();
        $error = $connection->error;
        if (strlen($error) == 0)
            echo "done";
        else echo $error;
    }
}

if (!$atleastone) { //since the id is a primary key, there should only be 0 or 1 matches.
    echo "invalid input";
}

function escape($string) {
    $string = str_replace(";", "\\;", $string);
    $string = str_replace("\\", "\\\\", $string);
    return $string;
}

function parseInt($string) {
    return is_numeric($string) ? (int) $string : 0;
}
