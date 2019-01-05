<?php

	$header=$this->getInclude('header');
	$footer=$this->getInclude('footer');
	$username=$this->data["mailInfo"]['givenname'].' '.$this->data["mailInfo"]['sn'];
	$from=$this->data["mailInfo"]['from'];

	require igblog\fonctions::getConfFile("config");
	$lien=$_SERVER["REQUEST_SCHEME"]."://".$_SERVER["HTTP_HOST"].'/'.$config['resourcesDir'].'/'.$this->data["mailInfo"]['lien'];

	$content=<<<EOD
		<div>
		Bonjour,<br/><br />
		Un commentaire de <b>$from</b> a été ajouté. <br /><br />
		</div>
		<div class="messageContent">
EOD;

	$content .=$this->data["mailInfo"]['text'].'</div>';


	$ret=$header.$content.$footer;
?>
