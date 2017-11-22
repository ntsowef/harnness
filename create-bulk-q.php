#!/usr/bin/php -q
<?php
require_once 'lib.php';
require_once 'config2.php';

if(!ini_get('date.timezone'))
{
	date_default_timezone_set('GMT');
}

$log = '/var/log/create-bulk-q.log';

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
    echo" Entering Connection ";
	if(!($conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME)))
	{
		file_put_contents($log, chr(9).'-- Database error: '.$conn->error.chr(10), FILE_APPEND);
		return 1;
	}//end if
	
	while (1)
	{
		$query = "SELECT * FROM outgoing_sms WHERE queued='0' LIMIT 1000";
		if(!($rs = $conn->query($query)))
		{
			file_put_contents($log, chr(9).'-- Database error: '.$conn->error.chr(10), FILE_APPEND);
			return 1;
		}//end if

		if($rs->num_rows > 0)
		{    
                       // echo "   Inside If statement ";
			$from_add = '';
			for($data = 0; $data < $rs->num_rows; $data++)
			{
				$rs->data_seek($data);				
				$row = $rs->fetch_assoc();
				
				//$from_add = $row['from_add'];
				$msg = '';
				$service = '';
				
				$query = "INSERT INTO smsmt_queue (sender, receiver, text, created, status, service, smsc_id, dlr_mask, dlr_url, network_id) ";
				$query.= "VALUES('".$row['from_add']."', '".$row['msisdn']."', '".$conn->real_escape_string($row['text'])."', NOW(), 'Q', '".$service."', '1', '31', 'http://localhost/sms/dlr.php?type=%d', '".$row['network_id']."')";
				//echo " ".$query;
				file_put_contents($log, chr(9).'-- Database info: '.$query.chr(10), FILE_APPEND);
				
				if(!$conn->query($query))
				{
					file_put_contents($log, chr(9).'-- Database error: '.$conn->error.chr(10), FILE_APPEND);
				}
				//send_email(mysql_result($rs, $data, 'msisdn'), mysql_result($rs, $data, 'amount'), mysql_result($rs, $data, 'competition'));
				$query = "UPDATE outgoing_sms SET queued='1' WHERE id='".$row['id']."'";
				if(!$conn->query($query))
				{
					file_put_contents($log, chr(9).'-- Database error: '.$conn->error.chr(10), FILE_APPEND);
				}
			}//end for
		}//end if
		$rs->free_result();

		sleep(1);
	}//end while
	file_put_contents($log, chr(10).'Status: Ending process - '.date("Y-m-d H:i:s").chr(10), FILE_APPEND);
}//end else
$conn->close();