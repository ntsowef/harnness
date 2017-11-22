<?php


require_once 'config2.php';
require_once 'config1.php';
require_once 'config3.php';




$log = '/var/log/getMessages-'.date("d-m-Y").".log";
file_put_contents($log, chr(10).'Status: Starting up process - '.date("Y-m-d H:i:s").chr(10), FILE_APPEND);

if(!($conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME)))
{
	file_put_contents($log, chr(10).'-- Database error: '.mysqli_error(), FILE_APPEND);
	return 1;
}//end if

if(!($conn1 = mysqli_connect(DB_HOST1, DB_USER1, DB_PASS1, DB_NAME1)))
{
	file_put_contents($log, chr(10).'-- Database error: '.mysqli_error(), FILE_APPEND);
	return 1;
}//end if

if(!($conn_lesothi = mysqli_connect(DB_HOST2, DB_USER2, DB_PASS2, DB_NAME2)))
{
	file_put_contents($log, chr(10).'-- Database error: '.mysqli_error(), FILE_APPEND);
	return 1;
}//end if


if(isset($_REQUEST) && isset($_REQUEST['msisdn']))
{
    $msisdn = $_REQUEST['msisdn'];
    $networkid = $_REQUEST['network-id'];
    $textMessage = $_REQUEST['text'];
    $timein = $_REQUEST['time'];
  /*  if ($_REQUEST['network-id']=="CHANNEL"){

          if (strtolower($_REQUEST['text'])=='yes'){

               $query="INSERT INTO leads (msisdn,date_created) VALUES ('".$_REQUEST['msisdn']."',now())";
				//echo $query;

                if(!mysqli_query($conn1, $query))
                {
                        file_put_contents($log, chr(10).'-- Database error: '.mysqli_error(), FILE_APPEND);
                        //exit();
                }

          }else if ((strtolower($_REQUEST['text'])=='no')||((strtolower($_REQUEST['text'])=='out'))){


              $query="INSERT INTO opt_out (msisdn,date_created) VALUES ('".$_REQUEST['msisdn']."',now())";
				//echo $query;

                if(!mysqli_query($conn1, $query))
                {
                        file_put_contents($log, chr(10).'-- Database error: '.mysqli_error(), FILE_APPEND);
                        //exit();
                }

                  if (strtolower($_REQUEST['text'])=='out'){

                         $query = "SELECT distinct(campaign_name), customer_group from customer_messages where msisdn ='$msisdn'";
                           if(!($rs = $conn1->query($query)))
                            {
                                file_put_contents($log, chr(10).chr(9).'-- Database error: '.$conn->error().chr(10), FILE_APPEND);
                                return 1;
                            }//end if
                             if($rs->num_rows > 0){

                                 for($data = 0; $data < $rs->num_rows; $data++){
				    $rs->data_seek($data);
				    $row = $rs->fetch_assoc();

                                    $customer_group = $row['customer_group'];

                                    $query = "DELETE FROM sms_group_".$customer_group." WHERE msisdn='$msisdn'";
                                     if(!($rs = $conn1->query($query)))
                                     {
                                         file_put_contents($log, chr(10).chr(9).'-- Database error: '.$conn->error().chr(10), FILE_APPEND);
                                        return 1;
                                     }//end if
                                    
                                 }

                             }
                        exit();
                 }

          }

      $query = "UPDATE customer_messages SET customer_reply='".$_REQUEST['text']."' WHERE msisdn='".$_REQUEST['msisdn']."'";
						                 //echo $query;

        if(!mysqli_query($conn1, $query))
        {
                file_put_contents($log, chr(10).'-- Database error: '.mysqli_error(), FILE_APPEND);
                //exit();
        }
       // echo " Text message: ".$_REQUEST['text'];

        return 1;

    } */
//$shorti = '';
      
      if (strlen($_REQUEST['short-code'])>5){
           $shorti = substr($_REQUEST['short-code'], 4);;
         
      }else{
           $shorti = $_REQUEST['short-code'];
      }   

      $station_name = '';
      $is_radio_shortcodes = false;
      
      $qry = "SELECT * from shortcodes where shortcode='$shorti'";
      if(!($res = $conn->query($qry)))
		{
			file_put_contents($log, chr(10).chr(9).'-- Database error: '.$conn->error().chr(10), FILE_APPEND);
			return 1;
		}//end if
     $rw = $res->fetch_assoc();
		
     $smscost = $rw['cost_inclusive']; 
    ///echo $smscost;
     $query = "SELECT * FROM premium_services WHERE keyword='".strtoupper($_REQUEST['text'])."' AND shortcode='$shorti'";
     //   echo $query;		
        if(!($rs = $conn->query($query)))
		{
			file_put_contents($log, chr(10).chr(9).'-- Database error: '.$conn->error().chr(10), FILE_APPEND);
			return 1;
		}//end if
	     
    //echo "   I am here testing ";
         if($rs->num_rows > 0){ 
             
             // echo "   Inside if row num > 0 ";
			$from_add = '';
			for($data = 0; $data < $rs->num_rows; $data++){
				$rs->data_seek($data);				
				$row = $rs->fetch_assoc();
				$cost = $row['price'];
				$message = $row['message'];
                                $company = $row['company'];
                            //echo "  My company ".$company;                                
                                $is_comp_has_advert = is_company_has_advert($conn, $company);
                                $is_keyword_has_advert = is_keyword_has_advert($conn, $_REQUEST['text']);
                              /*  if ($company=='be forward'){
                                    $message ="This competition is officially closed";
                                   if ($shorti =='31012'){
                                       
                                       for ($i=1; $i>=15;$i++){
                                          $query = "INSERT INTO competition_transaction (msisdn,  text, company, date_entered ) 
                                                VALUES ('".$_REQUEST['msisdn']."','".strtoupper($_REQUEST['text'])."','$company', NOW())";
                                          if(!mysqli_query($conn, $query))
                                                {
                                                        file_put_contents($log, chr(10).'-- Database error: '.mysqli_error(), FILE_APPEND);
                                                }
                                       }
                                   } else if ($shorti=='31019'){
                                       $query = "INSERT INTO competition_transaction (msisdn,  text, company, date_entered ) 
                                                VALUES ('".$_REQUEST['msisdn']."','".strtoupper($_REQUEST['text'])."', '$company', NOW())";
                                                if(!mysqli_query($conn, $query))
                                                {
                                                        file_put_contents($log, chr(10).'-- Database error: '.mysqli_error(), FILE_APPEND);
                                                }
                                   }          
                               
                                }*/ // competition transaction
                                             
                                if ($is_comp_has_advert ){                                    
                                  // echo $company."  company has advert";   
                                    if (checkAvailableAdvert($conn)){
                                       
                                        $advert = getAdvert($conn, $company);
                                       $message = $message." ".$advert;  
                                    }
                                }  
                                elseif($is_keyword_has_advert){
                                     if (checkAvailableAdvert($conn)){
                                       
                                        $advert = getAdvertKeyword($conn, $_REQUEST['text']);
                                       $message = $message." ".$advert;  
                                    }
                                }    
                                else{
                                    $message = $message;
                                }
                                
                                
                              //  echo "   keywork " .$_REQUEST['text']."  Message ".$message;
				$query = "INSERT INTO outgoing_sms (msisdn, from_add, text, created, queued, network_id) 
                                 VALUES ('".$_REQUEST['msisdn']."','".$_REQUEST['short-code']."', '$message', NOW(), '0', '".$_REQUEST['network-id']."')";
                                if(!mysqli_query($conn, $query))
				{
					file_put_contents($log, chr(10).'-- Database error: '.mysqli_error(), FILE_APPEND);
				}
				$query = "INSERT INTO smsmo (network_id, sender, receiver, keyword, created) 
				VALUES ('".$_REQUEST['network-id']."', '".$_REQUEST['msisdn']."', '".$_REQUEST['short-code']."', '".$_REQUEST['text']."', NOW())";
				
				if(!mysqli_query($conn, $query))
				{
					file_put_contents($log, chr(10).'-- Database error: '.mysqli_error(), FILE_APPEND);
				}
				//exit();
				
				$query="INSERT INTO premium_transactions (celnumber,shortcode,price,keyword,operator,message,company,message_date) VALUES ('".$_REQUEST['msisdn']."','".$_REQUEST['short-code']."','".$row['price']."' ,'".$_REQUEST['text']."','".$_REQUEST['network-id']."','".$row['message']."','".$row['company']."',now())";
				//echo $query;

                                if(!mysqli_query($conn, $query))
				{
					file_put_contents($log, chr(10).'-- Database error: '.mysqli_error(), FILE_APPEND);
					//exit();
				}
							
	
		
            }
            exit();
	} else{
            //  echo "   No premium services ".$msisdn;
                   
             	if((strtolower($_REQUEST['text']) == 'stop') || (strtolower($_REQUEST['text']) == 'smsout')|| (strtolower($_REQUEST['text']) == 'out')) {
                    $dumbmessage = "This number ".$_REQUEST['msisdn']." has been removed from Splitz database. Thanks for your subscription ";
                    
                    $query = "INSERT INTO outgoing_sms (msisdn, from_add, text, created, queued, network_id)
                    VALUES ('".$_REQUEST['msisdn']."','".$_REQUEST['short-code']."', '$dumbmessage', NOW(), '0', '".$_REQUEST['network-id']."')";
                    if(!mysqli_query($conn, $query))
                    {
                            file_put_contents($log, chr(10).'-- Database error: '.mysqli_error(), FILE_APPEND);
                    }
                    $query = "INSERT INTO smsmo (network_id, sender, receiver, keyword, created) 
                    VALUES ('".$_REQUEST['network-id']."', '".$_REQUEST['msisdn']."', '".$_REQUEST['short-code']."', '".$_REQUEST['text']."', NOW())";

                    if(!mysqli_query($conn, $query))
                    {
                            file_put_contents($log, chr(10).'-- Database error: '.mysqli_error(), FILE_APPEND);
                    }

                    $query="INSERT INTO premium_transactions (celnumber,shortcode,price,keyword,operator,message,company,message_date) VALUES ('".$_REQUEST['msisdn']."','".$_REQUEST['short-code']."','$smscost' ,'".$_REQUEST['text']."','".$_REQUEST['network-id']."','$dumbmessage','Splitz Marketing',now())";
                    if(!mysqli_query($conn, $query))
                    {
                        file_put_contents($log, chr(10).'-- Database error: '.mysqli_error(), FILE_APPEND);
                    }             
                    
                    
                    
                   $query="INSERT INTO lesotho_blacklist (msisdn,shortcode, network_id,keyword,date_added) VALUES ('".$_REQUEST['msisdn']."','".$_REQUEST['short-code']."','".$_REQUEST['network-id']."','".$_REQUEST['text']."',now())";
                    
                   echo "  ".$query;
                   
                   if(!mysqli_query($conn, $query))
                    {
                        file_put_contents($log, chr(10).'-- Database error: '.mysqli_error(), FILE_APPEND);
                    }
                    exit();
               
                    
                    
                    
                 }            
               if (is_poll_shortcode($conn, $shortcode)){
                   
               } 
                else if (is_radio_shortcode($conn, $shorti)){
                        $radio_name = getRadio($conn, $shorti);
                       // echo " Radio station name ".$radio_name;
                        $word_count = get_num_of_words($_REQUEST['text']);
                        if((is_poll_shortcode($conn, $shorti)) && ($word_count == 1)){
                               savePoll($conn, $shorti, $_REQUEST['text']); 
                               $message = $radio_name;
                                $query = "INSERT INTO outgoing_sms (msisdn, from_add, text, created, queued, network_id) 
                                 VALUES ('".$_REQUEST['msisdn']."','".$_REQUEST['short-code']."', '$message', NOW(), '0', '".$_REQUEST['network-id']."')";
                                if(!mysqli_query($conn, $query))
				{
					file_put_contents($log, chr(10).'-- Database error: '.mysqli_error(), FILE_APPEND);
				}
                                
                                 $query = "INSERT INTO smsmo (network_id, sender, receiver, keyword, created) 
                                VALUES ('".$_REQUEST['network-id']."', '".$_REQUEST['msisdn']."', '".$_REQUEST['short-code']."', '".$_REQUEST['text']."', NOW())";

                                if(!mysqli_query($conn, $query))
                                {
                                        file_put_contents($log, chr(10).'-- Database error: '.mysqli_error(), FILE_APPEND);
                                }

                                $query="INSERT INTO premium_transactions (celnumber,shortcode,price,keyword,operator,message,company,message_date) VALUES ('".$_REQUEST['msisdn']."','".$_REQUEST['short-code']."','$smscost' ,'".$_REQUEST['text']."','".$_REQUEST['network-id']."','$message','$radio_name',now())";
                                if(!mysqli_query($conn, $query))
                                {
                                file_put_contents($log, chr(10).'-- Database error: '.mysqli_error(), FILE_APPEND);
                                }
                            
                        } else {
                            
                            // echo "Poll id is zero for sure";
                                  $message = $radio_name;
                                $query = "INSERT INTO outgoing_sms (msisdn, from_add, text, created, queued, network_id) 
                                 VALUES ('".$_REQUEST['msisdn']."','".$_REQUEST['short-code']."', '$message', NOW(), '0', '".$_REQUEST['network-id']."')";
                                if(!mysqli_query($conn, $query))
				{
					file_put_contents($log, chr(10).'-- Database error: '.mysqli_error(), FILE_APPEND);
				}
                               $query = "INSERT INTO smsmo (network_id, sender, receiver, keyword, created) 
                                VALUES ('".$_REQUEST['network-id']."', '".$_REQUEST['msisdn']."', '".$_REQUEST['short-code']."', '".$_REQUEST['text']."', NOW())";

                                if(!mysqli_query($conn, $query))
                                {
                                        file_put_contents($log, chr(10).'-- Database error: '.mysqli_error(), FILE_APPEND);
                                }
                                $query="INSERT INTO premium_transactions (celnumber,shortcode,price,keyword,operator,message,company,message_date) VALUES ('".$_REQUEST['msisdn']."','".$_REQUEST['short-code']."','$smscost' ,'".$_REQUEST['text']."','".$_REQUEST['network-id']."','$message','$radio_name',now())";
                                if(!mysqli_query($conn, $query))
                                {
                                file_put_contents($log, chr(10).'-- Database error: '.mysqli_error(), FILE_APPEND);
                                }
                                   //   echo "Station name ".$station_name."  ---  ";                                                        
                               $query="INSERT INTO temp_marquee (msisdn,message,date_time,company) VALUES ('".$_REQUEST['msisdn']."','".$_REQUEST['text']."' ,NOW(),'$radio_name')";
                               
                               if(!mysqli_query($conn, $query))
                                {
                                file_put_contents($log, chr(10).'-- Database error: '.mysqli_error(), FILE_APPEND);
                                }
                                
                                
                        }        
                       exit();
                }  // for the radio 
                
                if ($shorti=='34040'){
                   $str = $_REQUEST['text'];
                   
                   $count = substr_count( $str,",");
                  // echo "   Shortcode ".$shorti."   ".$message;
                   
                   if ($count >=2){
                    
                          $arr = explode(",", $str);
                          
                          $constituency_no = $arr[0];
                          $party = $arr[1];
                          $votes = $arr[2];
                          $query = "SELECT * from votes_staging where constituency_no=$constituency_no and party='$party'";
                          $res = mysqli_query($conn_lesothi, $query);
                          $num_results = mysqli_num_rows($res);
                          $r = mysqli_fetch_assoc($res);  
                          $vote_id = $r['id'];
                          if ($num_results > 0){
                         
                              
                            $query  = "SELECT msisdn from whitelist";
                            if(!($results = $conn_lesothi->query($query)))
                             {
                                     file_put_contents($log, chr(10).chr(9).'-- Database error: '.$conn->error().chr(10), FILE_APPEND);
                                     return 1;
                             }//end if
                             // $blacklist = array();
                            //  echo "  Whitelist ".PHP_EOL;
                             while( $row = mysqli_fetch_array( $results)){
                               $blacklist[] = $row['msisdn']; // Inside while loop
                             //  echo "  ".$row['msisdn']."".PHP_EOL;
                             }
                          
                         if (in_array($_REQUEST['msisdn'], $blacklist)){  //check whitelist  
                              
                             $sql = "UPDATE votes set votes_staging=$votes, msisdn='".$_REQUEST['msisdn']."' where id=$vote_id";
                             
                             if(!($results = $conn_lesothi->query($query)))  {
                                     file_put_contents($log, chr(10).chr(9).'-- Database error: '.$conn->error().chr(10), FILE_APPEND);
                                     return 1;
                              }//end if
                              
                          $query = "SELECT * from constituency where constituency_no=".$constituency_no."";
                          
                          $results = mysqli_query($conn_lesothi, $query);
                          $num_results = mysqli_num_rows($results);
                          $const_name ='';
                          if ($num_results > 0){
                           $row = mysqli_fetch_assoc($results);
                           $const_name = $row['constituency_name'];
                          } 
       
                                $message = " Results for ".$const_name." Number ".$constituency_no." has been recorde";

                            //   echo "  Message ".$message;
                                 $query = "INSERT INTO outgoing_sms (msisdn, from_add, text, created, queued, network_id)
                                  VALUES ('".$_REQUEST['msisdn']."','".$_REQUEST['short-code']."', '$message', NOW(), '0', '".$_REQUEST['network-id']."')";
                                 if(!mysqli_query($conn, $query))
                                 {
                                         file_put_contents($log, chr(10).'-- Database error: '.mysqli_error(), FILE_APPEND);
                                 }
                                 $query = "INSERT INTO smsmo (network_id, sender, receiver, keyword, created)
                                 VALUES ('".$_REQUEST['network-id']."', '".$_REQUEST['msisdn']."', '".$_REQUEST['short-code']."', '".$_REQUEST['text']."', NOW())";

                                 if(!mysqli_query($conn, $query))
                                 {
                                         file_put_contents($log, chr(10).'-- Database error: '.mysqli_error(), FILE_APPEND);
                                 }      
                           }  // end whitelist
                           else{
                                $message = "You are not allowed to send us election results:";
                                $query = "INSERT INTO outgoing_sms (msisdn, from_add, text, created, queued, network_id)
                                VALUES ('".$_REQUEST['msisdn']."','".$_REQUEST['short-code']."', '$message', NOW(), '0', '".$_REQUEST['network-id']."')";
                               if(!mysqli_query($conn, $query))
                               {
                                       file_put_contents($log, chr(10).'-- Database error: '.mysqli_error(), FILE_APPEND);
                               }
                               $query = "INSERT INTO smsmo (network_id, sender, receiver, keyword, created)
                               VALUES ('".$_REQUEST['network-id']."', '".$_REQUEST['msisdn']."', '".$_REQUEST['short-code']."', '".$_REQUEST['text']."', NOW())";

                               if(!mysqli_query($conn, $query))
                               {
                                       file_put_contents($log, chr(10).'-- Database error: '.mysqli_error(), FILE_APPEND);
                               }  
                           }          
                         } //  
                         else {    // new enttry into the votes table
                            
                             
                             
                             
                             
                            $query  = "SELECT msisdn from whitelist";
                            if(!($results = $conn_lesothi->query($query)))
                             {
                                     file_put_contents($log, chr(10).chr(9).'-- Database error: '.$conn->error().chr(10), FILE_APPEND);
                                     return 1;
                             }//end if
                             // $blacklist = array();
                            //  echo "  Whitelist ".PHP_EOL;
                             while( $row = mysqli_fetch_array( $results)){
                               $blacklist[] = $row['msisdn']; // Inside while loop
                             //  echo "  ".$row['msisdn']."".PHP_EOL;
                             }
                          
                         if (in_array($_REQUEST['msisdn'], $blacklist)){  //check whitelist
                          
                          $query =  "INSERT INTO votes_staging (constituency_no, party, number_votes, msisdn, date_created) VALUES($constituency_no, '$party', $votes, '".$_REQUEST['msisdn']."', NOW())";
                           //echo $query;
                          if(!mysqli_query($conn_lesothi, $query)) {
					file_put_contents($log, chr(10).'-- Database error: '.mysqli_error(), FILE_APPEND);
		          }
                          
                          $query = "SELECT * from constituency where constituency_no=".$constituency_no."";
                          
                          $results = mysqli_query($conn_lesothi, $query);
                          $num_results = mysqli_num_rows($results);
                          $const_name ='';
                          if ($num_results > 0){
                           $row = mysqli_fetch_assoc($results);
                           $const_name = $row['constituency_name'];
                          } 
       
                          $message = " Results for ".$const_name." Number ".$constituency_no." has been recorde";
                          
                       //   echo "  Message ".$message;
                            $query = "INSERT INTO outgoing_sms (msisdn, from_add, text, created, queued, network_id)
                             VALUES ('".$_REQUEST['msisdn']."','".$_REQUEST['short-code']."', '$message', NOW(), '0', '".$_REQUEST['network-id']."')";
                            if(!mysqli_query($conn, $query))
                            {
                                    file_put_contents($log, chr(10).'-- Database error: '.mysqli_error(), FILE_APPEND);
                            }
                            $query = "INSERT INTO smsmo (network_id, sender, receiver, keyword, created)
                            VALUES ('".$_REQUEST['network-id']."', '".$_REQUEST['msisdn']."', '".$_REQUEST['short-code']."', '".$_REQUEST['text']."', NOW())";

                            if(!mysqli_query($conn, $query))
                            {
                                    file_put_contents($log, chr(10).'-- Database error: '.mysqli_error(), FILE_APPEND);
                            }
                            
                            // this section notifies the approver of the pending approval.
                            
                            $query  = "SELECT msisdn from notifiers";
                            if(!($result1 = $conn_lesothi->query($query)))
                             {
                                     file_put_contents($log, chr(10).chr(9).'-- Database error: '.$conn->error().chr(10), FILE_APPEND);
                                     return 1;
                             }//end if
                             // $blacklist = array();
                            //  echo "  Whitelist ".PHP_EOL;
                             
                             
                             while( $row = mysqli_fetch_array( $result1)){
                               $notifier[] = $row['msisdn']; // Inside while loop
                             //  echo "  ".$row['msisdn']."".PHP_EOL;
                                $message = "Please accept or decline the vote which just came in";                                 
				$sample = "INSERT INTO bulkmessages(id, message, msisdn, queued, message_date, company, username) "
                                        . "VALUES('','$message','".$row['msisdn']."', 'Q','now()','LENA','ADMIN')";
					
				
				if(!$conn->query($sample))
				{
					file_put_contents($log, chr(9).'-- Database error: '.$conn->error.chr(10), FILE_APPEND);
				
                                        return 1;
                                }

                             }
                            
                         } // check if whitelised number to send, else do nothing. 
                         else{
                             
                              $message = "You are not allowed to send us election results:";
                             $query = "INSERT INTO outgoing_sms (msisdn, from_add, text, created, queued, network_id)
                             VALUES ('".$_REQUEST['msisdn']."','".$_REQUEST['short-code']."', '$message', NOW(), '0', '".$_REQUEST['network-id']."')";
                            if(!mysqli_query($conn, $query))
                            {
                                    file_put_contents($log, chr(10).'-- Database error: '.mysqli_error(), FILE_APPEND);
                            }
                            $query = "INSERT INTO smsmo (network_id, sender, receiver, keyword, created)
                            VALUES ('".$_REQUEST['network-id']."', '".$_REQUEST['msisdn']."', '".$_REQUEST['short-code']."', '".$_REQUEST['text']."', NOW())";

                            if(!mysqli_query($conn, $query))
                            {
                                    file_put_contents($log, chr(10).'-- Database error: '.mysqli_error(), FILE_APPEND);
                            }
                         }
                             
                       }
                   }  // end of elections if statement
                   else if (strtoupper($str) == 'BATLA'){
                       
                         $message = getMenu(0, $conn);
                                           
                    } 
                    else {

                         $message = getChild($_REQUEST['text'], $conn);
                    
                    } 

                                $query = "INSERT INTO outgoing_sms (msisdn, from_add, text, created, queued, network_id)
                                 VALUES ('".$_REQUEST['msisdn']."','".$_REQUEST['short-code']."', '$message', NOW(), '0', '".$_REQUEST['network-id']."')";
                                if(!mysqli_query($conn, $query))
				{
					file_put_contents($log, chr(10).'-- Database error: '.mysqli_error(), FILE_APPEND);
				}
				$query = "INSERT INTO smsmo (network_id, sender, receiver, keyword, created)
				VALUES ('".$_REQUEST['network-id']."', '".$_REQUEST['msisdn']."', '".$_REQUEST['short-code']."', '".$_REQUEST['text']."', NOW())";

				if(!mysqli_query($conn, $query))
				{
					file_put_contents($log, chr(10).'-- Database error: '.mysqli_error(), FILE_APPEND);
				}


				$query="INSERT INTO premium_transactions (celnumber,shortcode,price,keyword,operator,message,company,message_date) VALUES ('".$_REQUEST['msisdn']."','".$_REQUEST['short-code']."','2.00' ,'".$_REQUEST['text']."','".$_REQUEST['network-id']."','$message','Splitz Marketing',now())";


                                if(!mysqli_query($conn, $query))
				{
					file_put_contents($log, chr(10).'-- Database error: '.mysqli_error(), FILE_APPEND);
					exit();
				}

                           exit();
                  
                }  
             
       
     if ($shorti == '31012'){
         // echo " 31012  ";
               //$exp_date = "2017-11-19 23:10:00";
   // $exp_date1 = "2016-11-20 00:00:00";
               
           $local_company = get_company_from_keyword($conn, $_REQUEST['text']);
           $slogan ="";           
           $numvotes=0;
           if ($local_company=="Ultimate FM"){
                $exp_date = "2017-11-26 00:00:00";
               $numvotes = 15;
               $slogan = " Ultimate FM Music Awards 2017.";
           }         
        
            $todays_date = date("Y-m-d H:i:s");

               $today = strtotime($todays_date);
               $expiration_date = strtotime($exp_date);
               
           
                if (is_ultimate_keyword($conn, $_REQUEST['text'])){
                     
                //    echo "Ultimate keyword ".$_REQUEST['text'];
                          $query = "SELECT * FROM ultimate_nominees WHERE allocate_code='".strtoupper($_REQUEST['text'])."' and active=1";
                              if(!($rs = $conn->query($query))){
                                file_put_contents($log, chr(10).chr(9).'-- Database error: '.$conn->error().chr(10), FILE_APPEND);
                                return 1;
                              }//end if

                           if($rs->num_rows == 0){
                               $message = "This code is no longer allowed on this poll, thanks";
                           } else {                              
                               $row = $rs->fetch_assoc();
                               $new_name  =  trim($row['first_name']); // ." - ". trim($row['surname']); 

                                if (($expiration_date > $today ) ){
                                          $valid = "yes";

                                            $message1= "";
                                              for ($x = 1; $x <= $numvotes; $x++) {
                                               //   echo "  inside ";
                                                   save_ULTIMATE($conn, $row['category_code'], $new_name, $_REQUEST['msisdn']);	 
                                              } 
                                            $message1 = $numvotes." votes in the";
                                            $message = "Thank you for voting ".$new_name.". with ".$message1."".$slogan;


                                  }         
                                  else {

                                       $message = "The voting has closed, and your vote will not be counted";   

                                 }
                    
                     
               
                    }                    
                
                }
                
                
                if($message == ""){
                    $message = "Invalid or unknown keyword";
                }
                
               $query = "INSERT INTO outgoing_sms (msisdn, from_add, text, created, queued, network_id)
                             VALUES ('".$_REQUEST['msisdn']."','".$_REQUEST['short-code']."', '$message', NOW(), '0', '".$_REQUEST['network-id']."')";
                if(!mysqli_query($conn, $query))
                {
                        file_put_contents($log, chr(10).'-- Database error: '.mysqli_error(), FILE_APPEND);
                }

                $query = "INSERT INTO smsmo (network_id, sender, receiver, keyword, created)
                            VALUES ('".$_REQUEST['network-id']."', '".$_REQUEST['msisdn']."', '".$_REQUEST['short-code']."', '".$_REQUEST['text']."', NOW())";

                if(!mysqli_query($conn, $query))
                {
                        file_put_contents($log, chr(10).'-- Database error: '.mysqli_error(), FILE_APPEND);
                }
                $query="INSERT INTO premium_transactions (celnumber,shortcode,price,keyword,operator,message,company,message_date) VALUES ('".$_REQUEST['msisdn']."','".$_REQUEST['short-code']."','$smscost' ,'".$_REQUEST['text']."','".$_REQUEST['network-id']."','$message','Splitz Marketing',now())";

                if(!mysqli_query($conn, $query))
                {
                        file_put_contents($log, chr(10).'-- Database error: '.mysqli_error(), FILE_APPEND);
                        exit();
                }
                
                exit();
       } // shortcode == 31012        
             

        if ($shorti=='31015'){

         $exp_date = "2017-08-26 00:00:00";

       
         $todays_date = date("Y-m-d H:i:s");

         $today = strtotime($todays_date);
         $expiration_date = strtotime($exp_date);
         $expiration_date1 = strtotime($exp_date1); 
          $query = "SELECT * FROM ultimate_nominees WHERE allocate_code='".strtoupper($_REQUEST['text'])."' and active=1";

               if(!($rs = $conn->query($query))){
			file_put_contents($log, chr(10).chr(9).'-- Database error: '.$conn->error().chr(10), FILE_APPEND);
			return 1;
		}//end if

              if($rs->num_rows > 0)
              { 
                  $row = $rs->fetch_assoc();
                  $new_name  =  trim($row['first_name']);// ." - ". trim($row['surname']); 
             
             // if (($expiration_date > $today) &&($row['category_code'] == "SOY") ){
              if (($expiration_date > $today) ){

               $valid = "yes";
              


                     $state = save_ULTIMATE($conn, $row['category_code'], $new_name, $_REQUEST['msisdn']);
                    $message = "Thank you for voting ".$new_name." in the Finite WO-MEN Awards 2017";           
                  
                } 
              else {
                     $message = "The voting has closed, and your vote will not be counted";           

                }
              }else{
                  $message = "Your Vote won't be counted since this candidate is not in top 3, thank you for your support";
              }   
                    $query = "INSERT INTO outgoing_sms (msisdn, from_add, text, created, queued, network_id)
                            VALUES ('".$_REQUEST['msisdn']."','".$_REQUEST['short-code']."', '$message', NOW(), '0', '".$_REQUEST['network-id']."')";
                    if(!mysqli_query($conn, $query))
                    {
                            file_put_contents($log, chr(10).'-- Database error: '.mysqli_error(), FILE_APPEND);
                    }

                    $query = "INSERT INTO smsmo (network_id, sender, receiver, keyword, created)
				VALUES ('".$_REQUEST['network-id']."', '".$_REQUEST['msisdn']."', '".$_REQUEST['short-code']."', '".$_REQUEST['text']."', NOW())";

                    if(!mysqli_query($conn, $query))
                    {
                            file_put_contents($log, chr(10).'-- Database error: '.mysqli_error(), FILE_APPEND);
                    }
                    $query="INSERT INTO premium_transactions (celnumber,shortcode,price,keyword,operator,message,company,message_date) VALUES ('".$_REQUEST['msisdn']."','".$_REQUEST['short-code']."','$smscost' ,'".$_REQUEST['text']."','".$_REQUEST['network-id']."','$message','Ultimate FM',now())";

                    if(!mysqli_query($conn, $query))
                    {
                            file_put_contents($log, chr(10).'-- Database error: '.mysqli_error(), FILE_APPEND);
                            exit();
                    }

                 exit(); 
             }  // end Finite Awards project

       
       
       
      /* if ($shorti=='31018'){

         $exp_date = "2017-10-27 23:59:00";

       
         $todays_date = date("Y-m-d H:i:s");

         $today = strtotime($todays_date);
         $expiration_date = strtotime($exp_date);
         $expiration_date1 = strtotime($exp_date1); 
          $query = "SELECT * FROM ultimate_nominees WHERE allocate_code='".strtoupper($_REQUEST['text'])."'";

               if(!($rs = $conn->query($query))){
			file_put_contents($log, chr(10).chr(9).'-- Database error: '.$conn->error().chr(10), FILE_APPEND);
			return 1;
		}//end if

             
                  $row = $rs->fetch_assoc();
                  $new_name  =  trim($row['first_name']);// ." - ". trim($row['surname']); 
             
        // if (($expiration_date > $today) &&($row['category_code'] == "SOY") ){
         if (($expiration_date > $today) ){

               $valid = "yes";
              


                     $state = save_ULTIMATE($conn, $row['category_code'], $new_name, $_REQUEST['msisdn']);
                    $message = "Thank you for voting ".$new_name." in the Alliance Sports Media Awards 2017";           
                  
                } 
              else {
                     $message = "The voting has closed, and your vote will not be counted";           

                }
                
                    $query = "INSERT INTO outgoing_sms (msisdn, from_add, text, created, queued, network_id)
                            VALUES ('".$_REQUEST['msisdn']."','".$_REQUEST['short-code']."', '$message', NOW(), '0', '".$_REQUEST['network-id']."')";
                    if(!mysqli_query($conn, $query))
                    {
                            file_put_contents($log, chr(10).'-- Database error: '.mysqli_error(), FILE_APPEND);
                    }

                    $query = "INSERT INTO smsmo (network_id, sender, receiver, keyword, created)
				VALUES ('".$_REQUEST['network-id']."', '".$_REQUEST['msisdn']."', '".$_REQUEST['short-code']."', '".$_REQUEST['text']."', NOW())";

                    if(!mysqli_query($conn, $query))
                    {
                            file_put_contents($log, chr(10).'-- Database error: '.mysqli_error(), FILE_APPEND);
                    }
                    $query="INSERT INTO premium_transactions (celnumber,shortcode,price,keyword,operator,message,company,message_date) VALUES ('".$_REQUEST['msisdn']."','".$_REQUEST['short-code']."','$smscost' ,'".$_REQUEST['text']."','".$_REQUEST['network-id']."','$message','Ultimate FM',now())";

                    if(!mysqli_query($conn, $query))
                    {
                            file_put_contents($log, chr(10).'-- Database error: '.mysqli_error(), FILE_APPEND);
                            exit();
                    }

                 exit(); 
             }   end Ultimate FM project
             
       if ($shorti=='31013'){

         $exp_date = "2017-09-30 23:59:00";

       
         $todays_date = date("Y-m-d H:i:s");

         $today = strtotime($todays_date);
         $expiration_date = strtotime($exp_date);
         $expiration_date1 = strtotime($exp_date1); 
          $query = "SELECT * FROM ultimate_nominees WHERE allocate_code='".strtoupper($_REQUEST['text'])."' and active=1";

               if(!($rs = $conn->query($query))){
			file_put_contents($log, chr(10).chr(9).'-- Database error: '.$conn->error().chr(10), FILE_APPEND);
			return 1;
		}//end if

             
                  $row = $rs->fetch_assoc();
                  $new_name  =  trim($row['first_name']);// ." - ". trim($row['surname']); 
             
        // if (($expiration_date > $today) &&($row['category_code'] == "SOY") ){
         if (($expiration_date > $today) ){

               $valid = "yes";
              


                     $state = save_ULTIMATE($conn, $row['category_code'], $new_name, $_REQUEST['msisdn']);
                    $message = "Thank you for voting ".$new_name." in the Butha-Buthe Excellence Awards 2017";           
                  
                } 
              else {
                     $message = "The voting has closed, and your vote will not be counted";           

                }
                
                    $query = "INSERT INTO outgoing_sms (msisdn, from_add, text, created, queued, network_id)
                            VALUES ('".$_REQUEST['msisdn']."','".$_REQUEST['short-code']."', '$message', NOW(), '0', '".$_REQUEST['network-id']."')";
                    if(!mysqli_query($conn, $query))
                    {
                            file_put_contents($log, chr(10).'-- Database error: '.mysqli_error(), FILE_APPEND);
                    }

                    $query = "INSERT INTO smsmo (network_id, sender, receiver, keyword, created)
				VALUES ('".$_REQUEST['network-id']."', '".$_REQUEST['msisdn']."', '".$_REQUEST['short-code']."', '".$_REQUEST['text']."', NOW())";

                    if(!mysqli_query($conn, $query))
                    {
                            file_put_contents($log, chr(10).'-- Database error: '.mysqli_error(), FILE_APPEND);
                    }
                    $query="INSERT INTO premium_transactions (celnumber,shortcode,price,keyword,operator,message,company,message_date) VALUES ('".$_REQUEST['msisdn']."','".$_REQUEST['short-code']."','$smscost' ,'".$_REQUEST['text']."','".$_REQUEST['network-id']."','$message','Ultimate FM',now())";

                    if(!mysqli_query($conn, $query))
                    {
                            file_put_contents($log, chr(10).'-- Database error: '.mysqli_error(), FILE_APPEND);
                            exit();
                    }

                 exit(); 
        }   end Ultimate FM project 31013 */ 
             
        if ($shorti=='31019'){

         $exp_date = "2017-11-18 23:10:00";

       
         $todays_date = date("Y-m-d H:i:s");

         $today = strtotime($todays_date);
         $expiration_date = strtotime($exp_date);
         $expiration_date1 = strtotime($exp_date1); 
          $query = "SELECT * FROM ultimate_nominees WHERE allocate_code='".strtoupper($_REQUEST['text'])."' and active=1";

               if(!($rs = $conn->query($query))){
			file_put_contents($log, chr(10).chr(9).'-- Database error: '.$conn->error().chr(10), FILE_APPEND);
			return 1;
		}//end if

          if($rs->num_rows == 0){
           $message = "This code is no longer allowed on this voting line, it was excluded";   
          } else {  
          $row = $rs->fetch_assoc();
          $new_name  =  trim($row['first_name']);// ." - ". trim($row['surname']); 
             
                  
          if ($new_name == ""){
              $message ="Invalid code";   
          }else if (($expiration_date > $today) ){

               $valid = "yes";
              


                     $state = save_ULTIMATE($conn, $row['category_code'], $new_name, $_REQUEST['msisdn']);
                    $message = "Thank you for voting ".$new_name." in the Ultimate FM Music Awards 2017";           
                  
                } 
              else {
                     $message = "The voting has closed, and your vote will not be counted";           

                }
          }   
                    $query = "INSERT INTO outgoing_sms (msisdn, from_add, text, created, queued, network_id)
                            VALUES ('".$_REQUEST['msisdn']."','".$_REQUEST['short-code']."', '$message', NOW(), '0', '".$_REQUEST['network-id']."')";
                    if(!mysqli_query($conn, $query))
                    {
                            file_put_contents($log, chr(10).'-- Database error: '.mysqli_error(), FILE_APPEND);
                    }

                    $query = "INSERT INTO smsmo (network_id, sender, receiver, keyword, created)
				VALUES ('".$_REQUEST['network-id']."', '".$_REQUEST['msisdn']."', '".$_REQUEST['short-code']."', '".$_REQUEST['text']."', NOW())";

                    if(!mysqli_query($conn, $query))
                    {
                            file_put_contents($log, chr(10).'-- Database error: '.mysqli_error(), FILE_APPEND);
                    }
                    $query="INSERT INTO premium_transactions (celnumber,shortcode,price,keyword,operator,message,company,message_date) VALUES ('".$_REQUEST['msisdn']."','".$_REQUEST['short-code']."','$smscost' ,'".$_REQUEST['text']."','".$_REQUEST['network-id']."','$message','Ultimate FM',now())";

                    if(!mysqli_query($conn, $query))
                    {
                            file_put_contents($log, chr(10).'-- Database error: '.mysqli_error(), FILE_APPEND);
                            exit();
                    }

                 exit(); 
             } 
                
            /* if ($shorti=='31019') {

                  // check if splitzcup or something 
                 
               $str = $_REQUEST['text'];
                   
                   $count = substr_count( $str,",");
                 
                 if ($count>=1){
                       $arr = explode(",", $str); 
                       $results = $arr[0];
                       $constituency_no = $arr[1];
                      
                       
                       
                       if (is_numeric($constituency_no))  
                       {
                          $query = "SELECT * FROM constituency WHERE constituency_no=".$constituency_no." ";
                          
                       } else{
                            $query = "SELECT * FROM constituency WHERE constituency_name='".$constituency_no."'";
                           
                       } 
                      
                     //  echo " ".$query;
                       if(!($res = $conn_lesothi->query($query))){
                               file_put_contents($log, chr(10).chr(9).'-- Database error: '.$conn->error().chr(10), FILE_APPEND);
                               return 1;
                       }//end if

                        $rows = $res->fetch_assoc();
                       
                       $const_name = $rows['constituency_name'];
                       $const_no = $rows['constituency_no'];
                       
                       
                       $message = "Published results for ".$const_name." ";
                       
                       
                       
                       $query = "SELECT * FROM votes_approved WHERE constituency_no=".$constituency_no."";

                       if(!($rs = $conn_lesothi->query($query))){
                               file_put_contents($log, chr(10).chr(9).'-- Database error: '.$conn->error().chr(10), FILE_APPEND);
                               return 1;
                       }//end if

                      
                         if($rs->num_rows > 0)
                          { 
             
                        	for($data = 0; $data < $rs->num_rows; $data++){
				   $rs->data_seek($data);				
				   $row = $rs->fetch_assoc();
                                   $message .= " ".$row['party']." ".$row['number_votes']."".PHP_EOL;
                                
                                }
                      //  $message .= " the final results will be publishe by IEC";
                              //  echo " Message ".$message;
                          }
 
                        
                        
                        

                       $query = "INSERT INTO outgoing_sms (msisdn, from_add, text, created, queued, network_id)
                               VALUES ('".$_REQUEST['msisdn']."','".$_REQUEST['short-code']."', '$message', NOW(), '0', '".$_REQUEST['network-id']."')";
                       if(!mysqli_query($conn, $query))
                       {
                               file_put_contents($log, chr(10).'-- Database error: '.mysqli_error(), FILE_APPEND);
                       }

                       $query = "INSERT INTO smsmo (network_id, sender, receiver, keyword, created)
                                   VALUES ('".$_REQUEST['network-id']."', '".$_REQUEST['msisdn']."', '".$_REQUEST['short-code']."', '".$_REQUEST['text']."', NOW())";

                       if(!mysqli_query($conn, $query))
                       {
                               file_put_contents($log, chr(10).'-- Database error: '.mysqli_error(), FILE_APPEND);
                       }
                       $query="INSERT INTO premium_transactions (celnumber,shortcode,price,keyword,operator,message,company,message_date) VALUES ('".$_REQUEST['msisdn']."','".$_REQUEST['short-code']."','$smscost' ,'".$_REQUEST['text']."','".$_REQUEST['network-id']."','$message','Ultimate FM',now())";

                       if(!mysqli_query($conn, $query))
                       {
                               file_put_contents($log, chr(10).'-- Database error: '.mysqli_error(), FILE_APPEND);
                               exit();
                       }

                 } 
                 else if(is_splitzcup($conn, $_REQUEST['text'])) {
                     $query = "SELECT id, name from teams where keyword ='".$_REQUEST['text']."'";
                                $results = mysqli_query($conn, $query);
                                $num_results = mysqli_num_rows($results);
                               if ($num_results > 0){
                                $row = mysqli_fetch_assoc($results);                        

                                $team_name = trim($row['name']);
                                $team_id = $row['id'];

                                if (strlen($team_name) < 50){
                                   if (strpos($team_name," ") !== false) {                           
                                       $team_name1 = str_replace(" ", "_", $team_name);
                                   }else{
                                       $team_name1 = $team_name;
                                   }       
                                }


                                     save_votes($_REQUEST['msisdn'], $team_name1, $team_id, $conn);
                                     $message = $team_name." vote recorded.";





                                    if (checkAvailableAdvert($conn)){
                                     $advert = getAdvert($conn);
                                     $message = $message."(AD) ".$advert;  
                                    }            

                                 $query = "INSERT INTO outgoing_sms (msisdn, from_add, text, created, queued, network_id) 
                                 VALUES ('".$_REQUEST['msisdn']."','".$_REQUEST['short-code']."', '$message', NOW(), '0', '".$_REQUEST['network-id']."')";
                             if(!mysqli_query($conn, $query))
                                 {
                                         file_put_contents($log, chr(10).'-- Database error: '.mysqli_error(), FILE_APPEND);
                                 }
                                 $query = "INSERT INTO smsmo (network_id, sender, receiver, keyword, created)
                                 VALUES ('".$_REQUEST['network-id']."', '".$_REQUEST['msisdn']."', '".$_REQUEST['short-code']."', '".$_REQUEST['text']."', NOW())";

                                 if(!mysqli_query($conn, $query))
                                 {
                                         file_put_contents($log, chr(10).'-- Database error: '.mysqli_error(), FILE_APPEND);
                                 }
                                 $query="INSERT INTO premium_transactions (celnumber,shortcode,price,keyword,operator,message,company,message_date) VALUES ('".$_REQUEST['msisdn']."','".$_REQUEST['short-code']."','2.00' ,'".$_REQUEST['text']."','".$_REQUEST['network-id']."','$message','Splitz Marketing',now())";

                                 if(!mysqli_query($conn, $query))
                                 {
                                         file_put_contents($log, chr(10).'-- Database error: '.mysqli_error(), FILE_APPEND);
                                         exit();
                                 }

                                 $query="INSERT INTO team_".$team_name1."(id, cell_no, date_voted, network_id) VALUES('','".$_REQUEST['msisdn']."',NOW(),'".$_REQUEST['network-id']."')";
                                 if(!mysqli_query($conn, $query))
                                 {
                                         file_put_contents($log, chr(10).'-- Database error: '.mysqli_error(), FILE_APPEND);
                                 }
                                 exit();

                             }else{

                                  save_votes($_REQUEST['msisdn'], "Spoiled_Vote", 36, $conn);
                                  $query="INSERT INTO team_Spoiled_Vote (id, cell_no, date_voted, network_id) VALUES('','".$_REQUEST['msisdn']."',NOW(),'".$_REQUEST['network-id']."')"; 
                                  if(!mysqli_query($conn, $query))
                                     {
                                             file_put_contents($log, chr(10).'-- Database error: '.mysqli_error(), FILE_APPEND);
                                     }
                                            // exit();

                                 $dumbMsg = "Unknown keyword. ";

                                 $query = "INSERT INTO outgoing_sms (msisdn, from_add, text, created, queued, network_id)
                                 VALUES ('".$_REQUEST['msisdn']."','".$_REQUEST['short-code']."', '$dumbMsg', NOW(), '0', '".$_REQUEST['network-id']."')";
                                 if(!mysqli_query($conn, $query))
                                 {
                                         file_put_contents($log, chr(10).'-- Database error: '.mysqli_error(), FILE_APPEND);
                                 }

                                 $query = "INSERT INTO smsmo (network_id, sender, receiver, keyword, created)
                                 VALUES ('".$_REQUEST['network-id']."', '".$_REQUEST['msisdn']."', '".$_REQUEST['short-code']."', '".$_REQUEST['text']."', NOW())";

                                 if(!mysqli_query($conn, $query))
                                 {
                                         file_put_contents($log, chr(10).'-- Database error: '.mysqli_error(), FILE_APPEND);
                                 }

                                 $query="INSERT INTO premium_transactions (celnumber,shortcode,price,keyword,operator,message,company,message_date) VALUES ('".$_REQUEST['msisdn']."','".$_REQUEST['short-code']."','2.00' ,'".$_REQUEST['text']."','".$_REQUEST['network-id']."','$dumbMsg','Splitz Marketing',now())";
                                 if(!mysqli_query($conn, $query))
                                 {
                                 file_put_contents($log, chr(10).'-- Database error: '.mysqli_error(), FILE_APPEND);
                                 }
                                 exit();

                                }
                 

                                
                         }   
                                
                } *///end of 31019
                
                
                else {   
                    // echo "Existing problem for radio sms";
                    
                    $message = unknown_keyword_message($conn, $shorti);
                    
                    if ($message == ""){
		       $dumbMsg = "Thank you for your contribution";
                    } else {
                        $dumbMsg = $message;
                               
                    }
                    
                    $query = "INSERT INTO outgoing_sms (msisdn, from_add, text, created, queued, network_id) 
                    VALUES ('".$_REQUEST['msisdn']."','".$_REQUEST['short-code']."', '$dumbMsg', NOW(), '0', '".$_REQUEST['network-id']."')";
                    if(!mysqli_query($conn, $query))
                    {
                            file_put_contents($log, chr(10).'-- Database error: '.mysqli_error(), FILE_APPEND);
                    }
                    
                    $query = "INSERT INTO smsmo (network_id, sender, receiver, keyword, created) 
                    VALUES ('".$_REQUEST['network-id']."', '".$_REQUEST['msisdn']."', '".$_REQUEST['short-code']."', '".$_REQUEST['text']."', NOW())";

                    if(!mysqli_query($conn, $query))
                    {
                            file_put_contents($log, chr(10).'-- Database error: '.mysqli_error(), FILE_APPEND);
                    }

                    $query="INSERT INTO premium_transactions (celnumber,shortcode,price,keyword,operator,message,company,message_date) VALUES ('".$_REQUEST['msisdn']."','".$_REQUEST['short-code']."','$smscost' ,'".$_REQUEST['text']."','".$_REQUEST['network-id']."','$dumbMsg','Splitz Marketing',now())";
                    if(!mysqli_query($conn, $query))
                    {
                    file_put_contents($log, chr(10).'-- Database error: '.mysqli_error(), FILE_APPEND);
                    }
                    exit();
                }
        }	
}
else{

   echo "everything is wrong";
}



