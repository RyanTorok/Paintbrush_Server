<?php
/**
 * Created by PhpStorm.
 * User: rtorok
 * Date: 7/5/18
 * Time: 4:18 PM
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

$id = (is_numeric($_POST['id']) ? (int)$_POST['id'] : 0);
$username = $_POST['username'];
$password = $_POST['password'];
$classId = (is_numeric($_POST['classId']) ? (int)$_POST['classId'] : 0);

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
        //read posts
        $stmt = $connection->prepare("SELECT * FROM posts WHERE userId = ? AND classId = ?");
        $stmt->bind_param("ii", $id, $classId);
        $stmt->execute();
        $error = $connection->error;
        if (strlen($error) > 0)
            echo $error;
        else {
            $postsResult = $stmt->get_result();
            while ($row = $postsResult->fetch_row()) {
                $postId = $row[0];
                //get likes

                //have I viewed this post?
                $stmt = $connection->prepare("SELECT * FROM views WHERE userId = ? AND postId = postId");
                $viewedThis = true;
                $stmt->bind_param("ii", $id, $postId);
                $stmt->execute();
                if (!$stmt->get_result()->fetch_row())
                    $viewedThis = false;

                //how many views did this post get?
                $stmt = $connection->prepare("SELECT COUNT(userId) AS countViews FROM views WHERE postId = ?");
                $stmt->bind_param("i", $postId);
                $stmt->execute();
                $numViews = $stmt->get_result()->fetch_row()[0];

                //did I give this post a like? (skip if never viewed, because it is impossible to like a post without viewing it)
                $likedThis = false;
                if ($viewedThis) {
                    $stmt = $connection->prepare("SELECT * FROM likes WHERE userId = ? AND postId = postId");
                    $likedThis = true;
                    $stmt->bind_param("ii", $id, $postId);
                    $stmt->execute();
                    if (!$stmt->get_result()->fetch_row())
                        $likedThis = false;
                }

                //how many likes did this post get?
                $stmt = $connection->prepare("SELECT COUNT(userId) AS countLikes FROM likes WHERE postId = ?");
                $stmt->bind_param("i", $postId);
                $stmt->execute();
                $numLikes = $stmt->get_result()->fetch_row()[0];

                $first = "Anonymous";
                $last = "";
                $posterUN = "Anonymous";

                $notAnonymous = $row[7] == "true";
                if ($notAnonymous) {
                    $userId = (is_numeric($row[3]) ? (int) $row[3] : 0);
                    $stmt = $connection-> prepare("SELECT `first`, `last`, username FROM users WHERE id = ?");
                    $stmt->bind_param("i", $userId);
                    $stmt->execute();
                    $posterData = $stmt->get_result();
                    $posterRow = $posterData->fetch_row();
                    $first = $row[0];
                    $last = $row[1];
                    $posterUN = $row[2];
                }

                //encode post
                //not all of these really needed to be escaped, but it's better to be safe
                $postLiteral = escape($row[0]) . ";" . escape($row[1]) . ";" . escape($row[2]) . ";" .
                    escape($row[3]) . ";" . escape($row[4]) . ";" . escape($row[5]) . ";" . escape($row[6]) . ";" .
                    escape($row[7]) . ";" . escape($row[8]) . ";" . escape($row[9]) . ";" . escape($row[10]) . ";" .
                    escape($row[11]) . ";" . escape($numLikes) . ";" . escape($likedThis) . ";" . escape($numViews) . ";" .
                    escape($viewedThis) . ";" . escape($first) . ";" . escape($last) . ";" . escape($posterUN);

                echo base64_encode($postLiteral . "\n");
            }
        }

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