<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', '1');
//set_time_limit(900);
include 'connect.php';
      // $company= $_SESSION['company'];
       $company = "TITANIC";
      $directory = "_uploads/";

       $directory = "/var/www/oleetelecomsolutionsptyltd.dedicated.co.za/sms/_uploads/";
//echo 'Company name is ==>'.$company;
     $SendSMG = $_POST['message'];
     
	$message=$SendSMG;
	$filename = $_FILES['file']['name'];
	if (strlen(trim($SendSMG)) == 0)
	{
	?>
	<script language = "javascript" type = "text/javascript"> 
	  	alert("Please enter your message");
		window.href = "upload_bulk_file.html";
	</script>
	<?php
	}
	  
         // echo "  Filename  ";
	  if (empty($filename)){
		   echo " Your file cannot be empty";
                   
                   
		   exit;
	  }
	  $content=$directory."".$filename;
	  $ext = pathinfo($filename, PATHINFO_EXTENSION);
	   
        $newFile =  upload_file($filename, $company, $ext);
        
   $query1="INSERT INTO uploadedfile(filename, company ,message) VALUES ('$newFile','$company','$message')";
//	echo $query1;
$result = mysql_query($query1) or die("Couldn't Insert bulkpush 2 ");
			
echo "  Upload is successful <br>";
echo "<a href=\"javascript:history.go(-1)\">GO BACK</a>";

  function upload_file($filename, $company, $ext){

                         
       $directory = "_uploads/";
                         if (!file_exists($directory)){
                           mkdir($directory, 0777, true);
			 }
                         $basefile = $filename;
                       
                         $target_path = $directory. basename( $_FILES['file']['name']);
                           

                        if(move_uploaded_file($_FILES['file']['tmp_name'], $target_path)) {
                            } else{
                            
							exit;
                        }
                  
                        $newfile=$directory.$company."_".time().".".$ext;
                        $scripted_file = $company."_".time().".".$ext;
                        rename($target_path, $newfile);
			$path_parts = pathinfo($newfile);
                        $newFile = $path_parts['filename'];
    return $newFile;
}


function internationalization($msisdn)
{  
	if(substr($msisdn, 0, 1) == '0')
	{
		$msisdn = preg_replace('/^0/', '+266', $msisdn);
	}
	
	if (substr($msisdn,0,3) == '266'){
		$msisdn = "+".$msisdn;
	}
	return $msisdn;
}


?>

