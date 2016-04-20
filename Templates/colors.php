<!DOCTYPE html>
<html>
<head>
    <style>
        td {
            width:100px;
            height:50px;
            text-align: center;
        }
        
        table{
            float:left;
            margin-left:10px;
            margin-top:10px;
        }
    </style>
</head>
<body>
<?php

echo "<table>";

$colors = array(
    "000", 
    "005", "050", "500", "001", "010", "100", "004", "040", "400", "002", "020", "200", "003", "030", "300",
    "005", "050", "500", "001", "010", "100", "004", "040", "400", "002", "020", "200", "003", "030", "300",
     "100");
    
for ($i=0; $i < 216; $i++) {
    if($i%16 == 0) 
        echo "<tr>";
    
    $red    = substr($colors[$i], 0,1);
    $green  = substr($colors[$i], 1,1);
    $blue   = substr($colors[$i], 2,1);
    
    echo "<td style=\"background-color:rgb(".($red*51).",".($green*51).",".($blue*51).");\" >";
    echo $red." ".$green." ".$blue."</td>";
    
    if($i%16==15)
        echo "</tr>";
    
}
echo "</table>";
?>
    

</body>
</html>