<?php


        $host = "196.37.186.21";
        $user = "root";      

        $pswd = "n4u2cc";        
        $database = "sms_data";
     	@mysql_pconnect($host, $user, $pswd) or die("Couldn't connect to server, the is a problem with your internet ".mysql_error());
        @mysql_select_db($database) or die("Couldn't select $database database! ".mysql_error());
	
?>

