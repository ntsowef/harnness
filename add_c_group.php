<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', '1');
set_time_limit(900);
include 'connect.php';
       $group_name = $_POST['group_name'];
       $type='bulk';    
       $company= $_SESSION['company'];
       $directory = "_uploads/";
//echo 'Company name is ==>'.$company;
 
 if ( empty ( $group_name ) ) {
	echo "Yo! Something ain't legit!";  
	exit;
  } else{
	  $filename = $_FILES['file']['name'];
	  if (empty($filename)){
		   echo " Your file cannot be empty";
		   exit;
	  }
	  $content=$directory."".$filename;
	  $ext = pathinfo($filename, PATHINFO_EXTENSION);
	   
	    $insert="INSERT into sms_group values('','$company','$group_name','Admin',now(),'$content','$type','1')";
	     $result=mysql_query($insert) ;
		 if ($result!=1){
			 echo " Error inserting the group ".mysql_errno();
		 } else{
			 
			  $createtable= "CREATE TABLE sms_group_".$group_name."(
                              msisdn VARCHAR(20) NOT NULL,
                              active boolean NOT NULL DEFAULT 1,                        
                              last_update TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP)";

//echo $createtable;


              $createRes = mysql_query($createtable);
			  
			  if ($createRes != 1){
				  echo " Could not create customer group ".$group_name.''.PHP_EOL;
				  ?>
						<!--script language = "javascript" style = "text/javascript">
						window.location = "http://196.37.186.21/hollard/add_bulksms_group.php";
						</script-->
                     
                  
					<?php
			  }else{
				  if ($ext == "xls" || $ext == "xlsx"){
					 process_excelfile($filename, $ext, $group_name);
				  //   echo "Processing excel spreadsheet";
					
					
				  }
				  else{
					  echo " Processing csv file";
					process_csv($filename, $group_name);
			
					          
				  }
				echo "Bulk group ".$group_name." is successfully created";
				?>
						<!--script language = "javascript" style = "text/javascript">
						//window.location = "http://oleetelecoms.co.za/Campaign_Management.html";
						</script-->
                                        <!--script language = "javascript" style = "text/javascript">
						window.location = "http://oleetelecoms.co.za/Campaign_Management.html";
				        </script-->
                  
			    <?php
			  }
		}
	  
  }
  function process_csv($filename, $group_name){

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
                  
                        // $temp = $fileo;                        
                         $sqlstatement="LOAD DATA LOCAL INFILE '$target_path' INTO TABLE sms_group_".$group_name." FIELDS TERMINATED BY '' LINES TERMINATED BY '\r\n' ";
                    //    echo $sqlstatement;
                        mysql_query($sqlstatement) or die(mysql_error());
					
    return;
}
  
  function process_excelfile($filename, $ext, $group_name){
    require_once 'config1.php';
    require_once 'Classes/PHPExcel/IOFactory.php';
	//	echo " Inside process excel ".$filename."".PHP_EOL;
	    $directory = "_uploads/";
 
        if (!file_exists($directory)){
        mkdir($directory, 0777, true);
		}             
      
                       
       $target_path = $directory. basename( $_FILES['file']['name']);


       if(move_uploaded_file($_FILES['file']['tmp_name'], $target_path)) {
       } else{
                            
		   exit;
       }
       

    if ($ext == "xls" ){
		    
	  $objReader = PHPExcel_IOFactory::createReader('Excel5');
	 }
	else if ($ext == "xlsx"){
        $objReader = PHPExcel_IOFactory::createReader('Excel2007');             
    }      

    // echo "  Filename ".$target_path;
    $objReader->setReadDataOnly(true);
    $objPHPExcel = $objReader->load($target_path);
// echo "          Filename : ".$inputFileName."  ".PHP_EOL;


    $sheetCount = $objPHPExcel->getSheetCount();

    $sheetNames = $objPHPExcel->getSheetNames();
	if(!($conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME)))
	{
		echo('-- Database error: '.$conn->error.chr(10));
		return 1;
	} //end if
    
        $objWorksheet = $objPHPExcel->getActiveSheet();
        //$objPHPExcel->get
        $highestRow = $objWorksheet->getHighestRow();
        $highestColumn = $objWorksheet->getHighestColumn();
        $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
        $rows = array();
        $id = 0;
        for ($row = 1; $row <= $highestRow; ++$row) {

          for ($col = 0; $col <= $highestColumnIndex; ++$col) {
            $rows[$col] = $objWorksheet->getCellByColumnAndRow($col, $row)->getValue();


          }
                 $cell_no = $rows[0];

        $id++;
                 $msisdn = internationalization($cell_no);
                  
                
		$sql = "INSERT INTO sms_group_".$group_name."( msisdn) VALUES ('$msisdn'); ";
               // echo $sql;
                 if(!$conn->query($sql))
                   {            echo " --Database error".$conn->error.chr(10);
                        //  file_put_contents($log, chr(9).'-- Database error: '.$conn->error.chr(10), FILE_APPEND);

                           return 1;
                   }
            
          }

	
	return;
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