function get_company_from_keyword($conn, $keyword){
    $company = "";
    $words = remove_numbers($keyword);
    
    $query= "SELECT  * from mobile_poll where quest='$words'";
     $results = mysqli_query($conn, $query);
   // echo $query." <br>";
     $num_results = mysqli_num_rows($results);
     if($num_results > 0){
         $is_ultimate = true;
           $row = mysqli_fetch_assoc($results); 
           $company = $row['company'];
     //   echo " is ultiate keyword".$company;
     }




    return $company;
}


function is_keyword_has_advert($conn, $keyword){
  $is_ultimate = false;
  $key_upper = strtoupper($keyword); 
  
    $query= "SELECT ps.keyword, ps.company, ps.price, ad.advert_wording FROM premium_services ps, adverts ad WHERE ps.keyword='$key_upper' OR ps.company = ad.company";
     $results = mysqli_query($conn, $query);
    //echo $query." <br>";
     $num_results = mysqli_num_rows($results);
     if($num_results > 0){
         $is_ultimate = true;
     
//        echo " is keyword has advert ";
     }




    return $is_ultimate;
}




function is_company_has_advert($conn, $company){
    //$company = "";
  $is_ultimate = false;
    // $key_upper = strtolower($company); 
//echo $company;  
    $query= "SELECT  * from adverts where company='$company'";
     $results = mysqli_query($conn, $query);
   // echo $query." <br>";
     $num_results = mysqli_num_rows($results);
     if($num_results > 0){
         $is_ultimate = true;
           $row = mysqli_fetch_assoc($results); 
           $company = $row['company'];
       // echo " is ultiate keyword".$company;
     }




    return $is_ultimate;
}

