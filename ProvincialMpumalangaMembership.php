<?php
set_time_limit(7200);
include 'connect_anc.php';

//$num = 123;
$str_length = 3 ;




$top6 =  "JohnThaoleLeader";



for ($i=1; $i <= 5;$i++){
    
    $member_name = $top6.$i;
    $member_surname = $top6.$i;
    $member_id = rand(0,1000000);
    $member_no = rand(0,100000000);
    $branch_id = rand(6266,6298);    
    $query = "INSERT member(membership_no, join_date, member_name, member_surname, member_id, branch_id, structure_id) "
        . "VALUES ('$member_no',NOW(),'$member_name','$member_surname',$member_id, $branch_id, 3) ";
    //echo $query."<br>";
        $eye =  mysql_query($query);
    
}

$top6 =  "JohnThaoleREC";

for ($i=1; $i <= 20;$i++){
    
    $member_name = $top6.$i;
    $member_surname = $top6.$i;
    $member_id = rand(0,1000000);
    $member_no = rand(0,100000000);
    $branch_id = rand(6266,6298);    
    $query = "INSERT member(membership_no, join_date, member_name, member_surname, member_id, branch_id, structure_id) "
        . "VALUES ('$member_no',NOW(),'$member_name','$member_surname',$member_id, $branch_id, 3) ";
   // echo $query."<br>";
        $eye =  mysql_query($query);
    
}





$top6 =  "NamakwaLeader";



for ($i=1; $i <= 5;$i++){
    
    $member_name = $top6.$i;
    $member_surname = $top6.$i;
    $member_id = rand(0,1000000);
    $member_no = rand(0,100000000);
       $branch_id = rand(6299, 6328);    
    $query = "INSERT member(membership_no, join_date, member_name, member_surname, member_id, branch_id, structure_id) "
        . "VALUES ('$member_no',NOW(),'$member_name','$member_surname',$member_id, $branch_id, 3) ";
    //echo $query."<br>";
        $eye =  mysql_query($query);
    
}

$top6 =  "NamakwaREC";

for ($i=1; $i <= 20;$i++){
    
    $member_name = $top6.$i;
    $member_surname = $top6.$i;
    $member_id = rand(0,1000000);
    $member_no = rand(0,100000000);
       $branch_id = rand(6299, 6328);    

    $query = "INSERT member(membership_no, join_date, member_name, member_surname, member_id, branch_id, structure_id) "
        . "VALUES ('$member_no',NOW(),'$member_name','$member_surname',$member_id, $branch_id, 3) ";
   // echo $query."<br>";
        $eye =  mysql_query($query);
    
}


$top6 =  "PixleyLeader";



for ($i=1; $i <= 5;$i++){
    
    $member_name = $top6.$i;
    $member_surname = $top6.$i;
    $member_id = rand(0,1000000);
    $member_no = rand(0,100000000);
    $branch_id = rand(6329,6370);    
    $query = "INSERT member(membership_no, join_date, member_name, member_surname, member_id, branch_id, structure_id) "
        . "VALUES ('$member_no',NOW(),'$member_name','$member_surname',$member_id, $branch_id, 3) ";
    //echo $query."<br>";
        $eye =  mysql_query($query);
    
}

$top6 =  "PixleyREC";

for ($i=1; $i <= 20;$i++){
    
    $member_name = $top6.$i;
    $member_surname = $top6.$i;
    $member_id = rand(0,1000000);
    $member_no = rand(0,100000000);
    $branch_id = rand(6329,6370);    

    $query = "INSERT member(membership_no, join_date, member_name, member_surname, member_id, branch_id, structure_id) "
        . "VALUES ('$member_no',NOW(),'$member_name','$member_surname',$member_id, $branch_id, 3) ";
   // echo $query."<br>";
        $eye =  mysql_query($query);
    
}





$top6 =  "SiyandaLeader";



for ($i=1; $i <= 5;$i++){
    
    $member_name = $top6.$i;
    $member_surname = $top6.$i;
    $member_id = rand(0,1000000);
    $member_no = rand(0,100000000);
    $branch_id = rand(6371,6411);    
    $query = "INSERT member(membership_no, join_date, member_name, member_surname, member_id, branch_id, structure_id) "
        . "VALUES ('$member_no',NOW(),'$member_name','$member_surname',$member_id, $branch_id, 3) ";
    //echo $query."<br>";
        $eye =  mysql_query($query);
    
}

$top6 =  "SiyandaREC";

for ($i=1; $i <= 20;$i++){
    
    $member_name = $top6.$i;
    $member_surname = $top6.$i;
    $member_id = rand(0,1000000);
    $member_no = rand(0,100000000);
    $branch_id = rand(6371,6411);    

    $query = "INSERT member(membership_no, join_date, member_name, member_surname, member_id, branch_id, structure_id) "
        . "VALUES ('$member_no',NOW(),'$member_name','$member_surname',$member_id, $branch_id, 3) ";
   // echo $query."<br>";
        $eye =  mysql_query($query);
    
}



$top6 =  "BaardLeader";



for ($i=1; $i <= 5;$i++){
    
    $member_name = $top6.$i;
    $member_surname = $top6.$i;
    $member_id = rand(0,1000000);
    $member_no = rand(0,100000000);
    $branch_id = rand(6412,6459);    
    $query = "INSERT member(membership_no, join_date, member_name, member_surname, member_id, branch_id, structure_id) "
        . "VALUES ('$member_no',NOW(),'$member_name','$member_surname',$member_id, $branch_id, 3) ";
    //echo $query."<br>";
        $eye =  mysql_query($query);
    
}

$top6 =  "BaardREC";

for ($i=1; $i <= 20;$i++){
    
    $member_name = $top6.$i;
    $member_surname = $top6.$i;
    $member_id = rand(0,1000000);
    $member_no = rand(0,100000000);
    $branch_id = rand(6412,6459);    

    $query = "INSERT member(membership_no, join_date, member_name, member_surname, member_id, branch_id, structure_id) "
        . "VALUES ('$member_no',NOW(),'$member_name','$member_surname',$member_id, $branch_id, 3) ";
   // echo $query."<br>";
        $eye =  mysql_query($query);
    
}


$top6 =  "Member";





$query = " SELECT * from branches where province_id=7";


$res = mysql_query($query);


while($row = mysql_fetch_array($res)) {
         $ward = $row['ward_number'];
         $branch_id = $row['id'];
        $numbers = rand(100,120);
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