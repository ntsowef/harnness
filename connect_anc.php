<?php


        $host = "localhost";
        $user = "root";      

        $pswd = "";        
        $database = "anc_conference";
     	@mysql_pconnect($host, $user, $pswd) or die("Couldn't connect to server, the is a problem with your internet ".mysql_error());
        @mysql_select_db($database) or die("Couldn't select $database database! ".mysql_error());
	
?>

