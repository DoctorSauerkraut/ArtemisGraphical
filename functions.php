<?php		
	 function connectBDD() {
	 	try
		{
			include('config.php');
			$bdd = new PDO("mysql:host=$db_host;dbname=$db_name", "$db_user", "$db_pass",array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
			
		}
		catch (Exception $e)
		{
			die('Erreur : ' . $e->getMessage());
		}
		
		return $bdd;
	 }
		
	function chargerClasse($classe)
		{
		if ($classe == 'Manager' ){
		  require 'Library/Models/'.str_replace('\\', '/', $classe).'.class.php'; 
		  require 'Library/Entities/Node.class.php';
		  require 'Library/Entities/Link.class.php';
		  require 'Library/Entities/Message.class.php';
		  require 'Library/Entities/CriticalitySwitch.class.php';
		  require 'Library/Entities/CriticalityLevel.class.php';
		  require 'Library/Entities/Settings.class.php';
		  require 'Library/Entities/Simulation.class.php';
		  }
		}
		
	function initManager() {
		
		$bdd = connectBDD();
		
		$manager = new Manager($bdd);
		
		return $manager;
	}
 
 	function is_dir_empty($dir) {
	  if (!is_readable($dir)) return NULL; 
	  $handle = opendir($dir);
	  while (false !== ($entry = readdir($handle))) {
	    if ($entry != "." && $entry != "..") {
	      return FALSE;
	    }
	  }
	  return TRUE;
	}
	
	function getSessionId() {
		return session_id();
		
	}
	function create_session($session_id) {
		return Settings::createSimulation($session_id);
	}

	function import(){
		require 'Library/Entities/Settings.class.php';
		/////////////////////// création d'une nouvelle simulation ///////////////////////////////////////////////
		$id=getSessionId(); 	// on recupère l'id de session actuel
		$bdd = connectBDD();
		$sql 	= "SELECT id_simu FROM simulations"; // on selectionne toutes les simulations
		$result = $bdd->query($sql);		// on execute la requete
		$idSimu = 0;						// on initialise un compteur à 0
		while($data = $result->fetch()){
			if($data["id_simu"] > $idSimu) {
				$idSimu = $data["id_simu"];	// tant qu'il y a des simulations le compteurs prend l'id le plus grand
			}
		}
		$idSimu++;		// le compteur prend l'id le plus grand +1
		$sql  = "INSERT INTO simulations (`id_simu`, `id_session`)"; // requete pour inserer la simulation 
		$sql .= " VALUES (\"$idSimu\", \"$id\")";		
		$result = $bdd->query($sql);	
		$_SESSION["simuid"] = $idSimu;   // on attribue l'id de simu à la session en cours
		$_SESSION["id_sel"] = $idSimu;
		$simuKey = $_SESSION["simuid"];
		/////////////////////////////////////////////////////////////////////////////////////////////////////////

		///////////////////////// enregistrement de l'archive ///////////////////////////////////////////////////
		$chemin_destination = './ressources/'.$simuKey.'/input';  // chemin de destination de l'archive
		if(!file_exists($chemin_destination)){				// si le repertoire n'existe pas
			mkdir($chemin_destination, 0777, true);			// on le crée
			chmod('./ressources/'.$simuKey, 0777);			// et on donne les droits necessaires à la suite
			chmod('./ressources/'.$simuKey.'/input', 0777);
		}
		move_uploaded_file($_FILES['import']['tmp_name'], $chemin_destination.'/'.$_FILES['import']['name']); // on enregistre l'archive dans le repertoire voulu
		chmod($chemin_destination.$_FILES['import']['name'], 0777); // on donne les droit au fichier pour pouvoir l'ouvrir plus tard
		/////////////////////////////////////////////////////////////////////////////////////////////////////////

		////////////////////////// extraction des fichiers de l'archive /////////////////////////////////////////
		$zip = new ZipArchive;		// création de l'objet archive
		$targetDir='./ressources/'.$simuKey.'/input/'; // repertoire cible (de destination)
		$filename=$targetDir.$_FILES['import']['name'];	// construction du chemin a partir du rep cible et du nom de l'archive
		if ($zip->open($filename)===TRUE) { // si le zip a bien été ouvert (peut echouer si pas ob_clean lor du telechargement de l'archive)
		    if(file_exists($filename)){		// si l'archive existe
				$zip->extractTo($targetDir);// on extrait dans le repertoire cible
			}else{echo $filename." doesn't exist";}
		    $zip->close();	// on ferme l'objet archive
		    unlink($filename);// on supprime l'archive après avoir extrait les fichiers
		} else {
		    echo 'Files extraction failed. ';
		}
		// echo '<meta http-equiv="refresh" content="0">';
		////////////////////////////////////////////////////////////////////////////////////////////////////////

		/////////////////////////////// enregistrement dans la BDD /////////////////////////////////////////////
		$files=array('config.xml','graphconfig.xml','network.xml','messages.xml');
		foreach ($files as $file) { // pour les 4 fichiers XML presents
			switch($file){
				case 'config.xml':
					$xml = simplexml_load_file("ressources/".$simuKey."/input/config.xml"); // pour config.xml, on crée un tableau avec toutes les infos
					foreach ($xml as $key=>$value){ // pour chaque info
				 		if($value!=""){ 											// si la valeur n'est pas nulle
				 		Settings::save(str_replace('-','',$key), $value, $simuKey); // on enregistre dans la BDD
						}
					}
					break;
				case 'graphconfig.xml': 
					$xml = simplexml_load_file("ressources/".$simuKey."/input/graphconfig.xml"); // pour graphconfig.xml, on crée un tableau avec toutes les infos
					foreach ($xml as $key=>$value){ // pour chaque info
				 		if($key=='starttime' OR $key=='endtime'){
				 			if($value!=""){											// si la valeur n'est pas nulle
				 				Settings::save(str_replace('time','graphtime',$key), $value, $simuKey);// on enregistre dans la BDD
				 			}
						}else if($key=='message-color'){ // récupération des couleurs des messages selon leur id
							foreach ($value as $message => $color) {
								 $messagesColor[''.$color->attributes()['id']]=''.$color->attributes()['color']; // création d'un tableau id=>couleur qui va être utilisé pour traiter messages.xml
							}
						}
					}
					break;
				case 'network.xml':
					$xml = simplexml_load_file("ressources/".$simuKey."/input/network.xml"); // pour network.xml, on crée un tableau avec toutes les infos
					$liens=array();
					foreach ($xml as $key=>$value){ // pour chaque infos
						$nodeName=$value->attributes()['name']; // on recupere le nom du noeud
						$bdd = connectBDD();
						$sql = "INSERT INTO node (id_simu,name,ip_address,scheduling,displayed,speed) VALUES (\"$simuKey\",\"$nodeName\",'0','FIFO','0','1')"; // on insere en BDD les noeuds sous de nouveaux id
						$insertNode = $bdd->query($sql);	// on execute
						$sql2 = "SELECT id FROM node WHERE name = \"$nodeName\" AND id_simu=\"$simuKey\""; // on recupere le nouvel id du noeuds inseré
						$recupNewId= $bdd->query($sql2); // on execute la requete
						$newId=$recupNewId->fetch();
						$nodes[''.$value->attributes()['id']]=array(''.$newId[0],''.$nodeName); // on rempli le tableau nodes avec l'ancien et le nouvel id
						
						$depart= $value->attributes()['id']; // on recupere l'id du noeud de départ
						foreach ($value->Links->machinel as $key1 => $value1) { // pour chaque liens
							$arrivee=$value1->attributes()['id']; // on récupere l'id du noeud d'arrivée
							$lien=array(''.$depart,''.$arrivee); // on crée un tableau contenant les anciens ids des noeuds composant le lien
							asort($lien); // on range les noeuds dans l'ordre croissant (pour eviter les doublons plus tard)
							$liens[]=$lien; // on empile tous les liens dans un tableau de liens
						}						
					}
					$liens = array_map('implode', array_fill(0, count($liens), '|'), $liens);
					$liens = array_unique($liens);
					$liens = array_map('explode', array_fill(0, count($liens), '|'), $liens); // procédure de suppression des doublons

					// identification des liens par rapport aux id noeuds (ancien et nouveau)
					foreach ($liens as $lien) { // pour chaque liens dans le tableau
						foreach ($nodes as $key => $node) { // pour chaque noeud dans le tableau 
							if ($key==$lien[0]){	// si le noeud de depart du lien correspond au noeud courant
								$lien_depart=$node[0]; // on attribut le nouvel id au lien
							}
							if ($key==$lien[1]){ 	// si le noeud d'arrivée du lien correspond au noeud courant
								$lien_arrivee=$node[0]; // on attribut le nouvel id au lien
							}
						}
						$sql3 = "INSERT INTO link (id_simu,node1,node2) VALUES (\"$simuKey\",\"$lien_depart\",\"$lien_arrivee\")"; // on insere le lien qui contient désormais les nouveaux id des noeuds importés
						$insertLink = $bdd->query($sql3); // execution
						$liensBDD[] = array($lien_depart,$lien_arrivee);
					}
					break;
				case 'messages.xml':
					$xml = simplexml_load_file("ressources/".$simuKey."/input/messages.xml");  // pour network.xml, on crée un tableau avec toutes les infos
					$i=0;
					foreach ($xml as $key => $value) { // pour chaque message
						$expath=$value->criticality->path; 		// on recupère le path						
						$listPath=explode(',', $expath);
						$path = array();
						foreach ($listPath as $key1 => $value1) {	// transformation du path originellement en id en name			
							foreach ($nodes as $key2 => $node) {	// tout en faisant correspondre l'ancien id avec le nouveau
								if($value1==$key2){
									$path[]=trim($node[1]);
								}
							}
						}
						$id_mess=''.$value->attributes()['id']; // récupération de l'id du message en cours
						$path=implode(',', $path); // on recrée une chaine pour le path
						$period=$value->criticality->period;	// on recupère la periode
						$offset=$value->criticality->offset;	// on recupère l'offset'
						$wcet=$value->criticality->offset;	// on recupère l'offset
						$color=$messagesColor[$id_mess];	// on attribue la couleur au message par rapport à son id
						$sql = "INSERT INTO message (id_simu,path,period,offset,color) VALUES (\"$simuKey\",\"$path\",\"$period\",\"$offset\",\"$color\")";// on insere le message en base
						$insertMessage = $bdd->query($sql); // execution 
						$sql2 = "SELECT id FROM message WHERE path = \"$path\" AND id_simu=\"$simuKey\" AND period=\"$period\" AND offset=\"$offset\" AND color=\"$color\""; // on recupere l'id du message 
						$recupNewId= $bdd->query($sql2); // on execute la requete
						$sql3 = "INSERT INTO wcets (id_simu,path,period,offset) VALUES (\"$simuKey\",\"$path\",\"$period\",\"$offset\")";// on insere le message en base
						// // $insertwcet = $bdd->query($sql3); // execution 

					}
					break;
			}
		}
		////////////////////////////////////////////////////////////////////////////////////////////////////////////
	}

	function activeColorBox($id,$color){
		echo '<a class="activeColorBox button" style="background-color:'.$color.'" id="activeColorBox'.$id.'" onclick="activeColorBox(0,\''.$id.'\')" >Color</a>';
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
	    	echo '<input type="radio" name="color" id="thecolor'.$id.'" value="'.$col.'" onclick="activeColorBox(\''.$col.'\',\''.$id.'\');"><span></span>';
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