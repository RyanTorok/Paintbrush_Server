<?php
/**
 * Created by PhpStorm.
 * User: rtorok
 * Date: 7/5/18
 * Time: 9:18 PM
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

$id = parseInt($_POST['id']);
$username = $_POST['username'];
$password = $_POST['password'];
$classId = parseInt($_POST['classId']);
$classItemId = parseInt($_POST['classItemId']);
$title = $_POST['title'];
$text = $_POST['text'];
$visibility = parseInt($_POST['visibility']);
$type = $_POST['type'];
$parent = $_POST['parent'];

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
        $stmt = $connection->prepare("INSERT INTO posts VALUE (null, ?, ?, ?, ?, ?, ?, ?, ?, null, null)");
        $stmt->bind_param('iiissisi', $classId,$classItemId, $id, $title, $text, $visibility, $type, $parent);
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