function get_company_from_campaign($conn, $keyword, $shortcode){
    $company = "";
    $words = remove_numbers($keyword);
     $key_upper = strtoupper($keyword);   
    $query= "SELECT  * from premium_services where shortcode='$shortcode' and keyword='$key_upper'";
     $results = mysqli_query($conn, $query);
   // echo $query." <br>";
     $num_results = mysqli_num_rows($results);
     if($num_results > 0){
         $is_ultimate = true;
           $row = mysqli_fetch_assoc($results); 
           $company = $row['company'];
     
     }




    return $company;
}



function is_ultimate_keyword($conn, $keyword){
   $is_ultimate = false;
   //echo "  examaning is ultimate keyword  ===> ".$keyword;
    $words = remove_numbers($keyword);
   //echo "  examaning is ultimate keyword  ===> ".$words;
    $query = "SELECT * from ultimate_nominees where allocate_code='$keyword'";
     $results = mysqli_query($conn, $query);
   // echo $query." <br>";
     $num_results = mysqli_num_rows($results);
     if($num_results > 0){
         $is_ultimate = true;
         
     //   echo " is ultiate keyword";
     }
return $is_ultimate;
   
}



function is_LASA_keyword($conn, $keyword){
   $is_ultimate = false;
   //echo "  examaning is ultimate keyword  ===> ".$keyword;
    $words = remove_numbers($keyword);
   //echo "  examaning is ultimate keyword  ===> ".$words;
    $query = "SELECT * from ultimate_nominees where allocate_code='$keyword'";
     $results = mysqli_query($conn, $query);
   // echo $query." <br>";
     $num_results = mysqli_num_rows($results);
     if($num_results > 0){
         $is_ultimate = true;
         
     //   echo " is ultiate keyword";
     }
return $is_ultimate;
   
}


