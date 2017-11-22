#!/usr/bin/php -q
<?php
require_once 'lib.php';
require_once 'config2.php';

if(!ini_get('date.timezone'))
{
	date_default_timezone_set('GMT');
}

$log = '/var/log/create-bulk-votes-uma.log';

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
else
{
	if(!($conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME)))
	{
		file_put_contents($log, chr(9).'-- Database error: '.$conn->error.chr(10), FILE_APPEND);
		return 1;
	}//end if
	
	while (1){
		$query = "SELECT * FROM uma_bulk_votes_queue WHERE status ='0' LIMIT 100";
		if(!($rs = $conn->query($query)))
		{
			file_put_contents($log, chr(9).'-- Database error: '.$conn->error.chr(10), FILE_APPEND);
			return 1;
		}//end if

		if($rs->num_rows > 0)
		{
			$from_add = '';
			for($data = 0; $data < $rs->num_rows; $data++)
			{
				$rs->data_seek($data);				
				$row = $rs->fetch_assoc();
				
		
                                
            
                                save_ULTIMATE($conn, $row['category_code'], $row['nominee'], $row['msisdn']);
                                                       
                                $query = "UPDATE uma_bulk_votes_queue SET status='1' WHERE id='".$row['id']."'";
				
                                if(!$conn->query($query))
                                {
                                        file_put_contents($log, chr(10).chr(9).'-- Error: '. $errno.' '.$errstr.chr(10), FILE_APPEND);
                                        return 1;
                                }//end if
     
		} // end for
                }//end if
	    $rs->free_result();

	    sleep(1);
	}//end while
	file_put_contents($log, chr(10).'Status: Ending process - '.date("Y-m-d H:i:s").chr(10), FILE_APPEND);
//}//end else
}

$conn->close();



function save_ULTIMATE($conn, $cat ,$opt, $msisdn){
    
    $return = false;
    $category = strtoupper($cat);
   
     $query1 = "SELECT * from mobile_poll_shortcode where category_code='$category'";
    // $query1 = "SELECT * from mobile_poll_shortcode where shortcode='$shortcode' and category_code='$category'";
     $results1 = mysqli_query($conn, $query1);
     $num_results = mysqli_num_rows($results1);
    
      
      if ($num_results > 0 ){
          
        $sql_category = "SELECT * FROM ultimate_categories where category_code='$cat'";
          $res = mysqli_query($conn, $sql_category);
          
          $poll_name ='';
          while ($r = mysqli_fetch_array($res)){
              $poll_name = $r['category'];
          }
          echo $poll_name."<br>"; 
          
          $query = "SELECT * FROM mobile_poll where quest='$cat'";
         // echo $query; 
         $results = mysqli_query($conn, $query);
         $num_results = mysqli_num_rows($results);  
          $row = mysqli_fetch_assoc($results);
          if ($num_results > 0){
                $query2 = "INSERT INTO splitz_poll_ans(ans_id, poll_id, poll_name ,opt, msisdn) VALUES('',".$row['id'].",'$poll_name','$opt', '$msisdn')";
            //   echo $query2;
                 if(!mysqli_query($conn, $query2))
                    {
                            file_put_contents($log, chr(10).'-- Database error: '.mysqli_error(), FILE_APPEND);
                    }
           
          }
          
          $return = true;
      }
    
      return $return;
} 
          
