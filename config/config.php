<?php
	$config=array(
		"components" => array(
			"css"=>array(
				"default",
			),
			"js"=>array(
				"prototype",
				"default",
			),
		),
		"resourcesDir"=>"svgtaBlog/www",
		"theme"=>"default",
		"contact"=>array(
			"contact@domain.tld",
		),
		"storage"=>array(
			"\\igblog\\storage\\file",
		),
		"auth"=>array( //to block acces of the site, for only mail listed her
			"auth1@domain1.tld",
			"auth2@domain2.tld",
		),
	);

?>