<?php

	$header=$this->getInclude('header');
	$footer=$this->getInclude('footer');
	$username=$this->data["mailInfo"]['givenname'].' '.$this->data["mailInfo"]['sn'];
	$mail=$this->data["mailInfo"]['mail'];

	$content=<<<EOD
	<div>
	<i>Vous avez reçu un mail de $username ($mail). Vous pouvez y répondre directement en choisissant "Répondre" avec votre logiciel mail.</i><br />
	Contenu du message :<br /><br />
	</div>
	<div class="messageContent">
EOD;
	$content .=$this->data["mailInfo"]['text'].'</div>';

	$ret=$header.$content.$footer;
?>