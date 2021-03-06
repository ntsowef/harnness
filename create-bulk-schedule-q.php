#!/usr/bin/php -q
<?php
require_once 'sms_queueConfig.php';
require_once 'config_aggregation.php';

if(!ini_get('date.timezone'))
{
	date_default_timezone_set('GMT');
}

$log = '/var/log/create-schedule-bulk-sa.log';

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
		file_put_contents($log, chr(9).'-- Database error: '.$conn->error.chr(10), FILE_APPEND);
		return 1;
	}//end if

        if(!($conn_aggregate = new mysqli(DB_HOST1, DB_USER1, DB_PASS1, DB_NAME1)))
	{
		file_put_contents($log, chr(9).'-- Database error: '.$conn->error.chr(10), FILE_APPEND);
		return 1;
	}//end if
        
	while (1){

		$query = "SELECT * FROM scheduled_messages WHERE schedule_date=NOW() and status='Q' LIMIT 1000";
		if(!($rs = $conn_aggregate->query($query)))
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


				$msg = '';
				$service = '';
                             //   $message = mysql_real_escape_string($row['message']);

				//$sample = "INSERT INTO bulkmessages(id, message, msisdn, queued, message_date, company, username) "
                               //         . "VALUES('','$message','".$row['msisdn']."', 'Q','now()','".$row['company']."','".$row['username']."')";

                                $sample = "INSERT INTO customer_messages(id, message, msisdn, campaign_name, customer_group , queued, message_date, company, username) "
                                        . "VALUES('','".$row['message']."','".$row['msisdn']."','".$row['campaign_name']."','".$row['customer_group']."', 'Q', NOW(),'".$row['company']."','".$row['username']."')";


				if(!$conn->query($sample))
				{
					file_put_contents($log, chr(9).'-- Database error: '.$conn->error.chr(10), FILE_APPEND);

                                        return 1;
                                }
				$query = "UPDATE scheduled_messages SET status='P', processed_date=NOW() WHERE id='".$row['id']."'";
				if(!$conn_aggregate->query($query))
				{
					file_put_contents($log, chr(9).'-- Database error: '.$conn->error.chr(10), FILE_APPEND);

                                        return 1;
                                }
			}//end for
		}//end if
		$rs->free_result();

		sleep(1);
	}//end while
	file_put_contents($log, chr(10).'Status: Ending process - '.date("Y-m-d H:i:s").chr(10), FILE_APPEND);
}//end else
$conn_aggregate->close();
$conn->close();