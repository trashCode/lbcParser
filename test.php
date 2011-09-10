<?php
require_once './annonceLBC.class.php';
require_once './annonceVoitureLBC.class.php';
require_once './annonceMotoLBC.class.php';
require_once './lbcOcrNumTel.class.php';

$test = array();
// $test[] = new annonceVoitureLBC('http://www.leboncoin.fr/voitures/221357961.htm?ca=12_s');
// $test[] = new annonceVoitureLBC('http://www.leboncoin.fr/voitures/228835183.htm?ca=12_s');
// $test[] = new annonceVoitureLBC('http://www.leboncoin.fr/voitures/211706424.htm?ca=12_s');
// $test[] = new annonceVoitureLBC('http://www.leboncoin.fr/voitures/228563078.htm?ca=12_s');
$test[] = new annonceMotoLBC('http://www.leboncoin.fr/motos/231294022.htm?ca=22_s');
$test[] = new annonceMotoLBC('http://www.leboncoin.fr/motos/197856229.htm?ca=22_s');
$test[] = new annonceMotoLBC('http://www.leboncoin.fr/motos/218889464.htm?ca=22_s');

foreach ($test as $annonce){
	echo "Parsing : ". $annonce->url ."\n";
	$annonce->retrieveSource();
	//$annonce->displaySource();
	if ($annonce->checkEncoreEnLigne()){
		$annonce->parse();
		echo "\tdate\t\t" .date_format($annonce->dateMiseEnLigne,'d/m/Y H:i') ."\n";
		echo "\timages\t\t" .count($annonce->imageUrls) ."\n";
		echo "\ttel\t\t" .$annonce->telephoneContact ."\n";
		echo "\tprix\t\t" .$annonce->prix ."\n";
		echo "\tcode postal\t\t" .$annonce->codePostal ."\n";
		echo "\tannee\t\t" .$annonce->annee ."\n";
		echo "\tcylindree\t\t" .$annonce->cylindree ."\n";

	}else{
		echo "\tAnnonce Desactivée\n";
	}

}
echo "== End of test == \n";
echo '== Mem usage :'. floor(memory_get_usage()/1024) . " Ko \n";
?>