function is_splitzcup($conn, $keyword){
   $is_ultimate = false;

  
    $query = "SELECT * from teams where keyword='$keyword'";
     $results = mysqli_query($conn, $query);

     $num_results = mysqli_num_rows($results);
     if($num_results > 0){
         $is_ultimate = true;       
 
     }
return $is_ultimate;
   
}



function remove_numbers($string) {
   return preg_replace('/[0-9]+/', "", $string);
}



function is_poll_shortcode($conn, $shortcode){
    $ispoll = false;
    $query = "SELECT * from poll_shortcode where shortcode='$shortcode'";
    $results = mysqli_query($conn, $query);
   // echo $query." <br>";
     $num_results = mysqli_num_rows($results);
     if ($num_results > 0){
         $ispoll = true;         
     //    echo " this is the poll shortcode indeed";
      }
     else 
      {
         $ispoll = false;
      }
    return $ispoll;
}

function is_radio_shortcode($conn, $shortcode){
    $ispoll = false;
    $query = "SELECT * from radio_shortcode where shortcode='$shortcode'";
    $results = mysqli_query($conn, $query);
   // echo $query." <br>";
     $num_results = mysqli_num_rows($results);
     if ($num_results > 0){
         $ispoll = true;         
     //    echo " this is the poll shortcode indeed";
      }
     else 
      {
         $ispoll = false;
      }
    return $ispoll;
}






