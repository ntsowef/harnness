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
       require_once 'config2.php';
      if(!($conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME)))
        {
	//file_put_contents($log, chr(10).'-- Database error: '.mysqli_error(), FILE_APPEND);
	return 1;
        }//end if

               $query  = "SELECT msisdn from lesotho_blacklist";
               if(!($results = $conn->query($query)))
		{
	//		file_put_contents($log, chr(10).chr(9).'-- Database error: '.$conn->error().chr(10), FILE_APPEND);
			return 1;
		}//end if
                 $blacklist = array();       
                while( $row =  mysqli_fetch_array($results)){
                  $blacklist[] = $row['msisdn']; // Inside while loop
                //  echo $row;
                }
                
                 print_r ($blacklist);     
                 echo "<br>";
                 
                 $key = in_array('+26650534440', $blacklist);
                 
                 echo " Key ".$key;
                 
                 if (!in_array('+27796222802', $blacklist)){
                     echo "Was not found in the array  ";               
                 }
                                
 
//echo $sql;
?>
    </body>
</html>