#!/usr/bin/php -q
<?php
//require_once 'lib.php';
require_once 'config2.php';

if(!ini_get('date.timezone'))
{
	date_default_timezone_set('GMT');
}

$log = '/var/log/inject-votes.log';

function help()
{
	global $log;

	echo chr(10);
	echo "Process creating a shedule queue daemon.".chr(10);
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
else
{  
	if(!($conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME)))
	{
		//file_put_contents($log, chr(9).'-- Database error: '.$conn->error.chr(10), FILE_APPEND);
		return 1;
	}//end if

	//while (1){

		$query = "SELECT * FROM temp_votes";
		if(!($rs = $conn->query($query)))
		{
			//file_put_contents($log, chr(9).'-- Database error: '.$conn->error.chr(10), FILE_APPEND);
			return 1;
		}//end if

		if($rs->num_rows > 0){
			
			for($data = 0; $data < $rs->num_rows; $data++){
				$rs->data_seek($data);
				$row = $rs->fetch_assoc();

                        if (strlen($row['shortcode'])>5){
                            $shorti = substr($row['shortcode'], 4);;

                       }else{
                            $shorti = $row['shortcode'];
                       }   
				$date = $row['created'];
				 $cat =  trim($row['keyword']);
                                 $msisdn = $row['msisdn'];
                                
                                
                                

                         
                        if ($shorti == '31012'){
                                for ($x = 1; $x <= 15; $x++) {
                                  save_ULTIMATE($conn,$msisdn,  $cat, $date);
                               }
                        }else{
                         save_ULTIMATE($conn,$msisdn,  $cat, $date);

                        }
				
			}//end for
		}//end if
		$rs->free_result();

		//sleep(1);
	//}//end while
	//file_put_contents($log, chr(10).'Status: Ending process - '.date("Y-m-d H:i:s").chr(10), FILE_APPEND);
}//end else
$conn->close();


function save_ULTIMATE($conn, $msisdn, $text, $date){
    
                $query2 = "INSERT INTO competition_transaction (msisdn,  text, date_entered ,company ) 
                                                VALUES ('".$msisdn."','".strtoupper($text)."','$date','be forward')"; 
                                          
             //   echo $query2;
             
                if(!mysqli_query($conn, $query2))
                    {
                            file_put_contents($log, chr(10).'-- Database error: '.mysqli_error(), FILE_APPEND);
                    }  
           
          
         
      
    
      return;
} 