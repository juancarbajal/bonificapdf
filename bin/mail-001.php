<?php
require_once(dirname(__FILE__) . '/../lib/Mail.php');
$connection = new PDO("mysql:dbname=$db;host=$host", $username, $password);
$stmt = $connection->prepare($sql);
$stmt->execute($args);
$rows = $stmt->fetchAll();
foreach ($rows as $rs) {

}
