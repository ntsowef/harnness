<?php
set_time_limit(7200);
include 'connect_anc.php';

//$num = 123;
$str_length = 3 ;




$top6 =  "EthekwiniLeader";



for ($i=1; $i <= 5;$i++){
    
    $member_name = $top6.$i;
    $member_surname = $top6.$i;
    $member_id = rand(0,1000000);
    $member_no = rand(0,100000000);
    $branch_id = rand(6460,6509);    
    $query = "INSERT member(membership_no, join_date, member_name, member_surname, member_id, branch_id, structure_id) "
        . "VALUES ('$member_no',NOW(),'$member_name','$member_surname',$member_id, $branch_id, 3) ";
    //echo $query."<br>";
        $eye =  mysql_query($query);
    
}

$top6 =  "EthekwiniREC";

for ($i=1; $i <= 20;$i++){
    
    $member_name = $top6.$i;
    $member_surname = $top6.$i;
    $member_id = rand(0,1000000);
    $member_no = rand(0,100000000);
    $branch_id = rand(6751,6870);    
    $query = "INSERT member(membership_no, join_date, member_name, member_surname, member_id, branch_id, structure_id) "
        . "VALUES ('$member_no',NOW(),'$member_name','$member_surname',$member_id, $branch_id, 3) ";
   // echo $query."<br>";
        $eye =  mysql_query($query);
    
}





$top6 =  "UguLeader";



for ($i=1; $i <= 5;$i++){
    
    $member_name = $top6.$i;
    $member_surname = $top6.$i;
    $member_id = rand(0,1000000);
    $member_no = rand(0,100000000);
       $branch_id = rand(6871,6949);    
    $query = "INSERT member(membership_no, join_date, member_name, member_surname, member_id, branch_id, structure_id) "
        . "VALUES ('$member_no',NOW(),'$member_name','$member_surname',$member_id, $branch_id, 3) ";
    //echo $query."<br>";
        $eye =  mysql_query($query);
    
}

$top6 =  "UguREC";

for ($i=1; $i <= 20;$i++){
    
    $member_name = $top6.$i;
    $member_surname = $top6.$i;
    $member_id = rand(0,1000000);
    $member_no = rand(0,100000000);
       $branch_id = rand(6871,6949);    

    $query = "INSERT member(membership_no, join_date, member_name, member_surname, member_id, branch_id, structure_id) "
        . "VALUES ('$member_no',NOW(),'$member_name','$member_surname',$member_id, $branch_id, 3) ";
   // echo $query."<br>";
        $eye =  mysql_query($query);
    
}






$top6 =  "UmgungundlovuLeader";



for ($i=1; $i <= 5;$i++){
    
    $member_name = $top6.$i;
    $member_surname = $top6.$i;
    $member_id = rand(0,1000000);
    $member_no = rand(0,100000000);
    $branch_id = rand(6950,7037);    
    $query = "INSERT member(membership_no, join_date, member_name, member_surname, member_id, branch_id, structure_id) "
        . "VALUES ('$member_no',NOW(),'$member_name','$member_surname',$member_id, $branch_id, 3) ";
    //echo $query."<br>";
        $eye =  mysql_query($query);
    
}

$top6 =  "UmgungundlovuREC";

for ($i=1; $i <= 20;$i++){
    
    $member_name = $top6.$i;
    $member_surname = $top6.$i;
    $member_id = rand(0,1000000);
    $member_no = rand(0,100000000);
    $branch_id = rand(6950,7037);    

    $query = "INSERT member(membership_no, join_date, member_name, member_surname, member_id, branch_id, structure_id) "
        . "VALUES ('$member_no',NOW(),'$member_name','$member_surname',$member_id, $branch_id, 3) ";
   // echo $query."<br>";
        $eye =  mysql_query($query);
    
}



$top6 =  "UthukelaLeader";



for ($i=1; $i <= 5;$i++){
    
    $member_name = $top6.$i;
    $member_surname = $top6.$i;
    $member_id = rand(0,1000000);
    $member_no = rand(0,100000000);
    $branch_id = rand(7038,7111);    
    $query = "INSERT member(membership_no, join_date, member_name, member_surname, member_id, branch_id, structure_id) "
        . "VALUES ('$member_no',NOW(),'$member_name','$member_surname',$member_id, $branch_id, 3) ";
    //echo $query."<br>";
        $eye =  mysql_query($query);
    
}

$top6 =  "UthukelaREC";

for ($i=1; $i <= 20;$i++){
    
    $member_name = $top6.$i;
    $member_surname = $top6.$i;
    $member_id = rand(0,1000000);
    $member_no = rand(0,100000000);
    $branch_id = rand(7038,7111);    

    $query = "INSERT member(membership_no, join_date, member_name, member_surname, member_id, branch_id, structure_id) "
        . "VALUES ('$member_no',NOW(),'$member_name','$member_surname',$member_id, $branch_id, 3) ";
   // echo $query."<br>";
        $eye =  mysql_query($query);
    
} 





