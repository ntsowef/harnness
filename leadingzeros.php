
<?php

set_time_limit(600);

include 'connect.php';

//$num = 123;
$str_length = 3 ;

// hardcoded left padding if number < $str_length

//$query = "DELETE from splitz_poll_ans where opt='SELIMO THABANE (KHOMO)' and poll_name = 'Song Of The Year (SOY)'";



/*for ($i=1; $i<=1333;$i++){
    

$result = mysql_query($query) or trigger_error(mysql_error().$query);

echo "<br>  ".$query;
} */

$ii=0;
while ($ii < 1000){
              
                $query = "INSERT INTO uma_bulk_votes_queue (id, msisdn, nominee, category_code, date_voted) VALUES(id,'+26657649839','SELIMO THABANE (KHOMO)','SOY',NOW()) ";
             // echo $query;
                 $eye =  mysql_query($query);
                 $ii++;
}


/*while($row = mysql_fetch_array($result)){
    $municipal_code = $row['code']; 
    $region_no = $row['district_id'];
    $municipal_name = $row['name'];
    $ward_prefix = $row['ward_prefix'];
    $municipal_type = $row['municipality_type'];
    $number_of_wards = $row['number_of_wards'];  
    $province_id = $row['provice_id'];
    
    
    if($municipal_type != 1){
       $ward_number="";
       $iec_ward ="";
       for ($i=1;$i <=$number_of_wards;$i++){
       $str = substr("000{$i}", -$str_length);
       $ward_number = "ward".$i;
       $iec_ward = $ward_prefix."".$str;              

        $sql_insert_branch ="INSERT branches (political_name, ward_number, region_id, province_id,  municipal_name, iec_ward_number) VALUE "
            . "('','$ward_number',$region_no,$province_id, '$municipal_name','$iec_ward' )";
         // echo '   '.$sql_insert_branch."<br>";  
        $eye =  mysql_query($sql_insert_branch);
       }
      
             
    }
   
}*/
//echo $sql; 
 ?> 
