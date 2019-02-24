<?php
/**
 * Created by PhpStorm.
 * User: rtorok
 * Date: 9/28/18
 * Time: 9:36 AM
 */


require '../acct/authenticate.php';

$token = $_POST['token'];
$typeFilters = parseInt($_POST['typeFilters']);

if ($typeFilters == 0) {
    echo("done");
    exit(1);
}

if ($typeFilters == 63) {
    $query = "SELECT `type`, `target`, `relevance` FROM searches WHERE query = ?";
} else {
    $query = "SELECT `type`, `target`, `relevance` FROM searches WHERE query = ? AND (0";
    $query = union($query, $typeFilters, 'classItem', 1);
    $query = union($query, $typeFilters, 'module', 2);
    $query = union($query, $typeFilters, 'post', 3);
    $query = union($query, $typeFilters, 'class', 4);
    $query = union($query, $typeFilters, 'organization', 5);
    $query .= ");";
}

$stmt = $connection->prepare($query);
$stmt->bind_param('s',$token);
$stmt->execute();

$allResults = $stmt->get_result();
while($row = $allResults->fetch_row()) {
    $type = $row[0];
    $target = $row[1];
    $relevance = $row[2];
    echo (new Identifier($type, $target, $connection))->toString($relevance) . "<br>";
}

echo "done";

function isNthBitSet($src, $n)
{
    return ($src & (1 << ($n - 1)));
}

function union($query, $typeFilters, $typeName, $n)
{
    if (isNthBitSet($typeFilters, $n)) {
        $query .= " OR type = " . $typeName;
    }
    return $query;
}