function getpoll_id($conn, $shortcode){
    $poll_id = '0';
    $query = "SELECT * from poll_shortcode where shortcode='$shortcode'";
    $results = mysqli_query($conn, $query);
     $num_results = mysqli_num_rows($results);
      $row = mysqli_fetch_assoc($results); 
     //echo "got the results from ";
     if ($num_results > 0){
         $poll_id = $row['poll_id'];
        // echo "got the results from getPoll_id ";
     }
    return $poll_id;
}


function getRadio($conn, $shortcode){
    $station_name  = '';
    $query = "SELECT * from radio_shortcode where shortcode='$shortcode'";
   // echo $query;
    $results = mysqli_query($conn, $query);
     $num_results = mysqli_num_rows($results);
      $row = mysqli_fetch_assoc($results); 
     //echo "got the results from ";
     if ($num_results > 0){
         $station_name = $row['radion_station_name'];
       // echo "got the results from station name ".$station_name;
     }
    return $station_name ;
}
function savePoll($conn, $shortcode, $opt){
    //$pollname="";
   // $poll_id = getpoll_id($conn, $shortcode);
     $query1 = "SELECT * from poll_shortcode where shortcode='$shortcode'";
     $results1 = mysqli_query($conn, $query1);
     $num_results = mysqli_num_rows($results1);
     // $row = mysqli_fetch_assoc($results); 
      
      while ($row1 = mysqli_fetch_array($results1)) {
         $query = "SELECT * from cell_poll where id='".$row1['poll_id']."' and (opt1='$opt' or opt2='$opt' or opt3='$opt' or opt4='$opt')";
        // echo $query; 
         $results = mysqli_query($conn, $query);
         $num_results = mysqli_num_rows($results);
         //$row = mysqli_fetch_assoc($results); 

         if ($num_results > 0){

             $query2 = "INSERT INTO sms_poll_ans(ans_id, qst_id, opt) VALUES('','".$row1['poll_id']."','$opt')";
            // echo $query2;
               if(!mysqli_query($conn, $query2))
                    {
                            file_put_contents($log, chr(10).'-- Database error: '.mysqli_error(), FILE_APPEND);
                    }
         }  
      }      
    
    
    return 1;
}

