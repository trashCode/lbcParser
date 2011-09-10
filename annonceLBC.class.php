<?php
class annonceLBC {
	protected $url;
	protected $htmlSource;
	protected $imageUrls; // un tableau
	protected $md5ImagePrincipale;
	protected $dateMiseEnLigne;
	protected $dateParsed;
	protected $auteur;
	protected $mailContact;
	protected $telephoneContact;
	protected $categorie;
	protected $lbcId;
	
	public function __construct($url){
		$this->url = $url;

		$matches = array();
		$tmp = preg_match('/http:\/\/www.leboncoin.fr\/([a-z,A-Z]+)\/(\d+)\..*/',$url,$matches);
		if ($tmp){
			$this->categorie = $matches[1];
			$this->lbcId = $matches[2];
		}
		
	}	
	
	public function __get($attribut){
		switch($attribut){
			case 'url' : return $this->url;
			case 'imageUrls' : return $this->imageUrls;
			case 'md5ImagePrincipale' : return $this->md5ImagePrincipale;
			case 'dateMiseEnLigne' : return $this->dateMiseEnLigne;
			case 'dateParsed' : return $this->dateParsed;
			case 'auteur' : return $this->auteur;
			case 'mailContact' : return $this->mailContact;
			case 'telephoneContact' : return $this->telephoneContact;
			case 'categorie' : return $this->categorie;
			case 'lbcId' : return $this->lbcId;
		}
	}
	
	public function checkEncoreEnLigne(){
		//todo: verifier que l'annonce est toujours en ligne.
		$matches = array();
		$tmp = preg_match('/Cette annonce est d&eacute;sactiv&eacute;e/',$this->htmlSource,$matches);
		return !$tmp;
	}

	public function parse(){
		if (!isset ($this->htmlSource)){$this->retrieveSource();}
		
		//recuperation auteur  et date de mise en ligne.
		$matches = array();
		$tmp = preg_match('/Mise en ligne par\s*<a.*?>\s*(.*?)\s*<\/a>\sle\s(\d+)\s(.*?)\s&agrave; (\d+):(\d+)............/',$this->htmlSource,$matches);

		if($tmp){
			$this->auteur = $matches[1];
			$jour = (int)$matches[2];
			$mois = $this->mois2dec($matches[3]);	
			$annee = $this->guessYearFromMonth($mois);
			$heure = (int)$matches[4];
			$minutes = (int)$matches[5];
	
			$this->dateMiseEnLigne = new dateTime((string)$annee. '-' .(string)$mois. '-' .(string)$jour. ' ' .(string)$heure. ':' .(string)$minutes);
		}
		
		//on viens de la parser !
		$this->dateParsed = new dateTime('now');
		
		//images 
		
		$tmp = preg_match_all('/showLargeImage\(\'(.*?)\'/',$this->htmlSource,$matches);
		if ($tmp) {
			foreach ($matches[1] as $imgUrl){
				$this->imageUrls[] = $imgUrl;
			}
		}else{ //il n'y a pas plusieures images, mais peu etre une seule ! 
			$tmp = preg_match('/([^"]*)" id="main_image"/',$this->htmlSource,$matches);

			if ($tmp > 0){
				$this->imageUrls[] = $matches[1];
			}
		}

		// telephone 
		if (preg_match('/<img alt="#" src="(.*?)" class="AdPhonenum"/',$this->htmlSource,$matches)){
			$urlTelephone = $matches[1];
			$tmp = new lbcOcrNumTEl($urlTelephone);
			$this->telephoneContact = $tmp->lire();
		}
		
	}
	
	public function retrieveSource(){
		$curl = curl_init($this->url);
		curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);
		$this->htmlSource = utf8_encode(curl_exec($curl));
		$this->htmlSource = preg_replace('/(\r|\n|\r\n)/','',$this->htmlSource);//on supprime les retours a la ligne pour mieux parsé les donnée reparties sur plusieures lignes.
		curl_close($curl);
	}

	public function displaySource(){
		echo $this->htmlSource;
	}
	
	public function mois2dec($mois){
		switch($mois){
			case 'janvier': return 1;
			case 'février': return 2;
			case 'mars'   : return 3;
			case 'avril'  : return 4;
			case 'mai'    : return 5;
			case 'juin'   : return 6;
			case 'juillet': return 7;
			case 'août'   : return 8;
			case 'septembre' : return 9;
			case 'octobre': return 10;
			case 'novembre':return 11;
			case 'décembre':return 12;
		}
	}

	private function guessYearFromMonth($mois){
		//Si le mois est superieur au mois courrant, c'est que l'annonce date de l'an dernier (ou vient du futur ?)
		$now = new dateTime();
		$moisCourrant = (int)($now->format('m'));
		if ($mois > $moisCourrant){
			return (int)($now->format('Y')) - 1;
		}else{
			return (int)($now->format('Y'));
		} 
	}	
}
?>
