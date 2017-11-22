<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of messageContent
 *
 * @author Frans
 */


require_once './class.Database.inc';


class MessageContent {
    //put your code here
    
    public $msisdn;
    public $network_id;
    public $textMessage;
    public $timein;
    public $shortcode;
    private $shorti;
    private $conn;
    private $smscost;
    
    
    function __construct($data = array()) {
        
       // echo " from constructor ";
        if (!is_array($data)){
            trigger_error("Unable to construct this class".  get_class($name));
        }
        if (count($data) > 0){
            foreach ($data as $name => $value) {
                    // Special case for protected properties.
                if (in_array($name, array(
                  'time_created',
                  'time_updated',
                ))) {
                  $name = '_' . $name;
                }
                $this->$name = $value;
                
               // echo "   name : ".$name."   value = ".$value;
            }
        }
    
       $db = new Database();
        
       $this->conn = $db->getConnection();
        
     }
    
    
    
    function __get($name) {
    // Postal code lookup if unset.
    
    
    // Attempt to return a protected property by name.
    $protected_property_name = '_' . $name;
    if (property_exists($this, $protected_property_name)) {
      return $this->$protected_property_name;
    }
    
    // Unable to access property; trigger error.
    trigger_error('Undefined property via __get: ' . $name);
    return NULL;
  }
  
  function __set($name, $value) {
    // Only set valid address type id.
    if ('address_type_id' == $name) {
      $this->_setAddressTypeId($value);
      return;
    }
    // Allow anything to set the postal code.
    if ('postal_code' == $name) {
      $this->$name = $value;
      return;
    }
    
    // Unable to access property; trigger error.
    trigger_error('Undefined or unallowed property via __set(): ' . $name);
  }
  
 public  function getRadio(){
     
      // $mysql = Database::getInstance();
     // $this->conn = $mysql->;
     
    // echo "  from getRadio  ".PHP_EOL;
    $station_name  = '';
    $query = "SELECT * from radio_shortcode where shortcode='$this->shortcode'";
   // echo $query;
    $results = mysqli_query($this->conn, $query);
     $num_results = mysqli_num_rows($results);
      $row = mysqli_fetch_assoc($results); 
  //  echo "got the results from ";
     if ($num_results > 0){
         $station_name = $row['radion_station_name'];
    //   echo "got the results from station name ".$station_name;
     }
    return $station_name ;
}



public function is_radio_shortcode(){
    $ispoll = false;
    
    $shortcode = $this->_fix_shortcode();
    $query = "SELECT * from radio_shortcode where shortcode='$shortcode'";
    $results = mysqli_query($this->conn, $query);
   // echo $query." <br>";
     $num_results = mysqli_num_rows($results);
     if ($num_results > 0){
         $ispoll = true;    
     
      }
     else 
      {
         $ispoll = false;
      }
    return $ispoll;
}



public function save_ULTIMATE($cat ,$opt, $msisdn){
    
    $return = false;
    $category = strtoupper($cat);
   // echo " Inside Save _ultimate ".$shortcode. "   Category code ".$category."  option ".$opt;
     $query1 = "SELECT * from mobile_poll_shortcode where category_code='$category'";
    // $query1 = "SELECT * from mobile_poll_shortcode where shortcode='$shortcode' and category_code='$category'";
     $results1 = mysqli_query($this->conn, $query1);
     $num_results = mysqli_num_rows($results1);
    // echo "   ".$query1   ."   RESULT ".$num_results;
      
      if ($num_results > 0 ){
          
        $sql_category = "SELECT * FROM ultimate_categories where category_code='$cat'";
          $res = mysqli_query($this->conn, $sql_category);
          
          $poll_name ='';
          while ($r = mysqli_fetch_array($res)){
              $poll_name = $r['category'];
          }
          //echo $poll_name; 
          
          $query = "SELECT * FROM mobile_poll where quest='$cat'";
         // echo $query; 
         $results = mysqli_query($this->conn, $query);
         $num_results = mysqli_num_rows($results);  
          $row = mysqli_fetch_assoc($results);
          if ($num_results > 0){
                $query2 = "INSERT INTO splitz_poll_ans(ans_id, poll_id, poll_name ,opt, msisdn) VALUES('',".$row['id'].",'$poll_name','$opt', '$msisdn')";
            //   echo $query2;
                 if(!mysqli_query($this->conn, $query2))
                    {
                          //  file_put_contents($log, chr(10).'-- Database error: '.mysqli_error(), FILE_APPEND);
                    }
           
          }
          
          $return = true;
      }
    
      return $return;
} 

