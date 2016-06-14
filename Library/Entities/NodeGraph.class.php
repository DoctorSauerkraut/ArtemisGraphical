<?php
	class nodeGraph{
		private $id;
		private $posX=0;
		private $posY=0;
		private $shape;
		private $nbLinks;
		private $parent;
		private $rank;
		private $taille=0;

		public function __construct(){
		}

		public function id(){
			return $this->id;
		}
		public function posX(){
			return $this->posX;
		}
		public function posY(){
			return $this->posY;
		}
		public function shape(){
			return $this->shape;
		}
		public function nbLinks(){
			return $this->nbLinks;
		}
		public function parent(){
			return $this->parent;
		}
		public function rank(){
			return $this->rank;
		}
		public function taille(){
			return $this->taille;
		}

		//mutateur
		public function setId($id){
			$this->id=$id;
		}
		public function setPosX($posX){
			$this->posX=$posX;
		}
		public function setPosy($posY){
			$this->posY=$posY;
		}
		public function setShape($shape){
			$this->shape=$shape;
		}
		public function setNbLinks($nbLinks){
			$this->nbLinks=$nbLinks;
		}
		public function setParent($parent){
			$this->parent=$parent;
		}
		public function setRank($rank){
			$this->rank=$rank;
		}
		public function setTaille($taille){
			$this->taille=$taille;
		}

	}


?>