<div id="results" style="text-align:center;"> 
<?php 
$nbfile=0;
if($folder = opendir('../Templates/results')){
	while(false !== ($file = readdir($folder))){
		if($file != '.' && $file != '..' && $file != 'index.php'){
		echo '<p><a href="./Templates/results/'.$file.'"><img src="./Templates/results/'.$file.'"/></a></p>';	
		$nbfile++;
		}
	}	
	echo 'There is <strong>' . $nbfile .'</strong> file(s) in the folder.';
closedir($folder);
}else {
echo "Error: Fail to open the folder.";
}

?>
<p><a href="./index.php"> Return to the Home Page </a></p> 
</div>