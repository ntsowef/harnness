<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
include_once 'connect.php';
$menu_code =  $_POST['id'];

$sql = "delete from menu where menu_code=$menu_code";

echo $sql;

$result=mysql_query($sql) or die('Failed to insert premium_services'.mysql_error());