function savePoll_two_words($conn, $shortcode,$cat ,$opt, $msisdn){
    
    $category = strtoupper($cat);
  // echo " Inside Savepoll ".$shortcode. "   Category code ".$category."  option ".$opt;
     $query1 = "SELECT * from mobile_poll_shortcode where shortcode='$shortcode' and (category_code='$category' or poll_id=$cat)";
     $results1 = mysqli_query($conn, $query1);
     $num_results = mysqli_num_rows($results1);
     
      
      while ($row1 = mysqli_fetch_array($results1)) {
         //$query = "SELECT * from mobile_poll where id='".$row1['poll_id']."' and quest ='$category' and (opt1='$opt' or opt2='$opt' or opt3='$opt' or opt4='$opt')";
         
          
          
          $poll = $row1['category_code'];
          
          $sql_category = "SELECT * FROM bam_categories where category_code='$poll'";
          $res = mysqli_query($conn, $sql_category);
          
          $poll_name ='';
          while ($r = mysqli_fetch_array($res)){
              $poll_name = $r['category'];
          }
        //  echo $poll_name; 
          
          $query = "SELECT * FROM mobile_poll where id='".$row1['poll_id']."'";
         // echo $query; 
         $results = mysqli_query($conn, $query);
         $num_results = mysqli_num_rows($results);
         //$row = mysqli_fetch_assoc($results); 

         if ($num_results > 0){

             $query2 = "INSERT INTO splitz_poll_ans(ans_id, poll_id, poll_name ,opt, msisdn) VALUES('',".$row1['poll_id'].",'$poll_name','$opt', '$msisdn')";
             //echo $query2;
               if(!mysqli_query($conn, $query2))
                    {
                            file_put_contents($log, chr(10).'-- Database error: '.mysqli_error(), FILE_APPEND);
                    }
         }  
      }      
    
    
    return 1;
}



