<?php

	$header=$this->getInclude('header');
	$footer=$this->getInclude('footer');

	require igblog\fonctions::getConfFile("config");

	$content=<<<EOD
		<div>
		Bonjour,<br/><br />
		Un nouveau contact s'est abonné au site. <br /><br />
		</div>

EOD;

	$ret=$header.$content.$footer;
?>
