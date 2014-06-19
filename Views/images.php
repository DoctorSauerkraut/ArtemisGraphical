<?php 
	if($folder = opendir('../Templates/results')){
		while(false !== ($file = readdir($folder))){
			if($file != '.' && $file != '..' && $file != 'index.php'){
				echo '<p><img src="../Templates/results/'.$file.'"/></p>';	
			}
		}	
		closedir($folder);
	}else {
		echo "Error: Fail to open the folder.";
	}

?>