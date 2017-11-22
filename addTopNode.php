<?php


include_once 'connect.php';
$id = $_POST['id'];
    $node_text = $_POST['node_text'];
    $node_description = $_POST['node_description'];
    $menu_code = $_POST['menu_code'];


$sql ="INSERT INTO menu (id, text, menu_description, menu_code, parent_code) values ('','$node_text', '$node_description','$menu_code', '$id' )";

echo $sql;

$result=mysql_query($sql) or die('Failed to insert harness menu'.mysql_error());