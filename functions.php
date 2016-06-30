<?php		
    include("colors.php");

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
		  require 'Library/Entities/NodeGraph.class.php';
          require 'Library/Actions/ElementsEditor.class.php';
          require 'Library/Actions/SimulationManager.class.php';
          require 'Library/Actions/SimuIdManager.class.php';
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
		echo '<meta http-equiv="refresh" content="0">';
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
						$shape=$value->attributes()['shape'];
						$bdd = connectBDD();
						$sql = "INSERT INTO node (id_simu,name,ip_address,scheduling,displayed,speed,shape) VALUES (\"$simuKey\",\"$nodeName\",'0','FIFO','0','1',\"$shape\")"; // on insere en BDD les noeuds sous de nouveaux id
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

function prepareTopo($donnees2,$donnees1){
		foreach ($donnees2 as $link) { 						// pour chaque lien
			$nodes[$link->node1()]=$nodes[$link->node1()]+1;// on crée un tableau du nombre de 
			$nodes[$link->node2()]=$nodes[$link->node2()]+1;// liens par noeud
		}
		arsort($nodes); // on tri les noeuds pas ordre décroissants
		// print_r($donnees1);
		foreach ($nodes as $id => $nblinks) { 					// pour chaque noeud
		foreach ($donnees1 as $nodeName) {
			if($nodeName->id()==$id){
				$tabNodeSchema['name']=$nodeName->name();
			}
		}
			$nodeSchema= new nodeGraph();						// on crée une instance de noeud

			$nodeSchema->setId($id);							// on récupère l'id du noeud
			$tabNodeSchema['id']=$nodeSchema->id();				// on le met dans un tableau

			$nodeSchema->setNblinks($nblinks);					// on récupère le nombre de liens du noeud
			$tabNodeSchema['nblinks']=$nodeSchema->nbLinks();	// on le met dans un tableau
			$tabNodeSchema['nblinksleft']=$nodeSchema->nbLinks();

			$nodeSchema->setParent('');							// on attribue le parent du noeud
			$tabNodeSchema['parent']=$nodeSchema->parent();		// on le met dans un tableau

			$nodeSchema->setRank(0);							// on attribue le rang (la profondeur) du noeud
			$tabNodeSchema['rank']=$nodeSchema->rank();			// on le met dans un tableau
 
			if($nblinks>1){
				$nodeSchema->setShape('square');				// on attribue la forme du noeud
			}else{
				$nodeSchema->setShape('round');
				$nodeSchema->setTaille(1);
			}
			$tabNodeSchema['shape']=$nodeSchema->shape();		// on le met dans un tableau
			$tabNodeSchema['taille']=$nodeSchema->taille();
			$tabNodeSchema['posX']=$nodeSchema->posX();
			$tabNodeSchema['posY']=$nodeSchema->posY();

			$nodeSchemas[$nodeSchema->id()]= $tabNodeSchema;	// on crée un tableau de tableau de noeuds
		}
		$i=0;

		$count=0;
		$rankmax=0;
		foreach ($nodeSchemas as $node) {
			if($count==0){										// si c'est le premier noeud traité
				$nodeSchemas[$node['id']]['parent']='none';		// alors il n'a pas de parent
					$tabNodeI[$node['id']]=$i;
					$i++;
				$count=1;
			}
			$notfinish=1;
		}

		while($notfinish!=0){
			$count=0;
			foreach ($nodeSchemas as $node) {
				if($nodeSchemas[$node['id']]['parent']!=''){
					$count=$count;
				}
				else{
					$count++;
				}
				$notfinish=$count;
				
			}
			foreach ($nodeSchemas as $node) {
				foreach ($donnees2 as $link) {
					if($nodeSchemas[$node['id']]['parent']!=''){
						if($node['id']==$link->node1() AND $nodeSchemas[$link->node2()]['parent']==''){
							$nodeSchemas[$link->node2()]['parent']=$node['id'];
							$tabNodeI[$link->node2()]=$i;
							$i++;
					}
						if($node['id']==$link->node2() AND $nodeSchemas[$link->node1()]['parent']==''){
							$nodeSchemas[$link->node1()]['parent']=$node['id'];
							$tabNodeI[$link->node1()]=$i;
							$i++;
						}
					}
				}
			}
		}		
		
		$i=0;
		
		// foreach ($nodeSchemas as $node) {
		// 	if($tabNodeI[$node['id']]<$tabNodeI[$node['parent']]){
		// 		$temp=$tabNodeI[$node['id']];
		// 		$tabNodeI[$node['id']]=$tabNodeI[$node['parent']];
		// 		$tabNodeI[$node['parent']]=$temp;
		// 	}
		// }
		foreach ($nodeSchemas as $node) {
			$nodeSchemas[$node['id']]['i']=$tabNodeI[$node['id']];
		}
		$nodeSchemas=array_sort($nodeSchemas,'i',SORT_ASC);
		foreach ($nodeSchemas as $node) {
			if($node['parent']!='none'){
					$nodeSchemas[$node['id']]['rank']=$nodeSchemas[$node['parent']]['rank']+1;
					if($nodeSchemas[$node['id']]['rank']>$rankmax){
						$rankmax=$nodeSchemas[$node['id']]['rank'];
					}
				}
		}
		for ($i=$rankmax;$i>0;$i--){
			foreach ($nodeSchemas as $node) {
				if($node['rank']==$i AND $node['taille']!=0){
					$nodeSchemas[$node['parent']]['taille']=$nodeSchemas[$node['parent']]['taille']+$node['taille'];
				}
			}
		}
		return $nodeSchemas;
	}

	function array_sort($array, $on, $order=SORT_ASC){
		
	    $new_array = array();
	    $sortable_array = array();		
	    if (count($array) > 0) {		
	        foreach ($array as $key=>$value) {
	            if (is_array($value)) {
	            	
	                foreach ($value as $k2 => $v2) {
	                    if ($k2 == $on) {
	                        $sortable_array[$key] = $v2;
	                    }
	                }
	            } else {
	                $sortable_array[$key] = $v;
	            }
	        }
	        switch ($order) {
	            case SORT_ASC:
	                asort($sortable_array);
	            break;
	            case SORT_DESC:
	                arsort($sortable_array);
	            break;
	        }

	        foreach ($sortable_array as $key => $value) {
	            $new_array[$key] = $array[$key];
	        }
	    }
	    return $new_array;
	}

	function drawTopo($topologie){
		$topo=array_sort($topologie,'rank',SORT_ASC);
		$taillemax=0;
		$rankmax=0;
		foreach ($topo as $node) {
			if($node['taille']>$taillemax){
				$taillemax=$node['taille'];
			}	
			if($node['rank']>$rankmax){
				$rankmax=$node['rank'];
			}
		}
		if($topo!=array()){
			echo '<a id="zoomplus" onclick="zoomplus();"></a>';
			echo '<a id="zoomminus" onclick="zoomminus();"></a>';
		}
		if(($taillemax*50)>1250){
			$scroll='overflow: scroll;';
		}
		echo '<div id="topology" style="width:'.($taillemax*50).'px; height:'.(($rankmax+1.2)*80).'px;'.$scroll.'">';
		echo '<canvas id="canvas" onclick="reloadgraph(event);"style="transform: scale(1); width:'.($taillemax*50).'px; height:'.(($rankmax+1)*80).'px;"></canvas>';
		foreach($topo as $node){
			if ($node['parent']=='none'){
				$topo=placement($node,$topo);
			}
			foreach ($topo as $child) {
				if($child['parent']==$node['id'] AND $child['rank']==$node['rank']+1){
					$topo=placement($child,$topo);
				}
			}
		}
		echo'</div>';

		return $topo;
	}

	function placement($node,$topo){
		if ($node['parent']=='none'){
			$node['posX']=($node['taille']*50)/2 - 25;
			$node['posY']=$node['rank']*50;
			$topo[$node['id']]['posX']=$node['posX'];
			$topo[$node['id']]['posY']=$node['posY'];
			echo '</br>';
			echo '<div class="'.$node['shape'].'" id="'.$node['id'].'" style="display: none; left:'.$node['posX'].'px; top:'.$node['posY'].'px;">'.$node['name'].'</div>';
		}
		else if($node['parent']!='none'){
			$tailleParent=$topo[$node['parent']]['taille'];
			$posXParent=$topo[$node['parent']]['posX'];
			if($_SESSION['pos']==0){
				$depart=($posXParent+25)-(($tailleParent/2)*50);
			}else{
				$depart=$_SESSION['pos'];
			}
			
			$node['posX']=$depart+(($node['taille']*50)/2) - 25;
			$node['posY']=$node['rank']*80;
			echo '<div class="'.$node['shape'].'" id="'.$node['id'].'" style="display: none;  left:'.$node['posX'].'px; top:'.$node['posY'].'px;">'.$node['name'].'</div>';
			$_SESSION['pos']=$depart+($node['taille']*50);
			$topo[$node['id']]['posX']=$node['posX'];
			$topo[$node['id']]['posY']=$node['posY'];
			$topo[$node['parent']]['nblinksleft']=$topo[$node['parent']]['nblinksleft']-1;
			if($topo[$node['parent']]['nblinksleft']==0 AND $topo[$node['parent']]['rank']==0){
				$_SESSION['pos']=0;
			}elseif ($topo[$node['parent']]['nblinksleft']==1 AND $topo[$node['parent']]['rank']!=0) {
				$_SESSION['pos']=0;
			}

		}
		return $topo;
	}
?>