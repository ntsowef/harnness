<?php
set_time_limit(7200);
include 'connect_anc.php';

//$num = 123;
$str_length = 3 ;




$top6 =  "MopaniLeader";



for ($i=1; $i <= 5;$i++){
    
    $member_name = $top6.$i;
    $member_surname = $top6.$i;
    $member_id = rand(0,1000000);
    $member_no = rand(0,100000000);
    $branch_id = rand(5282,5621);    
    $query = "INSERT member(membership_no, join_date, member_name, member_surname, member_id, branch_id, structure_id) "
        . "VALUES ('$member_no',NOW(),'$member_name','$member_surname',$member_id, $branch_id, 3) ";
    //echo $query."<br>";
        $eye =  mysql_query($query);
    
}

$top6 =  "MopaniREC";

for ($i=1; $i <= 20;$i++){
    
    $member_name = $top6.$i;
    $member_surname = $top6.$i;
    $member_id = rand(0,1000000);
    $member_no = rand(0,100000000);
    $branch_id = rand(5282,5621);    
    $query = "INSERT member(membership_no, join_date, member_name, member_surname, member_id, branch_id, structure_id) "
        . "VALUES ('$member_no',NOW(),'$member_name','$member_surname',$member_id, $branch_id, 3) ";
   // echo $query."<br>";
        $eye =  mysql_query($query);
    
}





$top6 =  "VhembeLeader";



for ($i=1; $i <= 5;$i++){
    
    $member_name = $top6.$i;
    $member_surname = $top6.$i;
    $member_id = rand(0,1000000);
    $member_no = rand(0,100000000);
       $branch_id = rand(5412,5538);    
    $query = "INSERT member(membership_no, join_date, member_name, member_surname, member_id, branch_id, structure_id) "
        . "VALUES ('$member_no',NOW(),'$member_name','$member_surname',$member_id, $branch_id, 3) ";
    //echo $query."<br>";
        $eye =  mysql_query($query);
    
}

$top6 =  "VhembeREC";

for ($i=1; $i <= 20;$i++){
    
    $member_name = $top6.$i;
    $member_surname = $top6.$i;
    $member_id = rand(0,1000000);
    $member_no = rand(0,100000000);
       $branch_id = rand(5412,5538);    

    $query = "INSERT member(membership_no, join_date, member_name, member_surname, member_id, branch_id, structure_id) "
        . "VALUES ('$member_no',NOW(),'$member_name','$member_surname',$member_id, $branch_id, 3) ";
   // echo $query."<br>";
        $eye =  mysql_query($query);
    
}


$top6 =  "WaterbergLeader";



for ($i=1; $i <= 5;$i++){
    
    $member_name = $top6.$i;
    $member_surname = $top6.$i;
    $member_id = rand(0,1000000);
    $member_no = rand(0,100000000);
    $branch_id = rand(5652,5748);    
    $query = "INSERT member(membership_no, join_date, member_name, member_surname, member_id, branch_id, structure_id) "
        . "VALUES ('$member_no',NOW(),'$member_name','$member_surname',$member_id, $branch_id, 3) ";
    //echo $query."<br>";
        $eye =  mysql_query($query);
    
}

$top6 =  "WaterbergREC";

for ($i=1; $i <= 20;$i++){
    
    $member_name = $top6.$i;
    $member_surname = $top6.$i;
    $member_id = rand(0,1000000);
    $member_no = rand(0,100000000);
    $branch_id = rand(5652,5748);    

    $query = "INSERT member(membership_no, join_date, member_name, member_surname, member_id, branch_id, structure_id) "
        . "VALUES ('$member_no',NOW(),'$member_name','$member_surname',$member_id, $branch_id, 3) ";
   // echo $query."<br>";
        $eye =  mysql_query($query);
    
}





$top6 =  "SkhukhuniLeader";



for ($i=1; $i <= 5;$i++){
    
    $member_name = $top6.$i;
    $member_surname = $top6.$i;
    $member_id = rand(0,1000000);
    $member_no = rand(0,100000000);
    $branch_id = rand(5749,5865);    
    $query = "INSERT member(membership_no, join_date, member_name, member_surname, member_id, branch_id, structure_id) "
        . "VALUES ('$member_no',NOW(),'$member_name','$member_surname',$member_id, $branch_id, 3) ";
    //echo $query."<br>";
        $eye =  mysql_query($query);
    
}

$top6 =  "SkhukhuniREC";

for ($i=1; $i <= 20;$i++){
    
    $member_name = $top6.$i;
    $member_surname = $top6.$i;
    $member_id = rand(0,1000000);
    $member_no = rand(0,100000000);
    $branch_id = rand(5749,5865);    

    $query = "INSERT member(membership_no, join_date, member_name, member_surname, member_id, branch_id, structure_id) "
        . "VALUES ('$member_no',NOW(),'$member_name','$member_surname',$member_id, $branch_id, 3) ";
   // echo $query."<br>";
        $eye =  mysql_query($query);
    
}



$top6 =  "CapricornLeader";



for ($i=1; $i <= 5;$i++){
    
    $member_name = $top6.$i;
    $member_surname = $top6.$i;
    $member_id = rand(0,1000000);
    $member_no = rand(0,100000000);
    $branch_id = rand(5539,5631);    
    $query = "INSERT member(membership_no, join_date, member_name, member_surname, member_id, branch_id, structure_id) "
        . "VALUES ('$member_no',NOW(),'$member_name','$member_surname',$member_id, $branch_id, 3) ";
    //echo $query."<br>";
        $eye =  mysql_query($query);
    
}

$top6 =  "CapricornREC";

for ($i=1; $i <= 20;$i++){
    
    $member_name = $top6.$i;
    $member_surname = $top6.$i;
    $member_id = rand(0,1000000);
    $member_no = rand(0,100000000);
    $branch_id = rand(5539,5631);    

    $query = "INSERT member(membership_no, join_date, member_name, member_surname, member_id, branch_id, structure_id) "
        . "VALUES ('$member_no',NOW(),'$member_name','$member_surname',$member_id, $branch_id, 3) ";
   // echo $query."<br>";
        $eye =  mysql_query($query);
    
}


$top6 =  "Member";





$query = " SELECT * from branches where province_id=3";


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