function save_ULTIMATE($conn, $cat ,$opt, $msisdn){
    
    $return = false;
    $category = strtoupper($cat);
   
     $query1 = "SELECT * from mobile_poll_shortcode where category_code='$category'";
   //  echo $query1;
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
          //echo $poll_name; 
          
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
          

function save_ULTIMATE_FM($conn, $shortcode, $cat ,$opt, $msisdn){
    
    $category = strtoupper($cat);
  // echo " Inside Savepoll ".$shortcode. "   Category code ".$category."  option ".$opt;
     $query1 = "SELECT * from mobile_poll_shortcode where shortcode='$shortcode' and (category_code='$category' or poll_id=$cat)";
     $results1 = mysqli_query($conn, $query1);
     $num_results = mysqli_num_rows($results1);
     
      
      while ($row1 = mysqli_fetch_array($results1)) {
         //$query = "SELECT * from mobile_poll where id='".$row1['poll_id']."' and quest ='$category' and (opt1='$opt' or opt2='$opt' or opt3='$opt' or opt4='$opt')";
         
          
          
          $poll = $row1['category_code'];
          
          $sql_category = "SELECT * FROM ultimate_categories where category_code='$cat'";
          $res = mysqli_query($conn, $sql_category);
          
          $poll_name ='';
          while ($r = mysqli_fetch_array($res)){
              $poll_name = $r['category'];
          }
          echo $poll_name; 
          
          $query = "SELECT * FROM mobile_poll where ='".$row1['poll_id']."'";
          echo $query; 
         $results = mysqli_query($conn, $query);
         $num_results = mysqli_num_rows($results);
         //$row = mysqli_fetch_assoc($results); 

         if ($num_results > 0){

             $query2 = "INSERT INTO splitz_poll_ans(ans_id, poll_id, poll_name ,opt, msisdn) VALUES('',".$row1['poll_id'].",'$poll_name','$opt', '$msisdn')";
             echo $query2;
               if(!mysqli_query($conn, $query2))
                    {
                            file_put_contents($log, chr(10).'-- Database error: '.mysqli_error(), FILE_APPEND);
                    }
         }  
      }      
    
    
    return 1;
}






function save_poll_votes($conn, $answer, $poll){
    $query = "";
}

function save_votes($phonenumber,$name,$voted_for, $conn){

    create_table($name, $conn);
//INSERT INTO voters (phone_number, voted_for) VALUES (?, ?)
     $query = "INSERT INTO voters (id, phone_number, voted_for, latest_time_voted) 
                VALUES ('','$phonenumber','$voted_for',now())";
                if(!mysqli_query($conn, $query))
                {
                        file_put_contents($log, chr(10).'-- Database error: '.mysqli_error(), FILE_APPEND);
                }
   
       $query = "UPDATE teams SET votes=votes + 1 WHERE id='".$voted_for."'";
						
                if(!$conn->query($query))
                {
                        file_put_contents($log, chr(10).chr(9).'-- Error: '. $errno.' '.$errstr.chr(10), FILE_APPEND);
                        return 1;
                }         
    
                
// this function should save the vote for a specific team.... 
// this function update the votes for a team and insert a phone number into the teams table. 
    
    
    
    return;
}

function checkAvailableAdvert($conn){
    $available = 0;
    $query = "SELECT * from adverts where status=1";
      $results = mysqli_query($conn, $query);
     $num_results = mysqli_num_rows($results);
     if ($num_results > 0){
         $available = 1;
     }else{
         resetAdverts($conn);
     }
     
    return $available;
}

function resetAdverts($conn){
     
        $query = "UPDATE adverts SET status=1";
						
        if(!$conn->query($query))
        {
                file_put_contents($log, chr(10).chr(9).'-- Error: '. $errno.' '.$errstr.chr(10), FILE_APPEND);
                return 1;
        }       
    return;
}



function getAdvertKeyword($conn, $keyword){
    $advert_wording =  "";
    $key_upper = strtoupper($keyword);
    $query = "SELECT * from adverts where keyword='$key_upper' and active=1 and status=1 LIMIT 1";
 
      $results = mysqli_query($conn, $query);
     $num_results = mysqli_num_rows($results);
     $row = mysqli_fetch_assoc($results); 
     if ($num_results > 0){
          $advert_wording = $row['advert_wording'];
          $row_id = $row['id'];
          $used_count = $row['used_count']+1;
          $query = "UPDATE adverts SET status=0, used_count=$used_count WHERE id='".$row_id."'";
						
        if(!$conn->query($query))
        {
                file_put_contents($log, chr(10).chr(9).'-- Error: '. $errno.' '.$errstr.chr(10), FILE_APPEND);
                return 1;
        }         
     }
     
    return $advert_wording;
    
    
}

function getAdvert($conn, $company){
    $advert_wording =  
    $key_upper = strtolower($company);
    $query = "SELECT * from adverts where company='$key_upper' and active=1 and status=1 LIMIT 1";
 
      $results = mysqli_query($conn, $query);
     $num_results = mysqli_num_rows($results);
     $row = mysqli_fetch_assoc($results); 
     if ($num_results > 0){
          $advert_wording = $row['advert_wording'];
          $row_id = $row['id'];
          $used_count = $row['used_count']+1;
          $query = "UPDATE adverts SET status=0, used_count=$used_count WHERE id='".$row_id."'";
						
        if(!$conn->query($query))
        {
                file_put_contents($log, chr(10).chr(9).'-- Error: '. $errno.' '.$errstr.chr(10), FILE_APPEND);
                return 1;
        }         
     }
     
    return $advert_wording;
    
    
}





function get_num_of_words($string) {
    $string = preg_replace('/\s+/', ' ', trim($string));
    $words = explode(" ", $string);
    return count($words);
}

function get_token_no($string, $searchStr){
    
    $token  = strtok($string, $searchStr);
                        //echo $_REQUEST['text'];
    $count = 0;
                        
                        
     while ($token !== false) {      
      $count++;
      $token = strtok($searchStr);      
    }                    
    
    return $count;
}

function create_table($name, $conn){
   $create_table =
    "CREATE TABLE IF NOT EXISTS team_".$name."  
    (
    id INT NOT NULL AUTO_INCREMENT,
    cell_no VARCHAR(20) NOT NULL,
    date_voted DATETIME NULL,
    network_id VARCHAR(20) NULL,
    PRIMARY KEY(id)
)";

   if(!mysqli_query($conn, $create_table))
	{
			file_put_contents($log, chr(10).'-- Database error: '.mysqli_error(), FILE_APPEND);
	}
   
    return 1;
}


function getMenu($id, $conn){
    $menu = "";
    $s = "SELECT text, menu_code, parent_code FROM menu WHERE menu_code !=0 AND parent_code=0";
    
   // echo $s;
  //  SELECT menu_name, menu_code, parent_code FROM menu WHERE menu_code <> 0 AND parent_code = 0
     $results = mysqli_query($conn, $s);
     $num_results = mysqli_num_rows($results);
 
     while ($r = mysqli_fetch_array($results)){         
         
         $menu .= $r['menu_code'].".".$r['text']." ".PHP_EOL;
         
  
     }
     
     
    return $menu;
}
function getChild($id, $conn)	{
    $menu = "";
    $s = " SELECT text, menu_code, parent_code, menu_description FROM menu WHERE menu_code != 0 AND parent_code = $id";
  //  echo "   Select ".$s;
     $results = mysqli_query($conn, $s);
     $num_results = mysqli_num_rows($results);
   //  $row = mysqli_fetch_assoc($results);
     
     
     if ($num_results == 1){
       //  echo " single results set ";
         $row = mysqli_fetch_assoc($results);
         
       //  echo " menu code ===>".$row['menu_code'];
         if (children($row['menu_code'], $conn)){
              $menu .= $row['menu_code'].". ".$row['text']." ".PHP_EOL;
            //  echo  $menu;
         }else{
       //       $sql = " SELECT text, menu_code, parent_code, menu_description FROM menu WHERE parent_code = $id";
           //  echo "   Select ".$sql;
        //   $result = mysqli_query($conn, $sql);
         //  $rows = mysqli_fetch_assoc($result);
         
           $menu = $row['menu_description'];    
         }
         
         
     }else  if ($num_results > 1){
           while ($r = mysqli_fetch_assoc($results)){         
           $menu .= $r['menu_code'].". ".$r['text']." ".PHP_EOL;
        } 
     }else{
        // $menu = "No data for this input:::-> ".$id.PHP_EOL;
         $menu .= "  Could not find anything under this service code ".$id.", sms find to 34040";
     }
  
    return $menu;
}

function children($id, $conn){
    
    
  //  echo " Something about children on ".$id;
    $s = "SELECT text, menu_code, parent_code, menu_description FROM menu WHERE menu_code != 0 AND parent_code = $id";
 
     $results = mysqli_query($conn, $s);
     $num_results = mysqli_num_rows($results);
     if ($num_results > 1){
         
        // echo "   has children ";
         return  true;
   } else if ($num_results == 1) {
       //echo " No children";
       return true;
   }else{
       return false;
   }   
}

function has_token($string){
    
  //  echo '  inside token checker '.$string;
     //   $token  = strtok($rows[5], '/');
$word = strtok($string,'   ');
 if ( $word !== false ) {
     return true;
 }else{ 
    return false;
 }
}





function unknown_keyword_message($conn, $shortcode){
    
    $message = "";
       $s = "SELECT * FROM sms_shortcode_unknown WHERE shortcode = '$shortcode'";
  //  echo "   Select ".$s;
     $results = mysqli_query($conn, $s);
     $num_results = mysqli_num_rows($results);
   //  $row = mysqli_fetch_assoc($results);
     
     
     if ($num_results > 0){
         $row = mysqli_fetch_assoc($results);
         $message = $row['message'];
     }else{
        $message = "";
     }
  return $message;
}
    
    
    