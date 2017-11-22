<?php
session_start();
$username = $_SESSION["username"];
$company = $_SESSION["company"];
$id = $_SESSION["user_id"];
$admin = $_SESSION["admin"];

?>
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <?php
        include "connect.php";
        if(@$_POST['submit']){
             $user = $username;
             $group_name = $_POST['group_name'];
             $filename = $_FILES['file']['name'];
             
             echo $filename. "   FILENAME <br>";
             $fileo =  $_FILES['file']['tmp_name'];//$_FILES["file"]["name"]; //
	     $content = fopen($fileo, "r") or exit("Unable to open file!");
              fclose($fileo);
             $company = $_POST['company'];
             $type='bulk';
             $status='Ok';	
             $table_group = "sms_group";
             if(empty($group_name))
		 {
		  $msg=$msg."All fields must be completed  <br>";
		  $status="False";
		 }
                 if($status=='Ok')
		  {
		   $insert="INSERT into ".$table_group." values('','$company','$group_name','$username',now(),'$content','$type','1')";
		   
                   
                   
                   $result=mysql_query($insert) or die("Could not insert sms_group table ");
                //   echo $result. "<br>";
                   
                   if ($result == 1){
                   $createtable= "CREATE TABLE sms_group_".$group_name." 
                                                        (
                                                      
                                                        msisdn VARCHAR(20)
                                                        
                                                        )";
                  // echo $createtable;
                   
                  $createRes = mysql_query($createtable) or die("could not create sms_group_".$group_name."");
                  
                  
                     if ($createRes){
                         
                         $directory = "_uploads/";
                         
                         if (!file_exists($directory)){
                           mkdir($directory, 0777, true);
                           echo "The directory {$directory} was successfully created."; 
                         }
                         
                         $target_path = $directory. basename( $_FILES['file']['name']);
                         
                         
                        if(move_uploaded_file($_FILES['file']['tmp_name'], $target_path)) {
                         //   echo "The file ".  basename( $_FILES['file']['name']). 
                           // " has been uploaded to " . $target_path."<br>";
                            } else{
                            echo "There was an error uploading the file, please try again!";
                        }
                        // echo "<p> Glad it successfully created the table</p>";
                         
                         
                         $temp = $fileo;

                        //$sqlstatement="LOAD DATA LOCAL INFILE '$target_path' INTO TABLE sms_group_".$group_name." FIELDS TERMINATED BY ',' OPTIONALLY ENCLOSED BY '\"' LINES TERMINATED BY '\r\n' IGNORE 1 LINES";
                       
                         $sqlstatement="LOAD DATA LOCAL INFILE '$target_path' INTO TABLE sms_group_".$group_name." FIELDS TERMINATED BY '' LINES TERMINATED BY '\r\n' ";
                         echo $sqlstatement;
                        mysql_query($sqlstatement) or die(mysql_error());
                     }else{
                         
                     }
                   }
                   
      echo "<font face='Verdana' size='2' color=red>$msg</font><br><input type='button'  value='Retry' onClick='history.go(-1)'>"; 

                   
                  ?>
		     <!--script language = "javascript" style = "text/javascript"> 
			 window.location = "bulkmsgs.php>";									
		    </script-->
                     
                  
		   <?php
		  }  
                  else {
                       {echo "<font face='Verdana' size='2' color=red>$msg</font><br><input type='button'  value='Retry' onClick='history.go(-1)'>"; }

                  }
                 
         }
         else
         {
             ?>
                    
                    <form action="add_bulksms_group.php" method="POST" name="myform" enctype="multipart/form-data">
                    <table cellspacing="0" rules="none" align="center" bgcolor="white" width="400">
                       <tr>
                        <td valign="top" align="center" colspan="3"bgcolor="gray"><b><font size="3">Add SMS GROUP FORM</b></td>
                       </tr>
                       <tr>                   
                       <td valign="top" width="49%" align="left">Group name</td>
                       <td align="left" colspan="2"><input type="text"  name="group_name" size="30"></td>
                       </tr>
		   <tr>
                      <td align="left" valign="top" class = "content">Upload File:</td>
                      <td align="left" valign="top" colspan="2" >
		      <input type="file" size="37"  accept = "accept=&quot;content-type-list" [csv,txt]" name="file" id="file" />
		      </td>
                    </tr>
		     
                    <tr>
                     <td align="center" width="25%"></td><td colspan="2">
                     <input type="hidden" name="username" value="<?php echo $username;?>">
                     <input type="hidden" name="company" value="<?php echo $company;?>">
                     </td>
                     </tr>
                         <tr><td></td>
			   <td align="right" valign="top" width=""><input type="submit" name="submit" value="Save" /> </td>  
			
		     </tr>
                     </table>
                </form> 

                    
        <?php            
         }  
        
        ?>
    </body>
</html>
