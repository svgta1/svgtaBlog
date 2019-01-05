<?php

	$header=$this->getInclude('header');
	$footer=$this->getInclude('footer');
	$username=$this->data["mailInfo"]['givenname'].' '.$this->data["mailInfo"]['sn'];

	require igblog\fonctions::getConfFile("config");
	$lien=$_SERVER["REQUEST_SCHEME"]."://".$_SERVER["HTTP_HOST"].'/'.$config['resourcesDir'].'/'.$this->data["mailInfo"]['lien'];

	$content=<<<EOD
		<div>
		Bonjour $username,<br/><br />
		Un article a été ajouté ou mis à jour. Vous le trouverez en suivant ce lien : <a href="$lien">$lien</a><br /><br />
		</div>
		<div>
			<br />
			Bien cordialement.
		</div>

EOD;


	$ret=$header.$content.$footer;
?>
