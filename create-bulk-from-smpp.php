#!/usr/bin/php -q
<?php
require_once 'lib.php';
require_once 'config2.php';
require_once 'config3_1.php';

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
	if(!($conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME)))
	{
		file_put_contents($log, chr(9).'-- Database error: '.$conn->error.chr(10), FILE_APPEND);
		return 1;
	}//end if

        if(!($conn_sms_server = new mysqli(DB_HOST, DB_USER2, DB_PASS2, DB_NAME2)))
	{
		file_put_contents($log, chr(9).'-- Database error: '.$conn_sms_server->error.chr(10), FILE_APPEND);
		return 1;
	}//end if
        
	while (1)
	{
            $query ="SELECT ms.*, comp.`companyName` FROM message_store ms, accounting acc, company comp WHERE ms.systemId = acc.systemId AND comp.id = acc.companyId and ms.processed = 0 LIMIT 1000";
	//	$query = "SELECT * FROM outgoing_sms WHERE queued='0' LIMIT 1000";
		if(!($rs_sms_server = $conn_sms_server->query($query)))
		{
			file_put_contents($log, chr(9).'-- Database error: '.$conn_sms_server->error.chr(10), FILE_APPEND);
			return 1;
		}//end if

		if($rs_sms_server->num_rows > 0)
		{
			
			for($data = 0; $data < $rs_sms_server->num_rows; $data++)
			{
				$rs_sms_server->data_seek($data);				
				$row = $rs_sms_server->fetch_assoc();
				$query1="INSERT INTO bulkmessages(id, message, msisdn, queued, message_date, company, username) "
                                        . "VALUES('','".$row['message']."','".$row['destinationAddress']."', 'Q',NOW(),'".$row['companyName']."','".$row['systemId']."')";
                                echo $query1;
				
				file_put_contents($log, chr(9).'-- Database info: '.$query.chr(10), FILE_APPEND);
				
				if(!$conn->query($query1))
				{
					file_put_contents($log, chr(9).'-- Database error: '.$conn->error.chr(10), FILE_APPEND);
				}
				//send_email(mysql_result($rs, $data, 'msisdn'), mysql_result($rs, $data, 'amount'), mysql_result($rs, $data, 'competition'));
				$query = "UPDATE message_store SET processed=1, date_processed=NOW() WHERE id='".$row['id']."'";
				if(!$conn_sms_server->query($query))
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