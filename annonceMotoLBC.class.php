<?php
	class annonceMotoLBC extends annonceLBC {
		protected $prix;
		protected $codePostal;
		protected $ville;
		protected $cylindree;
		protected $annee;
		protected $km;
		protected $modele; //a renseigner pour faire des regroupement. Se baser sur le titre, meme s'il est trompeur.
		protected $disabled = false; //les annonce qu'on ne souhaites plus voir.
		
		
		public function __get($attribut){
			switch($attribut){
				case 'prix' : return $this->prix;
				case 'codePostal' : return $this->codePostal;
				case 'ville' : return $this->ville;
				case 'annee' : return $this->annee;
				case 'km' : return $this->km;
				case 'cylindree' : return $this->cylindree;
				case 'modele' : return $this->modele;
				case 'disabled' : return $this->disabled;
				default: return parent::__get($attribut);
			}
		}
		
		public function setModele($modele){
			$this->modele = $modele;
		}
		
		public function disable(){
			$this->disabled = true;
		}
		
		public function enable(){
			$this->disabled = false;
		}
		
		public function parse(){
			parent::parse();
			
			//prix
			$matches = array();
			if ( preg_match('/<label>Prix : <\/label>\s*<strong>(.*?)&/',$this->htmlSource,$matches)){
				$this->prix = (int)(str_replace(' ','',$matches[1]));
			}
			
			//ville et codePostal
			$matches = array();
			if ( preg_match('/<label>Ville : <\/label>\s*<strong>(\d+)(.*?)</',$this->htmlSource,$matches)){
				$this->codePostal = (int)$matches[1];
				$this->ville = trim($matches[2]);
			}
			
			//codePostal seulement
			$matches = array();
			if ( preg_match('/<label>Code postal : <\/label>\s*<strong>(\d+)/',$this->htmlSource,$matches)){
				$this->codePostal = (int)$matches[1];
			}
			
			//annee
			$matches = array();
			if ( preg_match('/<label>Ann&eacute;e-mod&egrave;le : <\/label>\s*<strong>(\d+)/',$this->htmlSource,$matches)){
				$this->annee = (int)$matches[1];
			}
			
			//km
			$matches = array();
			if ( preg_match('/<label>Kilom&eacute;trage : <\/label>\s*<strong>([\d,\s]+)/',$this->htmlSource,$matches)){
			// if ( preg_match('/<label>K.{50}/',$this->htmlSource,$matches)){
				$this->km = (int)(str_replace(' ','',$matches[1]));
			}
	
			//prix
			$matches = array();
			if ( preg_match('/<label>Cylindrée : <\/label>\s*<strong>([\d,\s]+)/',$this->htmlSource,$matches)){
			// if ( preg_match('/<label>Cylindrée : <\/label>\s*<strong>.{50}/',$this->htmlSource,$matches)){
				var_dump($matches);
				$this->cylindree = (int)(str_replace(' ','',$matches[1]));
			}
			
		}
		
	
	}
?>