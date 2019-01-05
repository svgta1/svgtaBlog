<?php

	$config=array(
		'smtp'=>array(
			'enable'=>true,
			'host'=>'mailhost',
			'port'=>587,
			'tls'=>true,
			'ssl'=>false,
			'username'=>'username',
			'password'=>'password',
		),
		'from'=>'defaultMailReply@domain.tld',
		'fromName'=>'Blog Name',
		'subjectSender'=>'Subject for the user contact',
		'subjectAdmin'=>'Subject for the admin ',
		'debug'=>false,
	);

?>
