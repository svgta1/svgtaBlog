<?php
	$sessBlog=$_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].'/'.$_SERVER['REQUEST_URI'];
?>
	<div style="width:100%; text-align:left;padding:30px;">
	<h1>Vous devez vous connecter à notre système afin de pouvoir nous contacter.</h1>
	<p>
		Bonjour,<br />
		Nous demandons une connexion à notre système afin de pouvoir nous contacter.<br />
		Ceci nous permet de nous protéger contre des attaques extérieures.<br /><br />
		Nous vous remercions pour votre compréhension.
		Bien cordialement.
	</p>
	<form name="f" method="GET" action="secure/">
		<input type="hidden" id="_SessBlog" name="_SessBlog" value="<?php echo $sessBlog; ?>"/></br>
		<input type="submit" id="sub" name="sub" value="J'accèpte la connexion">
	</form>
	</div>


