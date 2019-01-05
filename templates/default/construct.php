<html>
<html lang="fr-FR">
<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width" />

	<?php
		$components=$this->getComponentsFromConfig();
		if($components)
		foreach($components as $c){
			echo $c;
		}
	?>
</head>
<body>
	<h1>Site en cours de construction</h1>
	<p>
		Veuillez vous connecter &agrave; l'administration pour cr&eacute;er votre premier menu : <a href="admin/">administration</a>
	</p>
</body>
</html>