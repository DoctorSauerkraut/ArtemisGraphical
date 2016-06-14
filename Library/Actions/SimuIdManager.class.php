<?php
session_start();

include ("../../functions.php");

class SimuIdManager {
    private $manager;
    private $simuKey;
    private $pathToCore;
    
     public function __construct($managerP, $simuKeyP, $pathToCoreP) {
        $this->manager  = $managerP;
        $this->simuKey  = $simuKeyP;
        $this->pathToCore = $pathToCoreP;
    }
    
    public function selectSimu($idSimu) {
        $_SESSION['id_sel']=$idSimu;
        
        $id=getSessionId();
		create_session($id);
        
		return $_SESSION["simuid"];
    }
    
    public function deleteSimu($id_sel) {
        // suppression des simulations et de tous les éléments associés dans la base de données
		$bdd 	= connectBDD();
		$tables=array('simulations','config','node','link','message');
        
		foreach($tables as $tbl) {
			$rq_sel='SELECT id FROM '.$tbl.' WHERE id_simu='.$id_sel;
			$res = $bdd->query($rq_sel);
			while($select=$res->fetch()){
				if(isset($select)){
					$rq_del='DELETE FROM '.$tbl.' WHERE id='.$select['id'];
					$resu = $bdd->query($rq_del);
					if($tbl=="message"){
						$rq_del_message='DELETE FROM wcets WHERE id_msg='.$select['id'];
						$resultats = $bdd->query($rq_del_message);
					}
				}
			}
		}
		$fileToDel=$pathToCore.'./ressources/'.$id_sel;
		if(file_exists($fileToDel)){
			$dir_iterator = new RecursiveDirectoryIterator($fileToDel, RecursiveDirectoryIterator::SKIP_DOTS);;
			$iterator = new RecursiveIteratorIterator($dir_iterator, RecursiveIteratorIterator::CHILD_FIRST);
			foreach($iterator as $fichier){
				$fichier->isDir() ? rmdir($fichier) : unlink($fichier);
			}
			rmdir($fileToDel);
		}
    }
    
}

?>