<?php
	class annonceVoitureLBC extends annonceLBC {
		protected $prix;
		protected $codePostal;
		protected $ville;
		protected $annee;
		protected $km;
		protected $carburant;
		protected $typeBoite;
		
		// public function __construct($url){
			// parent::__construct($url);
		// }
		
		public function __get($attribut){
			switch($attribut){
				case 'prix' : return $this->prix;
				case 'ville' : return $this->ville;
				case 'codePostal' : return $this->codePostal;
				case 'annee' : return $this->annee;
				case 'km' : return $this->km;
				case 'carburant' : return $this->carburant;
				case 'typeBoite' : return $this->typeBoite;
				default: return parent::__get($attribut);
			}
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
			
			//energie
			$matches = array();
			if ( preg_match('/<label>Carburant : <\/label>\s*<strong>(.*?)</',$this->htmlSource,$matches)){
			// if ( preg_match('/<label>K.{50}/',$this->htmlSource,$matches)){
				$this->carburant = trim($matches[1]);
			}
			
			//boite vitesse
			$matches = array();
			 if ( preg_match('/<label>Boîte de vitesse : <\/label>\s*<strong>(.*?)</',$this->htmlSource,$matches)){
				$this->carburant = trim($matches[1]);
			}
			
		}
		
	}
?>