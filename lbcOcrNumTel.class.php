<?php
class lbcOcrNumTEl {
	private $url;
	private $img;
	private $numeroLu;
	private $numeroCrypted;
	static private $knownHashes = array (
		"9f8f0ec53a4f17e47d2aaf9f281edb31d1eeb829c07b58803179997c271fbe3d" => '0',
		"72e2a5ffa97fc7191f4151d3d73f180cafe2a6614b2c81c0cb3738d72d63128b" => '1',
		"87ee7f16a4b93937289bb534ecf8a48f01100c9434b41278df57bbea6349ff14" => '2',
		"9b3d4d0985830ba97e97d91d04fbf8214a064104d8f1a6b11fc1ddfa9943e010" => '3',
		"fe190ff3c0271cc6da93f3cc7ee4be7e29466ede6ed7653899dd1d72d74716ab" => '4',
		"697a9781246532d5249013590e058d8cd71c86bf36534491367faa7659ee9745" => '5',
		"01fd82ce3638469d5b2f503e937ac154ee449084355cf58c30e87de5a9c5b96a" => '6',
		"1858cd2cc2a304bd76dc891fabcdc24fe0af70caebc67803186c2430f6287607" => '7',
		"b67e91e22aa82b3ff12bee229f2b9ec3908e3d3f9a493ce41a969a69eaf89cb3" => '8',
		"d5e8a406938d6c4bd6ffe45c618867dbecd7848ba0aff40bfda05c817639a32a" => '9'
		//"afedbdafe62a8d59abc6ef1867e5e78881503de7906a828abc2cb27d4b9dc83b" => 'blank'
	);
	static private $blankHash = "afedbdafe62a8d59abc6ef1867e5e78881503de7906a828abc2cb27d4b9dc83b";
	
	public function __construct($url){
		$this->url = $url;
	}
	
	public function getUrl(){
		return $this->url;
	}

	public function display(){
		header("Content-Type: image/png");
		echo $this->$img;
	}
	
	public function load (){
		$tmp = file_get_contents($this->url);
		$im = new Imagick();
		$im->readImageBlob($tmp);
		$this->img = $im;
	}
	
	public function lire(){
		
		if (isset($this->numeroLu)){ return $this->numeroLu; }
		
		if (!isset($this->img)) {$this->load();}
		
		$geo = $this->img->getimageGeometry();
		$hashes = array();
		
		for ($i=0; $i<$geo["width"]-1 ; $i++){
			$case = $this->img->clone();
			$case->cropImage(1,$geo['heigth'],$i,0);
			$hashes[] = $case->getImageSignature();
		}
		
		$filteredHashes = array_intersect($hashes,array_flip(self::$knownHashes));
		$numero='';
		foreach ($filteredHashes as $k => $val){
			$numero = $numero.self::$knownHashes[$val];
		}
		
		$this->numeroLu = $numero;
		return $numero;
	}
}
?>