           private function  processVotesSplitz(){
    
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
               
	
                        save_votes($this->msisdn, $team_name1, $team_id, $conn);
                        $message = $team_name." vote recorded.";              	

                       if (checkAvailableAdvert($conn)){
                        $advert = getAdvert($conn);
                        $message = $message."(AD) ".$advert;  
                       }            
                       $this->commit_sendMessage($message);
                    $query="INSERT INTO team_".$team_name1."(id, cell_no, date_voted, network_id) VALUES('','".$this->msisdn."',NOW(),'".$this->network_id."')";
                    if(!mysqli_query($this->conn, $query))
                    {
                         //   file_put_contents($log, chr(10).'-- Database error: '.mysqli_error(), FILE_APPEND);
                    }
                    exit();
                                
                }else{

                     save_votes($this->msisdn, "Spoiled_Vote", 36, $conn);
                     $query="INSERT INTO team_Spoiled_Vote (id, cell_no, date_voted, network_id) VALUES('','".$this->msisdn."',NOW(),'".$this->network_id."')"; 
                     if(!mysqli_query($this->conn, $query))
                        {
                        //	file_put_contents($log, chr(10).'-- Database error: '.mysqli_error(), FILE_APPEND);
			}
                               // exit();
                                
                    $dumbMsg = "Unknown keyword. ";
                    $this->commit_sendMessage($dumbMsg);
                   
                }
    
}

