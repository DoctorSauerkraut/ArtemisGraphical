<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
    <head>
        <title>ARTEMIS</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF8" />
		<link type="text/css" href="styles/artemis.css" rel="stylesheet"/>
		<link type="text/css" href="styles/menu.css" rel="stylesheet"/>
		<link type="text/css" rel="stylesheet" href="./vis/dist/vis.css">
		<link rel="icon" href="./Templates/artemisicon.png" />
	</head>
	<body>
<?php 
			$id_sel=$_GET['id_sel'];
			$filename = './export/export_simu_'.$id_sel.'.zip';		
			$targetDir='./ressources/'.$id_sel.'/input/';
			if(file_exists($targetDir)){
				$zip = new ZipArchive;
				$files = array('messages.xml','network.xml','config.xml','graphconfig.xml');			
				if ($zip->open($filename, ZipArchive::OVERWRITE)===TRUE) {
					foreach ($files as $file) {
				    	if(file_exists($targetDir.$file)){
							// echo $file." existe \n";
							var_dump($zip->addFile($targetDir.$file,$file));
							var_dump($zip);
						}else{
							echo $file." n'existe PAS";
						}
				    }
				    var_dump($zip->close());
				    var_dump($zip);
				    chmod($filename, 0777);
				}else{
				   	echo 'échec';
				}		
				$filetest='export_simu_'.$id_sel.'.zip';
				$filepath='./export/';
				ob_start();
				header("Pragma: public");
				header("Expires: 0");
				header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
				header("Cache-Control: public");
				header("Content-Description: File Transfer");
				header("Content-type: application/octet-stream");
				header("Content-Disposition: attachment; filename=\"".$filetest."\"");
				header("Content-Transfer-Encoding: application/zip");
				header("Content-Length: ".filesize($filepath.$filetest));
				ob_end_flush();
				ob_clean();
				var_dump(readfile($filepath.$filetest));
			}else{
				echo '<div id="grayer" style="visibility:visible;" onclick="closePopup()"></div>';
				echo '<div id="popup" style="visibility:visible;">';
				$popupHead = "<div id=\"popupHead\">";
				$popupHead .= "<div id=\"popupTitle\">Export impossible</div>";
				$popupHead .= "<div id=\"closePopup\" onclick=\"closePopup()\"></div>";
				$popupHead .= "</div>";
				echo $popupHead;
				echo "<div id=\"popupBodyText\">This file doesn't exist yet, run simulation then try again</div>";
				echo "<a href=\"index.php\" class=\"button green\" onclick=\"closePopup()\" />Ok</a>";
				echo '</div>';
			}
?>
	</body>
	
 
		
