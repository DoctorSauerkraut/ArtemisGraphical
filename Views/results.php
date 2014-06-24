<?php 
	$nbfile=0;
	if($folder = opendir('./Templates/results')){ 
		echo " <div id='results' style='text-align:center;'>  
					<table class='tableShow'>
						<caption> Results Table </caption>
							<tr>
								<th> Node ID</th><th>Name</th><th>Result</th>
							</tr> ";
							foreach($donnees1 as $element){ 
								echo "	<tr><td>".$element->id()."</td><td>";
								echo $element->name()." </td>";
								rewinddir();
								while(false !== ($file = readdir($folder))){ 
									if ( $file != '.' && $file != '..' && $file != 'index.php'){ 
										$files=substr($file,13,-8);
										if($files == $element->id()){			
											echo '<td><p><a href="./Templates/results/'.$file.'?>" target="_BLANK"><img src="./Templates/zoom.png"/></a></p></td>';
											/*echo '<td><p><a href="./Templates/results/'.$file.'?>" target="_BLANK"><img src="./Templates/results/'.$file.'?"/></a></p></td>';*/
											$nbfile++;
											break;
											}
									}
								} 			
							}
					echo '</table>
					<p>There is <strong>' . $nbfile .'</strong> corresponding file(s) in the folder.</p>';
					closedir($folder);
	} else {
		echo "Error: Fail to open the folder.";
	} 
	echo "Simulation Time:0.35s</div>";