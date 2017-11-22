<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <?php
        
        $str = "12,bcp,1234";
$count = substr_count( $str,",");        

        echo  " string ".$str."  count == ".$count;
       $arr = explode(",", $str);
      echo '<br>   '.$arr[0]."  party ".$arr[1].'   votes '.$arr[2]
?>
        
        
    </body>
</html>