$top6 =  "UMzinyathiLeader";



for ($i=1; $i <= 5;$i++){
    
    $member_name = $top6.$i;
    $member_surname = $top6.$i;
    $member_id = rand(0,1000000);
    $member_no = rand(0,100000000);
    $branch_id = rand(7112,7167);    
    $query = "INSERT member(membership_no, join_date, member_name, member_surname, member_id, branch_id, structure_id) "
        . "VALUES ('$member_no',NOW(),'$member_name','$member_surname',$member_id, $branch_id, 3) ";
    //echo $query."<br>";
        $eye =  mysql_query($query);
    
}

$top6 =  "UMzinyathiREC";

for ($i=1; $i <= 20;$i++){
    
    $member_name = $top6.$i;
    $member_surname = $top6.$i;
    $member_id = rand(0,1000000);
    $member_no = rand(0,100000000);
    $branch_id = rand(7112,7167);    

    $query = "INSERT member(membership_no, join_date, member_name, member_surname, member_id, branch_id, structure_id) "
        . "VALUES ('$member_no',NOW(),'$member_name','$member_surname',$member_id, $branch_id, 3) ";
   // echo $query."<br>";
        $eye =  mysql_query($query);
    
} 




$top6 =  "AmajubaLeader";



for ($i=1; $i <= 5;$i++){
    
    $member_name = $top6.$i;
    $member_surname = $top6.$i;
    $member_id = rand(0,1000000);
    $member_no = rand(0,100000000);
    $branch_id = rand(7168,7220);    
    $query = "INSERT member(membership_no, join_date, member_name, member_surname, member_id, branch_id, structure_id) "
        . "VALUES ('$member_no',NOW(),'$member_name','$member_surname',$member_id, $branch_id, 3) ";
    //echo $query."<br>";
        $eye =  mysql_query($query);
    
}

$top6 =  "AmajubaREC";

for ($i=1; $i <= 20;$i++){
    
    $member_name = $top6.$i;
    $member_surname = $top6.$i;
    $member_id = rand(0,1000000);
    $member_no = rand(0,100000000);
    $branch_id = rand(7168,7220);    

    $query = "INSERT member(membership_no, join_date, member_name, member_surname, member_id, branch_id, structure_id) "
        . "VALUES ('$member_no',NOW(),'$member_name','$member_surname',$member_id, $branch_id, 3) ";
   // echo $query."<br>";
        $eye =  mysql_query($query);
    
} 





$top6 =  "ZululandLeader";



for ($i=1; $i <= 5;$i++){
    
    $member_name = $top6.$i;
    $member_surname = $top6.$i;
    $member_id = rand(0,1000000);
    $member_no = rand(0,100000000);
    $branch_id = rand(7221,7310);    
    $query = "INSERT member(membership_no, join_date, member_name, member_surname, member_id, branch_id, structure_id) "
        . "VALUES ('$member_no',NOW(),'$member_name','$member_surname',$member_id, $branch_id, 3) ";
    //echo $query."<br>";
        $eye =  mysql_query($query);
    
}

$top6 =  "ZululandREC";

for ($i=1; $i <= 20;$i++){
    
    $member_name = $top6.$i;
    $member_surname = $top6.$i;
    $member_id = rand(0,1000000);
    $member_no = rand(0,100000000);
    $branch_id = rand(7221,7310);    

    $query = "INSERT member(membership_no, join_date, member_name, member_surname, member_id, branch_id, structure_id) "
        . "VALUES ('$member_no',NOW(),'$member_name','$member_surname',$member_id, $branch_id, 3) ";
   // echo $query."<br>";
        $eye =  mysql_query($query);
    
} 



$top6 =  "UMkhayadukeLeader";



for ($i=1; $i <= 5;$i++){
    
    $member_name = $top6.$i;
    $member_surname = $top6.$i;
    $member_id = rand(0,1000000);
    $member_no = rand(0,100000000);
    $branch_id = rand(7311,7382);    
    $query = "INSERT member(membership_no, join_date, member_name, member_surname, member_id, branch_id, structure_id) "
        . "VALUES ('$member_no',NOW(),'$member_name','$member_surname',$member_id, $branch_id, 3) ";
    //echo $query."<br>";
        $eye =  mysql_query($query);
    
}

$top6 =  "UMkhayadukeREC";

for ($i=1; $i <= 20;$i++){
    
    $member_name = $top6.$i;
    $member_surname = $top6.$i;
    $member_id = rand(0,1000000);
    $member_no = rand(0,100000000);
    $branch_id = rand(7311,7382);    

    $query = "INSERT member(membership_no, join_date, member_name, member_surname, member_id, branch_id, structure_id) "
        . "VALUES ('$member_no',NOW(),'$member_name','$member_surname',$member_id, $branch_id, 3) ";
   // echo $query."<br>";
        $eye =  mysql_query($query);
    
} 




