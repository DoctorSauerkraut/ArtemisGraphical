<?php 
$nbfile=0;
if($folder = opendir('./Templates/results')){ ?>

	<div id="results" style="text-align:center;"> 
	<table class="tableShow">
	<caption> Results Table </caption>
	<tr>
	<th> Node ID</th><th>Name</th><th>Result</th>
	</tr>
		<?php foreach($donnees1 as $element){ ?>
		<tr><td><?php echo $element->id(); ?> </td>
		<td><?php echo $element->name(); ?> </td>
		<?php 		while(false !== ($file = readdir($folder))){ 
					if ( $file != '.' && $file != '..' && $file != 'index.php'){ 
					$files=substr($file,13,-8);
					if($files == $element->id()){
								?>
					<td><p><a href="./Templates/results/<?php echo $file ?>" target="_BLANK"><img src="./Templates/zoom.png"/></a></p></td>
					<?php   $nbfile++;
					break;			}
					}
				} 			
		} ?>
	</table>	
	<?php echo '<p>There is <strong>' . $nbfile .'</strong> corresponding file(s) in the folder.</p>';
	closedir($folder);
	} else {
echo "Error: Fail to open the folder.";
}
?>

<p><a href="./Views/images.php" target="_BLANK">Click here to view all the results</a></p>
<p><a href="./index.php"> Return to the Home Page </a></p> 
</div>