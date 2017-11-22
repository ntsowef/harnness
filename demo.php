<?php

include './messageContent.php';
$data =  array(
     
    'msisdn' =>'0796222815',
    'network_id'=>'VodacomSA',
    'textMessage'=>'SRY3',
    'timein'=>1232131231,
    'shortcode'=>'31012'
    
);

$messageContent = new MessageContent($data);


 $is_subscription = $messageContent->check_subsription();
 
 if ($is_subscription){
    //   echo $messageContent->shortcode."  is Subscribed  ".$messageContent->textMessage;
 //      
       $check_subriber = $messageContent->check_msisdn_subscribed();
       if (!$check_subriber){
           $messageContent->processSubscriber();
       }

       
 }
 else {
   //  echo "   Not Subscribed  <br>";
     
     $is_non_sub = $messageContent->process_non_subscription(); 
     if (!$is_non_sub){
         
      //   echo "Campaign not processed <br> ";
         
         if (strtolower($messageContent->textMessage)=='out' || strtolower($messageContent->textMessage)=='stop'){
           // echo'Opting out';
             
             $message = "This number ".$messageContent->msisdn." has been removed from Splitz database. Thanks for your subscription ";
             $messageContent->optout($message);   
         }else {
             
        //     echo "   This is non opt out".PHP_EOL;
             
             if ($messageContent->is_radio_shortcode()){
                 $messageContent->process_Radio();
                 
             }else{
                 
                 $messageContent->process_shortcode_poll();
             }
             
             
         }
         
         
     }
 }
//$messageContent->
?>