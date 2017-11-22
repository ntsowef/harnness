#!/usr/bin/php
<?php
require_once 'config2.php';
//require_once 'sendsms.php';

if(!ini_get('date.timezone'))
{
	date_default_timezone_set('GMT');
}

$log = '/var/log/send-bulk_sms.log';

function help()
{
	global $log;
	
	echo chr(10);
	echo "Process demonstrating a PHP daemon.".chr(10);
	echo chr(10);
	echo "Usage:".chr(10);
	echo chr(9)."./sendsms-d.php [options]".chr(10);
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

file_put_contents($log, chr(10).'Status: Starting up process - '.date("Y-m-d H:i:s").chr(10), FILE_APPEND);

$pid = pcntl_fork();
if ($pid == -1)
{
	file_put_contents($log, chr(10).chr(9).'-- Error: Could not daemonize process'.chr(10), FILE_APPEND);
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
		file_put_contents($log, chr(10).chr(9).'-- Database error: '.$conn->error.chr(10), FILE_APPEND);
		return 1;
	}//end if
		
	while (1)
	{ 
            
               $query  = "SELECT msisdn from lesotho_blacklist";
               if(!($results = $conn->query($query)))
		{
			file_put_contents($log, chr(10).chr(9).'-- Database error: '.$conn->error().chr(10), FILE_APPEND);
			return 1;
		}//end if
               /*  $blacklist = array();       
                while( $row = mysqli_fetch_array( $results)){
                  $blacklist[] = $row['msisdn']; // Inside while loop
                }*/
                
		$query = "SELECT * FROM bulkmessages_second WHERE queued='Q' LIMIT 100000";
		if(!($rs = $conn->query($query)))
		{
			file_put_contents($log, chr(10).chr(9).'-- Database error: '.$conn->error().chr(10), FILE_APPEND);
			return 1;
		}//end if
		
                
                
                
		if($rs->num_rows > 0){
                    
                    
			for($data = 0; $data < $rs->num_rows; $data++){
                            
                            $numofMessages = 0;
				$rs->data_seek($data);				
				$row = $rs->fetch_assoc();
                               //  $sender ="+26654000013";
                           
                                 $query = "SELECT * FROM bulksms_alphanumeric WHERE company='".$row['company']."' LIMIT 1";
                                if(!($res = $conn->query($query)))
                                {
                                        file_put_contents($log, chr(10).chr(9).'-- Database error: '.$conn->error().chr(10), FILE_APPEND);
                                        return 1;
                                }//   
                                
                                
                                if ($res->num_rows >0){
                                    $rows = $res->fetch_assoc();
                                    
                                    $sender = trim($rows['alphanumeric']);
                                    $network_sender = "Vodacom_New_BULK_ALPHANUMERIC2";
;
                                }else{ 
                                       $sender ="+26652000013";    // this is a correct sender numbers                                   
                                       $network_sender = "Vodacom_New_BULK2";
                                }
                        
                                $out_msg = mb_convert_encoding($row['message'], "UTF-8");

                                # Don't forget to URLEncode or else you might get unwanted string termination
                                $encoded_msg =  urlencode($out_msg);                                
                               // if (!in_array($row['msisdn'], $blacklist)){
                                
                                    
				$post_data = '<?xml version="1.0"  encoding="UTF-8"?>';
				$post_data.= '<message>';
				$post_data.= '<submit>';
				$post_data.= '    <da><number>'.$row['msisdn'].'</number></da>';
				$post_data.= '    <oa><number>'.$sender.'</number></oa>';
				$post_data.= '    <ud>'.$encoded_msg.'</ud>';
                                  $post_data .="<dcs>";
                                    // $post_data .="<mclass>mclass</mclass>";
                                     $post_data .="<coding>0</coding>";
                                   //  $post_data .="<mwi>mwi</mwi>";
                                    // $post_data .="<compress>compress</compress>";
                                    
                                $post_data .="</dcs>";
                               // $post_data.= '    <ud>'.$row['message'].'</ud>';
				$post_data.= '    <statusrequest>';
				$post_data.= '      <dlr-mask>31</dlr-mask>';
				$post_data.= '      <dlr-url>http://localhost/sms/dlr_sms2.php?type=%d&id='.$row['id'].'</dlr-url>';
				$post_data.= '    </statusrequest>';
				$post_data.= '    <from>';
				$post_data.= '      <user>admin</user>';
				$post_data.= '      <pass>admin</pass>';
				$post_data.= '    </from>';
			        $post_data.= '     <to>'.$network_sender.'</to>';
				$post_data.= '  </submit>';
				$post_data.= '</message>';

                            
				//Initiates a connection to example.com using port 80 with a timeout of 15 seconds.
				
				//Checks if the connection was fine
				if(!$socket = @fsockopen(HTTP_HOST, 13013, $errno, $errstr, 15))
				{
					//Connection failed so we display the error number and message and stop the script from continuing
					file_put_contents($log, chr(10).chr(9).'-- Error: '. $errno.' '.$errstr.chr(10), FILE_APPEND);
					return 1;
				}//end if
				else
				{
				        file_put_contents($log, chr(10).'sender: +26654000013 receiver: '.$row['msisdn'], FILE_APPEND);
					
					//Builds the header data we will send along with are post data. This header data tells the web server we are connecting to what
					//we are, what we are requesting and the content type so that it can process are request.
					$http  = "POST ".HTTP_SCRIPT." HTTP/1.1\r\n";
					$http .= "Host: ".HTTP_HOST."\r\n";
					$http .= "User-Agent: PHP API\r\n";
					$http .= "Content-Type: text/xml\r\n";
					$http .= "Content-length: " . strlen($post_data) . "\r\n";
					$http .= "Connection: close\r\n\r\n";
					$http .= $post_data . "\r\n\r\n";
					//Sends are header data to the web server
					fwrite($socket, $http);
					$contents = "";
					//Waits for the web server to send the full response. On every line returned we append it onto the $contents
					//variable which will store the whole returned request once completed.
					while (!feof($socket)) 
					{
						$contents .= fgets($socket, 4096);
					}//end while
						
					$res = explode(":", strrev(substr(strrev($contents), 0, strpos(strrev($contents), "\n"))));
					file_put_contents($log, chr(10).'Kannel response: '.$res[0], FILE_APPEND);
					
					if(($res[0] == 3) || ($res[0] == 0))
					{
						$query = "UPDATE bulkmessages_second SET queued='P', processed_date=NOW() WHERE id='".$row['id']."'";
						file_put_contents($log, chr(10).chr(9).$query.chr(10), FILE_APPEND);
						if(!$conn->query($query))
						{
							file_put_contents($log, chr(10).chr(9).'-- Error: '. $errno.' '.$errstr.chr(10), FILE_APPEND);
							return 1;
						}//end if
					}//end if
					//echo substr($contents, -1, strpos($contents, "\n"));
					//Close are request or the connection will stay open untill are script has completed.
					fclose($socket);
                 		   }//end else 
                                    
                               // }  // check if the number is not in the blacklist array
                                

                                
			}//end for
                       
                   }//end if
		$rs->free_result();
		
		 sleep(60);
	}//end while
	file_put_contents($log, chr(10).'Status: Ending process - '.date("Y-m-d H:i:s").chr(10), FILE_APPEND);
}//end else
$conn->close();



