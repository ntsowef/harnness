<?php include "connect.php"; ?>
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <?php
        
        
        $sql ="SELECT COUNT(operator) AS no_transactions, operator AS Network,  MONTH(message_date) AS month_transaction FROM premium_transactions WHERE YEAR(message_date) = 2017  
                GROUP BY MONTH(message_date), operator";
        $res = mysql_query($sql);
        $num_rows = mysql_num_rows($res);
        if ($num_rows > 0){
            $currentMonth = 0;
            $previousMonth = 0;
            $i=0;
            $j=0;
            $network='';
            
            $datax = array();
             while($data = mysql_fetch_array($res)){
                 $currentMonth = $data['month_transaction'];
                 
                 if (strlen(strstr($data['Network'],'Vodacom'))>0) {
                       $Network = 'Vodacom'; 
                       
                 }else if (strlen(strstr($data['Network'],'ETL'))>0){
                      $Network = 'ETL';
                 }
                 $month =  $data['month_transaction'];
                 $no_transaction =  $data['no_transactions'];
                 
                   $datax[] = array(
                    "Network" =>$Network,
                     "Month" => $month,
                     "no_transaction"=>$no_transaction  
                    );
                 
                 
              /* if ((strpos($data['Network'], 'Vodacom'))  &&  $currentMonth == $previousMonth ){
                 echo " Vodacom ".$i."  Current month ".$currentMonth;
                 $i++;
               }
               else if ((strpos($data['Network'], 'ETL'))  &&  $currentMonth == $previousMonth ){
                echo " ETL ".$j."  Current month ".$currentMonth;               
                   $j++;
             }else{
                 echo  $data['Network']." ".$i."  Short  ".strpos($data['Network'],"Vodacom")."<br>";
             } */
             
               
                $previousMonth = $currentMonth; 
             }
            //echo json_encode($datax);
             //print_r($datax);
             
             
      foreach($datax as $arr)
          
        {
          
         // print_r($arr) ;
          echo ' <br>';
            foreach($arr as $a => $val){
                echo " Inside ".$val." a = ".$a." res =".$result[$a]. "<br>";
                        
                if(isset($result[$a])){
                 //   print_r($result[$a]);
                 //   echo $val.'inside a loop <br> ';
                   // if ($a =='no_transaction'){
                      $result[$a] += $val;
                  //  }
                }else{
                $result[$a] = $val;
                }
            }
        }
        
        print_r($result);
        }
        
        
        
        
        ?>
    </body>
</html>
