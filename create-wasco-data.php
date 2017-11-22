#!/usr/bin/php
<?php
require_once 'Classes/PHPExcel/IOFactory.php';
require_once 'config2.php';

if(!ini_get('date.timezone'))
{
	date_default_timezone_set('GMT');
}

$log = '/var/log/create-wasco-data.log';

function help()
{
	global $log;

	echo chr(10);
	echo "Process creating a winners queue daemon.".chr(10);
	echo chr(10);
	echo "Usage:".chr(10);
	echo chr(9)."./create-q.php [options]".chr(10);
	echo chr(10);
	echo chr(9)."Options:".chr(10);
	echo chr(9).chr(9)."--help: Displays this help message.".chr(10);
	echo chr(9).chr(9)."--log=<filename> The location of the log file. (default '$log')".chr(10);
	echo chr(10);
}//End of help() function

//Configure command line arguments
if($argc > 0)
{
	foreach ($argv as $arg)
	{
		$args = explode('=', $arg);
		switch ($args)
		{
			case '--help':
				return help();
				break;
			case '--log':
				$log = $args[1];
				break;
		}//end switch;
	}//end foreach;
}//end if;

file_put_contents($log, 'Status: Starting up process - '.date("Y-m-d H:i:s").chr(10), FILE_APPEND);
$pid = pcntl_fork();
if ($pid == -1)
{
	file_put_contents($log, chr(9).'-- Error: Could not daemonize process'.chr(10), FILE_APPEND);
	return 1;
}//end if
elseif ($pid)
{
	return 0;
}//end elseif
else { //else other thing that are important
	
    if(!($conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME)))
	{
		file_put_contents($log, chr(9).'-- Database error: '.$conn->error.chr(10), FILE_APPEND);
		return 1;
	}//end if
	
	while (1){
            $dir = "_uploads/";
           // $files_in_directory = scandir('_uploads/');
           // $items_count = count($files_in_directory);
            
           // echo '   item count '.$items_count;
            
            //$q      =  (count(glob("_uploads/*.{xlsx,xls,csv,txt")) == 0) ? 'Empty' : 'Not empty';
                    
            if (count(glob("_uploads/*")) > 0 ){
                        
           
            
                  $files = glob("_uploads/*.{xlsx,xls,csv,txt}", GLOB_BRACE);

                    foreach ($files as $file) {
                      $filename = $file;
                        // each filename need processing now.
                       // echo "  Filename:  ".$filename;
                      $ext = pathinfo($filename, PATHINFO_EXTENSION);
                      $path_parts = pathinfo($filename);
                      $name_of_file = $path_parts['filename'];
                      
                      	 $ncompany = substr($name_of_file,0 ,strrpos($name_of_file,"_"));
                     // echo " Name of the File: ".$ncompany;
                     //echo " My Extension : ".$ext."<br>";
                     if (($ext == "txt") || ($ext == "csv")){  
                                
                         readTXTCSV($conn, $filename, $name_of_file ,$ncompany);

                    } else {
                        
                       // readEXCELFile($conn, $filename,$name_of_file ,$ncompany);
                    } 
      
                  }          

            }  // end if check folder contains files...... Check with the creator of this module.....

            sleep(10);
	}//end while
	file_put_contents($log, chr(10).'Status: Ending process - '.date("Y-m-d H:i:s").chr(10), FILE_APPEND);
}//end else
$conn->close();



function readTXTCSV($conn, $filename,$real_file , $company){
    //echo "  Inside read TXTCSV ";
   
    $query = "SELECT * FROM uploadedfile WHERE filename='$real_file' AND company='$company' and processed=0";
   
      $results = mysqli_query($conn, $query);
      $num_results = mysqli_num_rows($results);
     $r = mysqli_fetch_assoc($results);
     
     
     $saved_message = $r['message'];
     $id = $r['id'];
     
     if ($num_results > 0){
              
     $csv = getdata($filename);


      $rows = count($csv);

//echo "   Rows : ".$rows." <br>";
      $count = 0;
        for ($row = 1; $row < $rows; $row++) {
             $cols = count($csv[$row]);
             for($col = 0; $col < $cols; $col++ ) {

                 if ($col ==0){
                     $msisdn = $csv[$row][0];
                        if(strlen($msisdn)==8)
                        {
                         $msisdn="+266".$celnumber;		 
                        }

                 }
                 else if ($col ==1){
                     $name = $csv[$row][1];
                 }
                 else if ($col ==2){
                     $surname = $csv[$row][2];
                 }
                 else if ($col ==3){
                     $refNo = $csv[$row][3];
                 }
             }
             
               $message = "Dear ".$name." ".$surname."(".$refNo."), ".$saved_message;
        
           
               $query = "INSERT INTO bulkmessages(id, message, msisdn, queued, message_date, company, username) VALUES('','$message','$msisdn', 'Q',NOW(),'$company','System')";
	
               if(!$conn->query($query)){
                    return 1;
               }
                   
               $count = $count + 1;         
      }
      
      
      $sql="select id, credits from bulk_credits where company='$company' and status=1";
		//$res=mysql_query($sql);
                
      $results = mysqli_query($conn, $sql);
      $num_rows = mysqli_num_rows($results);
      $row = mysqli_fetch_assoc($results);
      $cid = $row['id'];
      $credits = $row['credits'];
      if ($num_rows > 1){
          $credits=$credits-$count;
	  $sqlu="update bulk_credits set credits='$credits' where id='$cid'";
          if(!$conn->query($sqlu)){
                 
             return 1;
          }                    
      }
          
      
                
     
     $query = "UPDATE uploadedfile set processed=1 where id=$id";
    // echo $query;
         if(!$conn->query($query))
                   {
                          file_put_contents($log, chr(9).'-- Database error: '.$conn->error.chr(10), FILE_APPEND);

                           return 1;
                   }     
    }
      
                   
     
unlink($filename);
    
}

  function getdata($csvFile){
    $file_handle = fopen($csvFile, 'r');
    while (!feof($file_handle) ) {
        $line_of_text[] = fgetcsv($file_handle, 1024);
    }
    fclose($file_handle);
    return $line_of_text;
}     