function send_sms($msgid, $numto, $msgtext,$from , $smsc = "smsc-default", $dlrmask = 31)
    {

            $sendsmsurl_prefix = "http://localhost:13013/cgi-bin/sendsms";
            $dlrurl_prefix = "http://localhost/sms/dlr_sms.php?smsc=%i&type=%d&id=$msgid'";
            //$dlrurl_prefix = "http://localhost/tools/kannel-receive.php";
            $username = "admin";
            $password = "admin";

            # fix number to what carriers expect
          //  $numto = preg_replace('/^0/', '', $numto);
          //  $numto = preg_replace('/^\+55/', '', $numto);
           // $numto = "0" . $numto;

            if (!$msgid) $dlrmask = 0;

            $dlrurl_params = array(
                   // "type" => "dlr",
                    "tiUumesent" => "%t",
                    "smsc" => "%i",
                    "uuid" => "%I",
                    "fid" => "%F",
                    "type" => "%d",
                    //"dlr-cod" => "%d",
                    "reply" => "%A",
                   // "msgid" => $msgid,
                    "text" => "%a",
                    "to" => "%P",
                    "from" => "%p",
                    "origsmsc" => "%f",
            );

            $dlrurl = $dlrurl_prefix;// ."".urldecode(http_build_query($dlrurl_params));

            $sendsmsurl_params = array(
                    "username" => $username,
                    "password" => $password,
                    "to" => $numto,
                    "from"=> $from,
                    "dlr-mask" => $dlrmask,
                    "dlr-url" => $dlrurl,
                    "smsc"=> $smsc,
                    "text" => $msgtext,
            );

            $sendsmsurl = $sendsmsurl_prefix . "?" . http_build_query($sendsmsurl_params);
//echo $sendsmsurl;
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_URL, $sendsmsurl);
            $bogus = curl_exec($ch);
            $ret = curl_error($ch);
            curl_close($ch);
            //echo $bogus;
            return $ret == "";

    }

   



