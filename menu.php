<?php

include_once 'connect.php';
function display_children($parent, $level) {
    
    
    
    $sql = "SELECT a.menu_code, a.text, a.menu_description, a.parent_code, Deriv1.Count FROM `menu` a  LEFT OUTER JOIN (SELECT parent_code, COUNT(*) AS COUNT FROM `menu` GROUP BY parent_code) Deriv1 ON a.menu_code = Deriv1.parent_code WHERE a.parent_code=".$parent;
   //echo $sql;
    
    
    $result = mysql_query($sql);
    echo "<ul>";
    while ($row = mysql_fetch_assoc($result)) {
        if ($row['Count'] > 0) {
            if ($row['menu_code'] != 0){
            echo '<li> '.$row['menu_code'].' )  ' . $row['text'] .' <button onclick="GetNodeScreen(' . $row['menu_code'] . ')" class="btn btn-primary"><i class="fa fa-plus"></i></button>';
			display_children($row['menu_code'], $level + 1);
	    echo '</li>';
            }elseif($row['Count']==1) {
                 echo "<li> ".$row['menu_code'].")  " . $row['text'] . "</li>";
                 display_children($row['menu_code'], $level + 1);
            }else {
                 echo "<li> " . $row['text'] . "</li>";
            }
        } elseif ($row['Count']==0) {
            echo '<li>  '.$row['menu_code'].') '. $row['text'] . '   <button onclick="GetNodeScreen(' . $row['menu_code'] . ')" class="btn btn-primary"><i class="fa fa-plus"></i></button>   <button onclick="DeleteNode('.$row['menu_code'].')" class="btn btn-danger">  <i class="fa fa-minus"></i> </button>  </li>';
        } else;
    }
    echo "</ul>";
}


