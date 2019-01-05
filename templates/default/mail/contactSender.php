<?php

	$header=$this->getInclude('header');
	$footer=$this->getInclude('footer');

	$username=$this->data["mailInfo"]['givenname'].' '.$this->data["mailInfo"]['sn'];
	$subject=$this->data["mailInfo"]['subject'];
	$text=$this->data["mailInfo"]['text'];

	$content=<<<EOD
		<div>
		Bonjour $username,<br/><br />
		Merci de nous avoir contacté. Nous vous répondrons dès que possible.<br /><br />
		Le contenu de votre message est le suivant :<br />
		</div>
		<div class="messageContent">
			<b>Sujet</b> : $subject <br/><br />
			<b>Contenu</b> : <br />
			$text
		</div>
		<div>
			<br />
			Bien cordialement.
		</div>

EOD;

	$ret=$header.$content.$footer;
?>