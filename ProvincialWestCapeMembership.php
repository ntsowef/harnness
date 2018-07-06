<?php
set_time_limit(7200);
include 'connect_anc.php';

//$num = 123;
$str_length = 3 ;



$top6 =  "CityofCapeLeader";



for ($i=1; $i <= 5;$i++){
    
    $member_name = $top6.$i;
    $member_surname = $top6.$i;
    $member_id = rand(0,1000000);
    $member_no = rand(0,100000000);
    $branch_id = rand(7626,7741);    
    $query = "INSERT member(membership_no, join_date, member_name, member_surname, member_id, branch_id, structure_id) "
        . "VALUES ('$member_no',NOW(),'$member_name','$member_surname',$member_id, $branch_id, 3) ";
    //echo $query."<br>";
        $eye =  mysql_query($query);
    
}

$top6 =  "CityofCapeREC";

for ($i=1; $i <= 20;$i++){
    
    $member_name = $top6.$i;
    $member_surname = $top6.$i;
    $member_id = rand(0,1000000);
    $member_no = rand(0,100000000);
    $branch_id = rand(7626,7741);    
    $query = "INSERT member(membership_no, join_date, member_name, member_surname, member_id, branch_id, structure_id) "
        . "VALUES ('$member_no',NOW(),'$member_name','$member_surname',$member_id, $branch_id, 3) ";
   // echo $query."<br>";
        $eye =  mysql_query($query);
    
}





$top6 =  "WestCoastLeader";



for ($i=1; $i <= 5;$i++){
    
    $member_name = $top6.$i;
    $member_surname = $top6.$i;
    $member_id = rand(0,1000000);
    $member_no = rand(0,100000000);
    $branch_id = rand(7742,7788);    
    $query = "INSERT member(membership_no, join_date, member_name, member_surname, member_id, branch_id, structure_id) "
        . "VALUES ('$member_no',NOW(),'$member_name','$member_surname',$member_id, $branch_id, 3) ";
    //echo $query."<br>";
        $eye =  mysql_query($query);
    
}

$top6 =  "WestCoastREC";

for ($i=1; $i <= 20;$i++){
    
    $member_name = $top6.$i;
    $member_surname = $top6.$i;
    $member_id = rand(0,1000000);
    $member_no = rand(0,100000000);
    $branch_id = rand(7742,7788);    

    $query = "INSERT member(membership_no, join_date, member_name, member_surname, member_id, branch_id, structure_id) "
        . "VALUES ('$member_no',NOW(),'$member_name','$member_surname',$member_id, $branch_id, 3) ";
   // echo $query."<br>";
        $eye =  mysql_query($query);
    
}


$top6 =  "CapeWineLeader";



for ($i=1; $i <= 5;$i++){
    
    $member_name = $top6.$i;
    $member_surname = $top6.$i;
    $member_id = rand(0,1000000);
    $member_no = rand(0,100000000);
    $branch_id = rand(7789,7888);    
    $query = "INSERT member(membership_no, join_date, member_name, member_surname, member_id, branch_id, structure_id) "
        . "VALUES ('$member_no',NOW(),'$member_name','$member_surname',$member_id, $branch_id, 3) ";
    //echo $query."<br>";
        $eye =  mysql_query($query);
    
}

$top6 =  "CapeWineREC";

for ($i=1; $i <= 20;$i++){
    
    $member_name = $top6.$i;
    $member_surname = $top6.$i;
    $member_id = rand(0,1000000);
    $member_no = rand(0,100000000);
    $branch_id = rand(7789,7888);    

    $query = "INSERT member(membership_no, join_date, member_name, member_surname, member_id, branch_id, structure_id) "
        . "VALUES ('$member_no',NOW(),'$member_name','$member_surname',$member_id, $branch_id, 3) ";
   // echo $query."<br>";
        $eye =  mysql_query($query);
    
}





$top6 =  "OverbergLeader";



for ($i=1; $i <= 5;$i++){
    
    $member_name = $top6.$i;
    $member_surname = $top6.$i;
    $member_id = rand(0,1000000);
    $member_no = rand(0,100000000);
    $branch_id = rand(7889,7927);    
    $query = "INSERT member(membership_no, join_date, member_name, member_surname, member_id, branch_id, structure_id) "
        . "VALUES ('$member_no',NOW(),'$member_name','$member_surname',$member_id, $branch_id, 3) ";
    //echo $query."<br>";
        $eye =  mysql_query($query);
    
}

$top6 =  "OverbergREC";

for ($i=1; $i <= 20;$i++){
    
    $member_name = $top6.$i;
    $member_surname = $top6.$i;
    $member_id = rand(0,1000000);
    $member_no = rand(0,100000000);
    $branch_id = rand(7889,7927);    

    $query = "INSERT member(membership_no, join_date, member_name, member_surname, member_id, branch_id, structure_id) "
        . "VALUES ('$member_no',NOW(),'$member_name','$member_surname',$member_id, $branch_id, 3) ";
   // echo $query."<br>";
        $eye =  mysql_query($query);
    
}



$top6 =  "EdenLeader";



for ($i=1; $i <= 5;$i++){
    
    $member_name = $top6.$i;
    $member_surname = $top6.$i;
    $member_id = rand(0,1000000);
    $member_no = rand(0,100000000);
    $branch_id = rand(7928,8012);    
    $query = "INSERT member(membership_no, join_date, member_name, member_surname, member_id, branch_id, structure_id) "
        . "VALUES ('$member_no',NOW(),'$member_name','$member_surname',$member_id, $branch_id, 3) ";
    //echo $query."<br>";
        $eye =  mysql_query($query);
    
}

$top6 =  "EdenREC";

for ($i=1; $i <= 20;$i++){
    
    $member_name = $top6.$i;
    $member_surname = $top6.$i;
    $member_id = rand(0,1000000);
    $member_no = rand(0,100000000);
    $branch_id = rand(7928,8012);    

    $query = "INSERT member(membership_no, join_date, member_name, member_surname, member_id, branch_id, structure_id) "
        . "VALUES ('$member_no',NOW(),'$member_name','$member_surname',$member_id, $branch_id, 3) ";
   // echo $query."<br>";
        $eye =  mysql_query($query);
    
} 



$top6 =  "KarooLeader";



for ($i=1; $i <= 5;$i++){
    
    $member_name = $top6.$i;
    $member_surname = $top6.$i;
    $member_id = rand(0,1000000);
    $member_no = rand(0,100000000);
    $branch_id = rand(8013,8027);    
    $query = "INSERT member(membership_no, join_date, member_name, member_surname, member_id, branch_id, structure_id) "
        . "VALUES ('$member_no',NOW(),'$member_name','$member_surname',$member_id, $branch_id, 3) ";
    //echo $query."<br>";
        $eye =  mysql_query($query);
    
}

$top6 =  "KarooREC";

for ($i=1; $i <= 20;$i++){
    
    $member_name = $top6.$i;
    $member_surname = $top6.$i;
    $member_id = rand(0,1000000);
    $member_no = rand(0,100000000);
    $branch_id = rand(8013,8027);    

    $query = "INSERT member(membership_no, join_date, member_name, member_surname, member_id, branch_id, structure_id) "
        . "VALUES ('$member_no',NOW(),'$member_name','$member_surname',$member_id, $branch_id, 3) ";
   // echo $query."<br>";
        $eye =  mysql_query($query);
    
} 


$top6 =  "Member";





$query = " SELECT * from branches where province_id=9";


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