$top6 =  "UthunguluLeader";



for ($i=1; $i <= 5;$i++){
    
    $member_name = $top6.$i;
    $member_surname = $top6.$i;
    $member_id = rand(0,1000000);
    $member_no = rand(0,100000000);
    $branch_id = rand(7383,7487);    
    $query = "INSERT member(membership_no, join_date, member_name, member_surname, member_id, branch_id, structure_id) "
        . "VALUES ('$member_no',NOW(),'$member_name','$member_surname',$member_id, $branch_id, 3) ";
    //echo $query."<br>";
        $eye =  mysql_query($query);
    
}

$top6 =  "UthunguluREC";

for ($i=1; $i <= 20;$i++){
    
    $member_name = $top6.$i;
    $member_surname = $top6.$i;
    $member_id = rand(0,1000000);
    $member_no = rand(0,100000000);
    $branch_id = rand(7383,7487);    

    $query = "INSERT member(membership_no, join_date, member_name, member_surname, member_id, branch_id, structure_id) "
        . "VALUES ('$member_no',NOW(),'$member_name','$member_surname',$member_id, $branch_id, 3) ";
   // echo $query."<br>";
        $eye =  mysql_query($query);
    
} 




$top6 =  "iLembeLeader";



for ($i=1; $i <= 5;$i++){
    
    $member_name = $top6.$i;
    $member_surname = $top6.$i;
    $member_id = rand(0,1000000);
    $member_no = rand(0,100000000);
    $branch_id = rand(7488,7564);    
    $query = "INSERT member(membership_no, join_date, member_name, member_surname, member_id, branch_id, structure_id) "
        . "VALUES ('$member_no',NOW(),'$member_name','$member_surname',$member_id, $branch_id, 3) ";
    //echo $query."<br>";
        $eye =  mysql_query($query);
    
}

$top6 =  "iLembeREC";

for ($i=1; $i <= 20;$i++){
    
    $member_name = $top6.$i;
    $member_surname = $top6.$i;
    $member_id = rand(0,1000000);
    $member_no = rand(0,100000000);
    $branch_id = rand(7488,7564);    

    $query = "INSERT member(membership_no, join_date, member_name, member_surname, member_id, branch_id, structure_id) "
        . "VALUES ('$member_no',NOW(),'$member_name','$member_surname',$member_id, $branch_id, 3) ";
   // echo $query."<br>";
        $eye =  mysql_query($query);
    
} 



$top6 =  "HGwalaLeader";



for ($i=1; $i <= 5;$i++){
    
    $member_name = $top6.$i;
    $member_surname = $top6.$i;
    $member_id = rand(0,1000000);
    $member_no = rand(0,100000000);
    $branch_id = rand(7565,7625);    
    $query = "INSERT member(membership_no, join_date, member_name, member_surname, member_id, branch_id, structure_id) "
        . "VALUES ('$member_no',NOW(),'$member_name','$member_surname',$member_id, $branch_id, 3) ";
    //echo $query."<br>";
        $eye =  mysql_query($query);
    
}

$top6 =  "HGwalaREC";

for ($i=1; $i <= 20;$i++){
    
    $member_name = $top6.$i;
    $member_surname = $top6.$i;
    $member_id = rand(0,1000000);
    $member_no = rand(0,100000000);
    $branch_id = rand(7565,7625);    

    $query = "INSERT member(membership_no, join_date, member_name, member_surname, member_id, branch_id, structure_id) "
        . "VALUES ('$member_no',NOW(),'$member_name','$member_surname',$member_id, $branch_id, 3) ";
   // echo $query."<br>";
        $eye =  mysql_query($query);
    
} 


$top6 =  "Member";





$query = " SELECT * from branches where province_id=2";


$res = mysql_query($query);


while($row = mysql_fetch_array($res)) {
         $ward = $row['ward_number'];
         $branch_id = $row['id'];
          $numbers = rand(50,120);
        for ($i=1; $i <= $numbers;$i++){

            $member_name = $top6.$i."_".$ward;
            $member_surname = $top6.$i."_".$ward;
            $member_id = rand(0,1000000);
            $member_no = rand(0,100000000);

            $query = "INSERT member(membership_no, join_date, member_name, member_surname, member_id, branch_id, structure_id) "
                . "VALUES ('$member_no',NOW(),'$member_name','$member_surname',$member_id, $branch_id, 9) ";
            //echo $query."<br>";
                $eye =  mysql_query($query);

        }
        
        
}



//$query = "INSERT members(membership_no, join_date, member_name, member_surname, member_id, branch_id, structure_id) "
 //       . "VALUES () ";