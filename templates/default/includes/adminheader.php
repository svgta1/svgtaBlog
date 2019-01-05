<html lang="fr-FR" dir="ltr" >
<head>
	<meta charset="UTF-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=Edge">
	  <meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="../resources/default/css/admin.css" media="screen">
	<script type="text/javascript" src="../resources/default/js/tinymce/js/tinymce/tinymce.min.js"></script>

</head>
<body>
	<div id="header">
		<h1>Administration</h1>
	</div>
	<div class="navigate">
	<ul>
<?php
	$aff='';
	foreach($this->data['nav'] as $k=>$ar){
		$aff .= '<li>';
		$aff .= '<a href="?t='.$ar["target"].'">'.$ar["libelle"].'</a>';
		if($ar["target"] == "sitemenu"){
			$aff .= \igblog\fonctions::getMenu("sitemenu",$this->data['menu']);
		}
		$aff .= '</li>';
	}

	echo $aff;
?>
	<li><a href="../">Sortir</a></li>
	</ul>
	</div>
	<div id="wrap">

<?php
	if(isset($this->data["target"]) AND isset($this->data["nav"][$this->data["target"]]["libelle"])){
		echo '<h2>'.$this->data["nav"][$this->data["target"]]["libelle"].'</h2>';
	}
?>