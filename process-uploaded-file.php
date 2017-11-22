#!/usr/bin/php -q
<?php
require_once 'Classes/PHPExcel/IOFactory.php';
require_once 'config2.php';

if(!ini_get('date.timezone'))
{
	date_default_timezone_set('GMT');
}

$log = '/var/log/process-uploaded-file.log';

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
else{
	if(!($conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME)))
	{
		file_put_contents($log, chr(9).'-- Database error: '.$conn->error.chr(10), FILE_APPEND);
		return 1;
	}//end if
	
  while (1){
            
                     $files = glob("/var/www/oleetelecomsolutionsptyltd.dedicated.co.za/sms/_uploads/*.{xlsx,xls}", GLOB_BRACE);
                    // $files = glob("_uploads/*.{xlsx,xls,csv,txt}", GLOB_BRACE);
 
                    foreach ($files as $file) {
                        $filename = $file;
                        // each filename need processing now.
                     //   echo "<br>  Filename:  ".$filename."<br>";
                        file_put_contents($log, chr(9).'-- Filename: '.$filename, FILE_APPEND);
	
                      $ext = pathinfo($filename, PATHINFO_EXTENSION);
                      $path_parts = pathinfo($filename);
                      $name_of_file = $path_parts['filename'];
                      	 $ncompany = substr($name_of_file,0 ,strrpos($name_of_file,"_"));
                     // echo " Name of the File: ".$ncompany;
                     if ($ext = "txt" || $ext == "csv"){  
                                
                         readTXTCSV($conn, $filename, $ncompany);

                    } else{
                    
                        readEXCELFile($conn, $filename, $ncompany);
                    } 

            
	}//end for
        sleep(1);
     }    //end while        
	
}//exnd else
file_put_contents($log, chr(10).'Status: Ending process - '.date("Y-m-d H:i:s").chr(10), FILE_APPEND);
$conn->close();


function readTXTCSV($conn, $filename, $company){
    echo "  Inside read TXTCSV ";
   // $inputFiname = $filename;
    $query = "SELECT * FROM uploadedfile WHERE filename='$filename' AND company='$company' and processed=0";
     echo $query."<br>";		
    
      $results = mysqli_query($conn, $query);
      $num_results = mysqli_num_rows($results);
     $r = mysqli_fetch_assoc($results);
     
     
     $saved_message = $r['message'];
     $id = $r['id'];
     
     if ($num_results > 0){
       echo "  Saved message :".$saved_message."<br>";
        
     $csv = getdata($filename);


      $rows = count($csv);

//echo "   Rows : ".$rows." <br>";
        for ($row = 1; $row < $rows; $row++) {
             $cols = count($csv[$row]);
             for($col = 0; $col < $cols; $col++ ) {

                 if ($col ==0){
                     $msisdn = $csv[$row][$col];
     
                 }
                 else if ($col ==1){
                     $name = $csv[$row][$col];
                 }
                 else if ($col ==2){
                     $surname = $csv[$row][$col];
                 }
                 else if ($col ==3){
                     $refNo = $csv[$row][$col];
                 }
             }
             
             $message = "Dear ".$name." ".$surname."(".$refNo."), ".$saved_message;
        
           
           $query = "INSERT INTO bulkmessages(id, message, msisdn, queued, message_date, company, username) VALUES('','$message','$msisdn', 'Q',NOW(),'$company','System')";
	
         if(!$conn->query($query))
                   {
                           file_put_contents($log, chr(9).'-- Database error: '.$conn->error.chr(10), FILE_APPEND);

                           return 1;
                   }
      }
     
     $query = "UPDATE uploadedfile set processed=1 where id=$id";
     echo $query;
         if(!$conn->query($query))
                   {
                          // file_put_contents($log, chr(9).'-- Database error: '.$conn->error.chr(10), FILE_APPEND);

                           return 1;
                   }     
    }
      
                   
     
//unlink($filename);
    
}

  function getdata($csvFile){
    $file_handle = fopen($csvFile, 'r');
    while (!feof($file_handle) ) {
        $line_of_text[] = fgetcsv($file_handle, 1024);
    }
    fclose($file_handle);
    return $line_of_text;
}     


function readEXCELFile($conn,$filename, $company){
    
    
    $query = "SELECT * FROM uploadedfile WHERE filename='$filename' AND company='$company' and processed=0";
   //  echo $query."<br>";		
    
      $results = mysqli_query($conn, $query);
      $num_results = mysqli_num_rows($results);
     $r = mysqli_fetch_assoc($results);
     
     
     $saved_message = $r['message'];
     $id = $r['id'];
    $inputFileName = $filename;
    
    if ($num_results > 0){
        
        
            //$inputFileName = $upload_path . $filename;
             $objReader = PHPExcel_IOFactory::createReader('Excel2007');
             $objReader->setReadDataOnly(true);
             $objPHPExcel = $objReader->load($inputFileName);



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
	
                            if(!$conn->query($query))
                            {
                                    file_put_contents($log, chr(9).'-- Database error: '.$conn->error.chr(10), FILE_APPEND);

                                    return 1;
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


                           $query = "INSERT INTO bulkmessages(id, message, msisdn, queued, message_date, company, username) VALUES('','$message','$msisdn', 'Q',NOW(),'$company','System')";

                            if(!$conn->query($query))
                            {
                                    file_put_contents($log, chr(9).'-- Database error: '.$conn->error.chr(10), FILE_APPEND);

                                    return 1;
                            }

                   }
         }
    }
    
    

unlink($filename);
    
    return;
}