<html lang="fr" dir="ltr" >
<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=Edge">
	<meta name="viewport" content="width=device-width" />

	<link rel='stylesheet' id='passenger-damion-css'  href='https://fonts.googleapis.com/css?family=Open+Sans%3A400italic%2C600italic%2C700italic%2C400%2C600%2C700%7CAllura&#038;ver=4.2.21' type='text/css' media='all' />
	<?php
		$components=$this->getComponentsFromConfig();
		if($components)
		foreach($components as $c){
			echo $c;
		}
	?>
</head>
<body>
	<div id="top" id="top">
	<div id="header" onclick="window.location='<?php echo str_replace('index.php','',$_SERVER['SCRIPT_NAME']); ?>';  ">
		<h1 class="site_title"><?php echo $this->data["siteName"];?></h1>
		<p><?php echo $this->data["siteDesc"];?></p>
	</div>
	<div class="navigate">
<?php
	
	echo igblog\fonctions::getMenuBlog("articles",$this->data['menu']);


?>
	</div>
<?php
	if(isset($_REQUEST['m'])){
		if(isset($this->data['menu'][$_REQUEST['m']]) AND isset($this->data['menu'][$_REQUEST['m']]['subMenu']) AND $this->data['menu'][$_REQUEST['m']]['subMenu']){
		$sMenu=$this->data['menu'][$_REQUEST['m']]['subMenu'];
		echo '<div class="subMenu"><ul>';
		foreach($sMenu as $s){
			if($s['visible'])
				echo '<li><a href="?m='.$s['uuid'].'">'.$s['name'].'</a></li>';
		}
		echo '</ul></div>';

		}
	}


?>
	</div>
	<div id="wrap">