function readEXCELFile($conn,$filename,$real_file ,$company){
    
    $query = "SELECT * FROM uploadedfile WHERE filename='$real_file' AND company='$company' and processed=0";
     echo $query."<br>";		
    
      $results = mysqli_query($conn, $query);
      $num_results = mysqli_num_rows($results);
     $r = mysqli_fetch_assoc($results);
     
     
     $saved_message = $r['message'];
     $id = $r['id'];
    $inputFileName = $filename;
    $count = 0;
    if ($num_results > 0){
 
             $objReader = PHPExcel_IOFactory::createReader('Excel2007');
             $objReader->setReadDataOnly(true);
             $objPHPExcel = $objReader->load($filename);



             $sheetCount = $objPHPExcel->getSheetCount();

             $sheetNames = $objPHPExcel->getSheetNames();


         if ($sheetCount > 1){
                 foreach($sheetNames as $sheetIndex => $sheetName) {
                //     echo 'WorkSheet #',$sheetIndex,' is named "',$sheetName,'"<br />';

                 $objWorksheet = $objPHPExcel->getSheet($sheetIndex);
                 $highestRow = $objWorksheet->getHighestRow();
                 $highestColumn = $objWorksheet->getHighestColumn();
                 $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
                 $rows = array();
                 for ($row = 2; $row <= $highestRow; ++$row) {
                   for ($col = 0; $col <= $highestColumnIndex; ++$col) {
                     $rows[$col] = $objWorksheet->getCellByColumnAndRow($col, $row)->getValue();


                   }
                            $msisdn = $rows[0];

                             $name = $rows[1];
                             $surname = $rows[2];

                             $names = ltrim($name." ".$surname);
                            // $contact = $rows[3];
                           
                             $refNo = $rows[3];


                             $message = "Dear ".$name." ".$surname."(".$refNo."), ".$saved_message;
        
           
                             $query = "INSERT INTO bulkmessages(id, message, msisdn, queued, message_date, company, username) VALUES('','$message','$msisdn', 'Q',NOW(),'$company','System')";
                             $count++;
                            if(!$conn->query($query))
                            {
                                    file_put_contents($log, chr(9).'-- Database error: '.$conn->error.chr(10), FILE_APPEND);

                            }

                         //}
                   }


                 } // more than one worksheet... this is working just fine.
         }else{

                 $objWorksheet = $objPHPExcel->getActiveSheet();
                 //$objPHPExcel->get
                 $highestRow = $objWorksheet->getHighestRow();
                 $highestColumn = $objWorksheet->getHighestColumn();
                 $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
                 $rows = array();
                 for ($row = 2; $row <= $highestRow; ++$row) {
                   for ($col = 0; $col <= $highestColumnIndex; ++$col) {
                     $rows[$col] = $objWorksheet->getCellByColumnAndRow($col, $row)->getValue();


                   }

                             $msisdn = $rows[0];

                             $name = $rows[1];
                             $surname = $rows[2];

                             $names = ltrim($name." ".$surname);
                            // $contact = $rows[3];
                             $refNo = $rows[3];
                            $message = "Dear ".$name." ".$surname."(".$refNo."), ".$saved_message;

                   //        echo " Message : ".$message;
                            $count++;
                           $query = "INSERT INTO bulkmessages(id, message, msisdn, queued, message_date, company, username) VALUES('','$message','$msisdn', 'Q',NOW(),'$company','System')";

                            if(!$conn->query($query))
                            {
                     
                                    return 1;
                            }

                   }
         }
         
      $sql="select id, credits from bulk_credits where company='$company' and status=1";
	          
      $results = mysqli_query($conn, $sql);
      $num_rows = mysqli_num_rows($result);
      $row = mysqli_fetch_assoc($result);
      $cid = $row['id'];
      $credits = $row['credits'];
      if ($num_rows > 1){
          $credits=$credits-$count;
	  $sqlu="update bulk_credits set credits='$credits' where id='$cid'";
          if(!$conn->query($sqlu)){
                 
             return 1;
          }                    
      }
    
    
      $query = "UPDATE uploadedfile set processed=1 where id=$id";
    // echo $query;
         if(!$conn->query($query)){
               return 1;
         }
   
         
         
    }
    
    
    
          

    unlink($filename);
    
    return;
}