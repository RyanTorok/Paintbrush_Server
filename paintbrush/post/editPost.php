<?php
/**
 * Created by PhpStorm.
 * User: rtorok
 * Date: 7/5/18
 * Time: 9:56 PM
 */

$id = parseInt($_POST['id']);
$username = $_POST['username'];
$password = $_POST['password'];
$postId = parseInt($_POST['postId']);
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
        $stmt = $connection->prepare("UPDATE posts SET title = ?, text = ?, visibility = ?, `type` = ?, responseTo = ? WHERE id = ? AND userId = ?");
        $stmt->bind_param("ssisiii", $title, $text, $visibility, $type, $parent, $postId, $id);
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

function parseInt($string) {
    return is_numeric($string) ? (int) $string : 0;
}
