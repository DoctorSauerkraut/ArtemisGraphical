<?php

function activeColorBox($id,$color){
		echo '<a class="activeColorBox button" style="background-color:'.$color.'" id="activeColorBox'.$id.'" onclick="activeColorBox(\''.$id.'\',0)" >Color</a>';
		echo '<div class="colorChoice" id="colorChoice'.$id.'" style="display:none">';
		$colors = array("#0000FF", "#00FF00", "#FF0000", "#CC00FF",
  			"#FF66FF", "#FFFF00", "#99FFFF", "#990066",
  			"#FF0099", "#CC6633", "#666699", "#FF9900",
			"#900000", "#C0C0C0", "#808080", "#660066");
		echo '<input type="text" name="color" id="inputColor'.$id.'" class="inputColor" value="'.$color.'" onclick="deactivateRadio(\''.$id.'\');"/>';
    	echo '<a class="valideColor" onclick="valideColor(\''.$id.'\');"></a>';  
    	echo '<table>';
	    foreach($colors as $color){
	    	if($color=="#0000FF" OR $color=="#FF66FF" OR $color=="#FF0099" OR $color=="#900000" ){
	    		echo'<tr>';	    		
	    	}
	    	$col=substr($color, 1);
	    	echo '<td style="background-color: '.$color.';" >';
	    	echo '<label>';
	    	echo '<input type="radio" name="color" id="thecolor'.$id.'" value="'.$col.'" onclick="activeColorBox(\''.$id.'\',\''.$col.'\');"><span></span>';
	    	echo '</label>';
	    	echo '</td>';
	    	if($color=="#CC00FF" OR $color=="#990066" OR $color=="#FF9900" OR $color=="#660066"){
 	    		echo '</tr>';
    		}
	    }
	    echo'</table>';
	echo'</div>';
	$id='';
	}


?>