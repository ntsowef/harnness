<?php 
//print "<pre>"; 
require_once "smpp.php"; 
$tx=new SMPP('localhost',7000); 
//$tx->debug=true; 
$tx->system_type="WWW"; 
$tx->addr_npi=1; 
//print "open status: ".$tx->state."\n"; 
#name=hugo
#password=ggoohu

$tx->bindTransmitter("hugo","ggoohu"); 
$tx->sms_source_addr_npi=1; 
$tx->sms_source_addr_ton=1; 
$tx->sms_dest_addr_ton=1; 
$tx->sms_dest_addr_npi=1; 
$tx->sms_registered_delivery_flag = 1;
for ($i=1; $i <=5;$i++){
   $messageId = $tx->sendSMS("27820029863","27796222802","from php client with user hugo");
//   $tx->bindReceiver("adams","adams");
   
}
//$tx->readSMS();
echo " Messge sent ";
$tx->close();

// $tx->bindReceiver("frans","ntsowe");
   
//}
//$tx->readSMS();

//$tx->close(); 




unset($tx); 
//print "</pre>"; 
?>