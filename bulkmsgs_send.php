 <?php
	$id=$_POST['id'];
	$logname = $_REQUEST['user'];
	$username = $_REQUEST['user'];
	$company = $_REQUEST['compan'];
	$admin= $_POST['admin'];
	$credits= $_REQUEST['credits'];
	$creditid= $_REQUEST['creditid'];
	$SendSMG = $_POST['Message'];
	$message=$SendSMG;
	
	if (strlen(trim($SendSMG)) == 0)
	{
	?>
	<script language = "javascript" type = "text/javascript"> 
	  	alert("Please enter your message");
		window.href = "bulkmsgs.php";
	</script>
	<?php
	}
/*
	
*/
	include "connect.php";
	$DateSend=date('Y'.'-'.'m'.'-'.'d'.' '.'h'.':'.'m'.':'.'s');
	$blnSent = false;
	echo $_FILE['file']['tmp_name'];
	if (strlen($SendSMG) == 0)
	{
		?>
		<script language = "javascript" type = "text/javascript"> 
			alert("Please enter your message");
			window.href = "bulkmsgs.php";
		</script>
		<?php
	}
if (isset($_POST['check'])){
	if($credits > 0){

          $directory = "_uploads/";
                         
          if (!file_exists($directory)){
            mkdir($directory, 0777, true);
       
          }

          $target_path = $directory. basename( $_FILES['file']['name']);
           if(move_uploaded_file($_FILES['file']['tmp_name'], $target_path)) {

                } else{
                echo "There was an error uploading the file, please try again!";
            }







        //$fileo =  $_FILES['file']['tmp_name'];//$_FILES["file"]["name"]; //
	$file = fopen($target_path, "r") or exit("Unable to open file!");
	//Output a line of the file until the end is reached
	while(!feof($file))
	{
	$strLine = fgets($file);
	$x=strstr($strLine,",");
          if(substr($x,0,1)==",")
          {
	  $arr = (explode(",",$strLine));
          }
	  else
	  {
          $num=str_replace("\n",",",$strLine);
          $num=str_replace("\r",",",$strLine);
          $arr = (explode(",",$num));
	  }
		$msgcount=0;
		$numbers='';
		foreach ($arr as $value)
		{
		$msgcount++;		
		$celnumber=trim($value);	
		if(strlen($celnumber)==8)
		 {
		  $celnumber="+266".$celnumber;		 
		 }
		//echo"celnumber ".$celnumber;
		if($credits >= $msgcount and !empty($celnumber) and $celnumber >0)
		{
		
                        $message=str_replace("'"," ",$message);
                        $query="INSERT INTO bulkpush VALUES('','$message','$company','','$celnumber','$username','$DateSend')";
			//echo $query;
			$result = mysql_query($query) or die("Couldn't Insert bulkpush 2 ".mysql_get_last_message());
			
                        
                        $query1="INSERT INTO bulkmessages(id, message, msisdn, queued, message_date, company, username) VALUES('','$message','$celnumber', 'Q',NOW(),'$company','$username')";
			//echo $query1;
			$result = mysql_query($query1) or die("Couldn't Insert bulkpush 2 ".mysql_get_last_message());
			
						
			$credits=$credits-1;
			$sqlu="update bulk_credits set credits='$credits' where id='$creditid'";
                        
                       // echo " <br> ".$sqlu;
			$resu=mysql_query($sqlu) or die('Unable to update credits '.mysql_get_last_message());
			//echo $credits;
			
		}
		
	     }	// for loop
        } // end of while loop
        } // if credits > 0
	else
	{
		$blnSent == true;
		   ?>
			  <script language = "javascript" type = "text/javascript"> 
			    alert("Credits are finished. Buy some more credits keep posting messages. Processing record: <?php echo $value;?>");
			    window.href = "bulkmsgs.php";
			  </script>
		    <?php
	}
}
else
{ 
    include "connect.php";	
    $strLine = $_POST['Phone_numbers'];
    $Phone_numbers = $_POST['Phone_numbers'];
    $company = $_POST['compan'];
    $admin= $_POST['admin'];
    $credits= $_REQUEST['credits'];
    $creditid= $_REQUEST['creditid'];
    $SendSMG = $_POST['Message'];
    $message=$SendSMG.':: '.$company;
    $SendSMG  = urlencode($SendSMG);
    $x=strstr($Phone_numbers,",");
    
    //echo "<br> ".$Phone_numbers."  message = ".$SendSMG."  credits ".$credits."  from ".$company; 
    
   if($credits>0)
   {//echo"<br>2-1";
	if(substr($x,0,1)==",")
	  {
	    $arr = (explode(",",$Phone_numbers));
	   // send_sms($Phone_numbers,$message,$user,$company,$creditid,$credits);
           // echo "  <br> already exploding ";
	  }
	 else
	  {
	    $num=str_replace("\n",",",$Phone_numbers);
	    $num=str_replace("\r",",",$Phone_numbers);
	    $arr = (explode(",",$num));
	  }	
		$msgcount=0;
		$numbers='';
		foreach ($arr as $value)
		{
			if($credits > $msgcount )
			{ 
			$celnumber=trim($arr[$msgcount]);
			$msgcount++;
			 if(strlen($celnumber)==8)
			 {
			  $celnumber="+266".$celnumber;		 
			 }
                       // echo " <br>  ".$celnumber;
			$message=str_replace("'"," ",$message);
			$query="INSERT INTO bulkpush VALUES('','$message','$company','','$celnumber','$username','$DateSend')";
			//echo $query;
			$result = mysql_query($query) or die("Couldn't Insert bulkpush 2 ".mysql_get_last_message());
			
                        
                        $query1="INSERT INTO bulkmessages(id, message, msisdn, queued, message_date, company, username) VALUES('','$message','$celnumber', 'Q',NOW(),'$company','$username')";
			//echo $query1;
			$result = mysql_query($query1) or die("Couldn't Insert bulkpush 2 ".mysql_get_last_message());
			
						
			$credits=$credits-1;
			$sqlu="update bulk_credits set credits='$credits' where id='$creditid'";
                        
                       // echo " <br> ".$sqlu;
			$resu=mysql_query($sqlu) or die('Unable to update credits '.mysql_get_last_message());
			//echo $credits;
			}
			
		}
		
	 }
	else
	{	
		$blnSent == true;
		?>
		  <script language = "javascript" type = "text/javascript"> 
		    alert("Credits are finished. Buy some more credits keep posting messages. Processing record: <?php echo $value;?>");
		    window.href = "bulkmsgs.php";
		</script>
		 <?php
	}
}

	  ?>
		<script language = "javascript" type = "text/javascript"> 
			alert('Message sending Completed');
			window.location = "bulkmsgs.php";									
		</script>
             <?php
  mysql_close();
 
?>