private function processVoteUltimate(){
    
         $exp_date = "2017-11-19 23:10:00";

       //  $exp_date1 = "2016-11-20 00:00:00";
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


                    $state = $this->save_ULTIMATE($row['category_code'], $new_name, $this->msisdn);
                    $message = "Thank you for voting ".$new_name." in the Alliance Sports Media Awards 2017";           
                  
                } 
              else {
                     $message = "The voting has closed, and your vote will not be counted";           

                }
                
                $this->commit_radioMessage("Splitz Marketing", $message, $this->smscost);

}






        public function save_poll_votes( $answer, $poll){
            $query = "";
        }

        public function save_votes($phonenumber,$name,$voted_for){

            $this->create_table($name);
        //INSERT INTO voters (phone_number, voted_for) VALUES (?, ?)
             $query = "INSERT INTO voters (id, phone_number, voted_for, latest_time_voted) 
                        VALUES ('','$phonenumber','$voted_for',now())";
                        if(!mysqli_query($this->conn, $query))
                        {
                              //  file_put_contents($log, chr(10).'-- Database error: '.mysqli_error(), FILE_APPEND);
                        }

               $query = "UPDATE teams SET votes=votes + 1 WHERE id='".$voted_for."'";

                        if(!$this->conn->query($query))
                        {
                                //file_put_contents($log, chr(10).chr(9).'-- Error: '. $errno.' '.$errstr.chr(10), FILE_APPEND);
                               // return 1;
                        }         


        // this function should save the vote for a specific team.... 
        // this function update the votes for a team and insert a phone number into the teams table. 



            return;
        }

        public function checkAvailableAdvert(){
            $available = 0;
            $query = "SELECT * from adverts where status=1";
              $results = mysqli_query($this->conn, $query);
             $num_results = mysqli_num_rows($results);
             if ($num_results > 0){
                 $available = 1;
             }else{
                 $this->resetAdverts();
             }

            return $available;
        }

        public function resetAdverts(){

                $query = "UPDATE adverts SET status=1";

                if(!$this->conn->query($query))
                {
                      //  file_put_contents($log, chr(10).chr(9).'-- Error: '. $errno.' '.$errstr.chr(10), FILE_APPEND);
                        return 1;
                }       
            return;
        }

        public function getAdvert(){
            $advert_wording =  "";
            $query = "SELECT * from adverts where active= 1 and status=1 LIMIT 1";

              $results = mysqli_query($this->conn, $query);
             $num_results = mysqli_num_rows($results);
             $row = mysqli_fetch_assoc($results); 
             if ($num_results > 0){
                  $advert_wording = $row['advert_wording'];
                  $row_id = $row['id'];
                  $used_count = $row['used_count']+1;
                  $query = "UPDATE adverts SET status=0, used_count=$used_count WHERE id='".$row_id."'";

                if(!$this->conn->query($query))
                {
                       // file_put_contents($log, chr(10).chr(9).'-- Error: '. $errno.' '.$errstr.chr(10), FILE_APPEND);
                        return 1;
                }         
             }

            return $advert_wording;


        }


        public function commit_sendMessage($message){
                
                 $query = "INSERT INTO outgoing_sms (msisdn, from_add, text, created, queued, network_id) 
                    VALUES ('".$this->msisdn."','".$this->shortcode."', '$message', NOW(), '0', '".$this->network_id."')";
                if(!mysqli_query($this->conn, $query))
                    {
                          //  file_put_contents($log, chr(10).'-- Database error: '.mysqli_error(), FILE_APPEND);
                    }
                    $query = "INSERT INTO smsmo (network_id, sender, receiver, keyword, created)
                    VALUES ('".$this->network_id."', '".$this->msisdn."', '".$this->shortcode."', '".$this->textMessage."', NOW())";

                    if(!mysqli_query($this->conn, $query))
                    {
                            //file_put_contents($log, chr(10).'-- Database error: '.mysqli_error(), FILE_APPEND);
                    }
                    $query="INSERT INTO premium_transactions (celnumber,shortcode,price,keyword,operator,message,company,message_date) VALUES "
                            . "('".$this->msisdn."','".$this->shortcode."','2.00' ,'".$this->textMessage."','".$this->network_id."','$message','Splitz Marketing',now())";

                    if(!mysqli_query($this->conn, $query))
                    {
                           // file_put_contents($log, chr(10).'-- Database error: '.mysqli_error(), FILE_APPEND);
                           // exit();
                    }

                   

        }


        
        public  function commit_sendMessage_cost($message, $cost){
            
                 $query = "INSERT INTO outgoing_sms (msisdn, from_add, text, created, queued, network_id) 
                    VALUES ('".$this->msisdn."','".$this->shortcode."', '$message', NOW(), '0', '".$this->network_id."')";
                if(!mysqli_query($this->conn, $query))
                    {
                          //  file_put_contents($log, chr(10).'-- Database error: '.mysqli_error(), FILE_APPEND);
                    }
                    $query = "INSERT INTO smsmo (network_id, sender, receiver, keyword, created)
                    VALUES ('".$this->network_id."', '".$this->msisdn."', '".$this->shortcode."', '".$this->textMessage."', NOW())";

                    if(!mysqli_query($this->conn, $query))
                    {
                            //file_put_contents($log, chr(10).'-- Database error: '.mysqli_error(), FILE_APPEND);
                    }
                    $query="INSERT INTO premium_transactions (celnumber,shortcode,price,keyword,operator,message,company,message_date) VALUES "
                            . "('".$this->msisdn."','".$this->shortcode."','$cost' ,'".$this->textMessage."','".$this->network_id."','$message','Splitz Marketing',now())";

                    if(!mysqli_query($this->conn, $query))
                    {
                           // file_put_contents($log, chr(10).'-- Database error: '.mysqli_error(), FILE_APPEND);
                           // exit();
                    }

              
            
        }
        
        
         public  function commit_radioMessage($radio, $message, $cost){
            
                 $query = "INSERT INTO outgoing_sms (msisdn, from_add, text, created, queued, network_id) 
                    VALUES ('".$this->msisdn."','".$this->shortcode."', '$message', NOW(), '0', '".$this->network_id."')";
                if(!mysqli_query($this->conn, $query))
                    {
                          //  file_put_contents($log, chr(10).'-- Database error: '.mysqli_error(), FILE_APPEND);
                    }
                    $query = "INSERT INTO smsmo (network_id, sender, receiver, keyword, created)
                    VALUES ('".$this->network_id."', '".$this->msisdn."', '".$this->shortcode."', '".$this->textMessage."', NOW())";

                    if(!mysqli_query($this->conn, $query))
                    {
                            //file_put_contents($log, chr(10).'-- Database error: '.mysqli_error(), FILE_APPEND);
                    }
                    $query="INSERT INTO premium_transactions (celnumber,shortcode,price,keyword,operator,message,company,message_date) VALUES "
                            . "('".$this->msisdn."','".$this->shortcode."','$cost' ,'".$this->textMessage."','".$this->network_id."','$message','$radio',now())";

                    if(!mysqli_query($this->conn, $query))
                    {
                           // file_put_contents($log, chr(10).'-- Database error: '.mysqli_error(), FILE_APPEND);
                           // exit();
                    }

              
            
        }
        
        public function get_num_of_words($string) {
                    $string = preg_replace('/\s+/', ' ', trim($string));
                    $words = explode(" ", $string);
                    return count($words);
        }

        public function get_token_no($string, $searchStr){

            $token  = strtok($string, $searchStr);
                                //echo $_REQUEST['text'];
            $count = 0;


             while ($token !== false) {      
              $count++;
              $token = strtok($searchStr);      
            }                    

            return $count;
        }

        public function create_table($name){
           $create_table =
            "CREATE TABLE IF NOT EXISTS team_".$name."  
            (
            id INT NOT NULL AUTO_INCREMENT,
            cell_no VARCHAR(20) NOT NULL,
            date_voted DATETIME NULL,
            network_id VARCHAR(20) NULL,
            PRIMARY KEY(id)
        )";

            if(!mysqli_query($this->conn, $create_table))
                 {
                               //  file_put_contents($log, chr(10).'-- Database error: '.mysqli_error(), FILE_APPEND);
                 }

             return 1;
         }


        function getMenu($id){
            $menu = "";
            $s = "SELECT text, menu_code, parent_code FROM menu WHERE menu_code !=0 AND parent_code=0";

           // echo $s;
          //  SELECT menu_name, menu_code, parent_code FROM menu WHERE menu_code <> 0 AND parent_code = 0
             $results = mysqli_query($this->conn, $s);
             $num_results = mysqli_num_rows($results);

             while ($r = mysqli_fetch_array($results)){         

                 $menu .= $r['menu_code'].".".$r['text']." ".PHP_EOL;


             }
            return $menu;
        }


        public function getChild($id)	{
            $menu = "";
            $s = " SELECT text, menu_code, parent_code, menu_description FROM menu WHERE menu_code != 0 AND parent_code = $id";
        
             $results = mysqli_query($this->conn, $s);
             $num_results = mysqli_num_rows($results);
             if ($num_results == 1){
        
                 $row = mysqli_fetch_assoc($results);
                 if ($this->children($row['menu_code'])){
                      $menu .= $row['menu_code'].". ".$row['text']." ".PHP_EOL;
                    //  echo  $menu;
                 }else{

                   $menu = $row['menu_description'];    
                 }


             }else  if ($num_results > 1){
                   while ($r = mysqli_fetch_assoc($results)){         
                   $menu .= $r['menu_code'].". ".$r['text']." ".PHP_EOL;
                } 
             }else{
                 $menu .= "  Could not find anything under this service code ".$id.", sms find to 34040";
             }

            return $menu;
        }



        public function children($id){


            $s = "SELECT text, menu_code, parent_code, menu_description FROM menu WHERE menu_code != 0 AND parent_code = $id";

             $results = mysqli_query($this->conn, $s);
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

        public function has_token($string){

        $word = strtok($string,'   ');
         if ( $word !== false ) {
             return true;
         }else{ 
            return false;
         }
        }





        public function unknown_keyword_message(){

            $message = "";
            
            
            $shortcode = $this->_fix_shortcode();
            $s = "SELECT * FROM sms_shortcode_unknown WHERE shortcode = '$shortcode'";
          //  echo "   Select ".$s;
             $results = mysqli_query($this->conn, $s);
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
    
        protected function _fix_shortcode(){
            $shorti ="";
            if (strlen($this->shortcode)>5){
                $shorti = substr($this->shortcode, 4);;
         
            }else{
               $shorti = $this->shortcode;
            }
            
            return $shorti; 
        }
        
        
       public function process_non_subscription(){
           
           $result = false;
           $shortcode = $this->_fix_shortcode();
           
           $qry = "SELECT * from shortcodes where shortcode='$shortcode'";
           if(!($res = $this->conn->query($qry)))
		{
			//file_put_contents($log, chr(10).chr(9).'-- Database error: '.$conn->error().chr(10), FILE_APPEND);
			return 1;
		}//end if
            $rw = $res->fetch_assoc();
            
            
            
            $this->smscost = $rw['cost_inclusive']; 
           //  echo "  Cost ". $this->smscost."  <br>";
             $query = "SELECT * FROM premium_services WHERE keyword='".strtoupper($this->textMessage)."' AND shortcode='$shortcode'";
            //  echo $query;		
             if(!($rs = $this->conn->query($query))){
			//file_put_contents($log, chr(10).chr(9).'-- Database error: '.$conn->error().chr(10), FILE_APPEND);
			return 1;
                 
                 $result = false;
             }//end if
       
             
             if($rs->num_rows > 0) {
                 echo "   Inside ";
                 for($data = 0; $data < $rs->num_rows; $data++){
				$rs->data_seek($data);				
				$row = $rs->fetch_assoc();
				$cost = $row['price'];
				$message = $row['message'];
                                
                                
                               // $this->smscost = $cost;  
                                
                               // echo '   Cost of '.;
                   $this->commit_sendMessage_cost($message, $cost);             
                  $result = true;
                }
             }else{
                // echo "    Could not process the request  <br>";
                 $result = false;
             }
             
             return $result;
       }
        
        public function optout($message){
            $query="INSERT INTO blacklist (msisdn,shortcode, network_id,keyword,date_added) "
                           . "VALUES ('".$this->msisdn."','".$this->shortcode."','".$this->network_id."','".$this->textMessage."',now())";
            if(!mysqli_query($this->conn, $query))                    
                    
            {               // trigger_error("   Failed to comming to blacklist  :".mysqli_error(), E_USER_ERROR);
              //  file_put_contents($log, chr(10).'-- Database error: '.mysqli_error(), FILE_APPEND);
            }else{
                 $this->commit_sendMessage($message);
                   
           
            }
     
            
        }
        
        
        public function check_subsription(){
            $is_subscribed = false;
           $shortcode = $this->_fix_shortcode();
            $s = "SELECT * FROM subcription_service WHERE keyword='".strtoupper($this->textMessage)."' AND shortcode='$shortcode'";
          //   echo "   Select ".$s;
            $results = mysqli_query($this->conn, $s);
            $num_results = mysqli_num_rows($results);
              if ($num_results > 0){
                  $is_subscribed = true;
              }

            return $is_subscribed;
        }   
    
        public  function processSubscriber(){
            
           // echo "    Process subscriber <br>";
            
                           $query = "INSERT INTO sms_subscription (msisdn, keyword, network_id, status, creation_date) 
                            VALUES ('".$this->msisdn."','".$this->textMessage."','".$this->network_id."', 1, NOW())";
                           
                         //  echo $query;
                                if(!mysqli_query($this->conn, $query))
				{
				//	file_put_contents($log, chr(10).'-- Database error: '.mysqli_error(), FILE_APPEND);
                                  //      return 1;
				}
                                
                       		$message = "Thanks for subscribing to ".$this->textMessage." content, please reply with yes to opt-in for us to send you this content";
                              //  echo $message;
                              $this->commit_sendMessage_cost($message, $this->smscost);
        }
        
        
        
        public function check_msisdn_subscribed() {
             $is_subscribed = false;

                $s = "SELECT * FROM sms_subscription WHERE msisdn = '$this->msisdn' and keyword='$this->textMessage'";
                //echo "   Select ".$s;
                $results = mysqli_query($this->conn, $s);
                $num_results = mysqli_num_rows($results);
                if ($num_results > 0){
                   $is_subscribed = true;
                }


             return $is_subscribed;
        }



        public function savePoll($conn, $shortcode, $opt){
            //$pollname="";
           // $poll_id = getpoll_id($conn, $shortcode);
             $query1 = "SELECT * from poll_shortcode where shortcode='$this->shortcode'";
             $results1 = mysqli_query($this->conn, $query1);
             $num_results = mysqli_num_rows($results1);
             // $row = mysqli_fetch_assoc($results); 

              while ($row1 = mysqli_fetch_array($results1)) {
                 $query = "SELECT * from cell_poll where id='".$row1['poll_id']."' and (opt1='$opt' or opt2='$opt' or opt3='$opt' or opt4='$opt')";
                // echo $query; 
                 $results = mysqli_query($this->conn, $query);
                 $num_results = mysqli_num_rows($results);
                 //$row = mysqli_fetch_assoc($results); 

                 if ($num_results > 0){

                     $query2 = "INSERT INTO sms_poll_ans(ans_id, qst_id, opt) VALUES('','".$row1['poll_id']."','$opt')";
                    // echo $query2;
                       if(!mysqli_query($this->conn, $query2))
                            {
                                  //  file_put_contents($log, chr(10).'-- Database error: '.mysqli_error(), FILE_APPEND);
                            }
                 }  
              }      


            return 1;
        }

        function is_poll_shortcode(){
            $ispoll = false;
            
            $shortcode = $this->_fix_shortcode();
            $query = "SELECT * from poll_shortcode where shortcode='$shortcode'";
            $results = mysqli_query($this->conn, $query);
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
        
        
        
        public function  process_Radio(){
           // echo "   Process Radio function";
            
            $shortcode = $this->_fix_shortcode();
            $num_words = $this->get_num_of_words($this->textMessage);
            $radio_name = $this->getRadio();
            $messages = $radio_name." would like to thank you for your contribution";
             if(($this->is_poll_shortcode($shortcode)) && ($word_count == 1)){
                 
                 $this->savePoll($conn, $shortcode, $this->textMessage);
                 $this->commit_radioMessage($radio_name, $messages, $this->smscost);
                 
             }else{
                 $this->commit_radioMessage($radio_name, $messages, $this->smscost);
                 $query="INSERT INTO temp_marquee (msisdn,message,date_time,company) VALUES ('".$this->msisdn."','".$this->textMessage."' ,NOW(),'$radio_name')";
                //  echo $query;             
                if(!mysqli_query($this->conn, $query))
                 {    echo mysql_error();
                    //file_put_contents($log, chr(10).'-- Database error: '.mysqli_error(), FILE_APPEND);
                 }
             }
            
        }

     private function getNominee(){
         $nominee= "";
         
         
     }


     public function process_shortcode_poll(){
            
            
            $shortcode = $this->_fix_shortcode();
            if ($shortcode =='31012'){
             //  echo "   Chosen  31012";
               
               
         $message = "";
               $exp_date = "2017-11-19 23:10:00";
   // $exp_date1 = "2016-11-20 00:00:00";
                $todays_date = date("Y-m-d H:i:s");

               $today = strtotime($todays_date);
               $expiration_date = strtotime($exp_date);
               
        
                if ($this->is_ultimate_keyword()){
                     
                //    echo "Ultimate keyword ".$_REQUEST['text'];
                     $query = "SELECT * FROM ultimate_nominees WHERE allocate_code='".strtoupper($this->textMessage)."'";
                              if(!($rs = $this->conn->query($query))){
                               // file_put_contents($log, chr(10).chr(9).'-- Database error: '.$conn->error().chr(10), FILE_APPEND);
                                return 1;
                              }//end if

                          //    echo "   Ultimate FM ";
                               $row = $rs->fetch_assoc();
                               $new_name  =  trim($row['first_name']); // ." - ". trim($row['surname']); 

                   if (($expiration_date > $today ) ){
                             $valid = "yes";
                            
                               $message1= "";
                                 for ($x = 1; $x <= 15; $x++) {
                                  //   echo "  inside ";
                                  $this->save_ULTIMATE($row['category_code'], $new_name, $this->msisdn);	 
                                 } 
                               $message1 = "15 votes allocated. ";
                               $message = "Thank you for voting ".$new_name.".".$message1." in the Alliance Sports Media Awards 2017";
                     }                  

                     else {
                         
                        $message = "The voting has closed, and your vote will not be counted";   
                         
                     }
                    
                }else if ($this->is_splitzcup()){
                    
                    $query = "SELECT id, name from teams where keyword ='".$this->textMessage."'";
                    $results = mysqli_query($this->conn, $query);
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
                    
                    }
                    
                     for ($x = 1; $x <= 15; $x++) {
                         $this->save_votes($this->msisdn, $team_name1, $team_id);	 
		     } 
                        
                        
                     $message = " 15 votes allocated to ".$team_name.". ";
                     
                     
                     if ($this->checkAvailableAdvert()){
                        $advert = $this->getAdvert($conn);
                        $message = $message."(AD) ".$advert;  
                     }         
                }   // splitz cup
                if($message == ""){
                    $message = "Thanks for your contribution";
                }
                
                
                
                
                $this->commit_sendMessage_cost($message, $this->smscost);
                
            }
            else if($shortcode == '31018') {
                 echo "   Chosen  31018";  
                 $this->processVoteUltimate();
                 
                // $this->commit_sendMessage_cost($message, $this->smscost);
                 
            } else if($shortcode =='31019') {
                 if ($this->is_ultimate_keyword()){
                     $this->processVoteUltimate();
                 }  else if($this->is_splitzcup()) {
                     $this->processVotesSplitz();
                 }
            } else if ($shortcode == '34040'){
                
                   if (strtoupper($this->textMessage) == 'FIND'){
                       
                         $message = $this->getMenu(0);
                                           echo $message;
                    } 
                    else {

                         $message = $this->getChild($this->textMessage);
                            echo $message;
                    } 
                    $this->commit_sendMessage_cost($message, $this->smscost);
            } 
            else {
                
                
                    $message = $this->unknown_keyword_message();
                    
                    if ($message == ""){
		       $dumbMsg = "Thank you for your contribution";
                    } else {
                        $dumbMsg = $message;
                               
                    }
                
             //   $dumbMsg = "Thank you for your contribution";
                  $this->commit_sendMessage_cost($dumbMsg, $this->smscost);
                
            }
            
            
        }
        
        
        
        
        
        
        private function remove_numbers($string) {
            return preg_replace('/[0-9]+/', null, $string);
        }
        
        
        public function is_ultimate_keyword(){
               $is_ultimate = false;
               //echo "  examaning is ultimate keyword  ===> ".$keyword;
                $words = $this->remove_numbers($this->textMessage);
               //echo "  examaning is ultimate keyword  ===> ".$words;
                $query = "SELECT * from ultimate_nominees where allocate_code='$this->textMessage'";
                 $results = mysqli_query($this->conn, $query);
               // echo $query." <br>";
                 $num_results = mysqli_num_rows($results);
                 if($num_results > 0){
                     $is_ultimate = true;

                 //   echo " is ultiate keyword";
                 }
            return $is_ultimate;

        }


        public  function is_splitzcup(){
           $is_ultimate = false;


            $query = "SELECT * from teams where keyword='$this->textMessage'";
             $results = mysqli_query($this->conn, $query);

             $num_results = mysqli_num_rows($results);
             if($num_results > 0){
                 $is_ultimate = true;       

             }
        return $is_ultimate;

        }

}
