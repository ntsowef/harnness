<?php
require_once 'config2.php';
$log = '/var/log/dlr_access.log';
if(!($conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME)))
   {
		file_put_contents($log, chr(10).chr(9).'-- Database error: '.$conn->error.chr(10), FILE_APPEND);
		return 1;
   }//end if
file_put_contents($log, chr(10).'Status: Starting up process - '.date("Y-m-d H:i:s").chr(10), FILE_APPEND);
if((isset($_REQUEST)) && (isset($_REQUEST['type'])))
{file_put_contents($log, chr(10).'DRL for this was : '.$_REQUST['type'], FILE_APPEND);


	$query = "UPDATE bulkmessages ";
        $type = $_REQUEST['type'];
        $wasco_reason ='';
        $status = '';
	switch ($type)
	{
		case '1': $query .= "SET status='delivery success'";
                          $wasco_reason = "Successful: Delivered";
                          $status ="Success";
			break;
		case '2': $query .= "SET status='delivery failure'";
                          $wasco_reason = "Failed: Message failed delivery to phone";
                          $status ="Failure";
			break;
		case '4': $query .= "SET status='message buffered'";
                          $wasco_reason = "Message has been buffered";
                          $status = "Success";
			break;
		case '8': $query .= "SET status='delivery success'";
                          $wasco_reason = "Successful: Delivered";
                          $status = "Success";
			  break;
		case '16': $query .= "SET status='failed: smsc reject'";
                           $wasco_reason = "Failed: This number is not a mobile number, failed to submit";
                           $status ="Failed";

			break;
	}
	$query .= ", date_sent=NOW() WHERE id='".$_REQUEST['id']."'";
}
	if(!$conn->query($query))
        {
                file_put_contents($log, chr(10).chr(9).'-- Error: '. $errno.' '.$errstr.chr(10), FILE_APPEND);
                return 1;
        }


        $query = "SELECT * from bulkmessages where id='".$_REQUEST['id']."'";
        if(!$res=$conn->query($query))
        {
                file_put_contents($log, chr(10).chr(9).'-- Error: '. $errno.' '.$errstr.chr(10), FILE_APPEND);
                return 1;
        }

        if($res->num_rows > 0){

            $row = $res->fetch_assoc();

            $company = $row['company'];
           // $reason = $row['status'];
            $sql1="select * from bulk_credits where company='$company' and status=1";
            if(!$result=$conn->query($sql1))
                {
                        file_put_contents($log, chr(10).chr(9).'-- Error: '. $errno.' '.$errstr.chr(10), FILE_APPEND);
                        return 1;
                }

            $r = $result->fetch_assoc();

            $credit = $r['credits'];
            $creditid = $r['id'];

            //echo "  Credits ".$credit."   with ID ".$creditid;

            if ($company==="WASCO"){

                $query = "UPDATE wasco_data_billing SET date_sent=NOW(), reasons_status='$wasco_reason' where sms_id='".$_REQUEST['id']."'";
                if(!$conn->query($query))
                    {
                            file_put_contents($log, chr(10).chr(9).'-- Error: '. $errno.' '.$errstr.chr(10), FILE_APPEND);
                            return 1;
                    }

                    if ($status==="Success"){
                        $credit = $credit - 1;
                        $sqlu="update bulk_credits set credits='$credit' where id='$creditid'";
                         if(!$conn->query($sqlu))
                            {
                                 file_put_contents($log, chr(10).chr(9).'-- Error: '. $errno.' '.$errstr.chr(10), FILE_APPEND);
                                 return 1;
                            }



                    }else if ($status==="Failure"){
                        $credit = $credit + 1;
                        $sqlu="update bulk_credits set credits='$credit' where id='$creditid'";
                         if(!$conn->query($sqlu))
                            {
                                 file_put_contents($log, chr(10).chr(9).'-- Error: '. $errno.' '.$errstr.chr(10), FILE_APPEND);
                                 return 1;
                            }
                    }


            }   // please implement

           else{
               /*if ($status==="Success"){
                        $credit = $credit - 1;
                        $sqlu="update bulk_credits set credits='$credit' where id='$creditid'";
                         if(!$conn->query($sqlu))
                            {
                                 file_put_contents($log, chr(10).chr(9).'-- Error: '. $errno.' '.$errstr.chr(10), FILE_APPEND);
                                 return 1;
                            }



                    }else if ($status==="Failure"){
                        $credit = $credit + 1;
                        $sqlu="update bulk_credits set credits='$credit' where id='$creditid'";
                         if(!$conn->query($sqlu))
                            {
                                 file_put_contents($log, chr(10).chr(9).'-- Error: '. $errno.' '.$errstr.chr(10), FILE_APPEND);
                                 return 1;
                            }
                    } */

            }
        }

    $conn->close();