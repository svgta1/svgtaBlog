<?php 
	$menu=$this->data["storage"]->getcall("getMenu");
	$this->getInclude("adminheader");
?>

	<form name="f" method="POST">
	<input type="hidden" name="from" id="from" value="sitemenu" />
	<div id="menu">
<?php
	

	if(isset($_REQUEST["m"])){
		$smenu=$this->data["storage"]->call("getArticlesMenu",$_REQUEST["m"]);
		if(isset($smenu["visible"]))
			$checked=$smenu["visible"];
		else
			$checked=true;
?>
		<label for="name">Nom</label>
		<input type="text" id="name" name="name" value="<?php echo $smenu["name"]; ?>" /><br />
		<label for="position">Position</label>
		<input type="number" id="position" name="position" value="<?php echo $smenu["position"]; ?>" /><br />
		<label for="visible">Visible</label>
		<input type="checkbox" id="visible" name="visible" value="<?php echo $checked; ?>" <?php if($checked) echo "checked"; ?>/><br />

		<input type="submit" id="updatemenu" name="updatemenu" value="Mettre &agrave; jour" />	<input type="submit" id="delmenu" name="delmenu" value="Supprimer le menu" />


<?php		
	}
?>
	</div>
	<div id="sousmenu">
	<h3>Liste des Sous-Menus</h3>
	<ul>

<?php
	if(isset($_REQUEST["m"])){
		if($smenu["subMenu"])
			foreach($smenu["subMenu"] as $v){
				$sM=$this->data["storage"]->call("getArticlesMenu",$v);
				echo '<li><a href="?t=sitemenu&m='.$v.'">'.$sM['name'].'</a></li>';
			}
	}else{
		foreach($menu as $k=>$v){
			echo '<li><a href="?t=sitemenu&m='.$k.'">'.$v['name'].'</a></li>';
		}

	}
?>
	</ul>
	<input type="submit" id="createmenu" name="createmenu" value="Cr&eacute;er un sous-menu" />
	</div>
<?php
	if(isset($_REQUEST["m"])){
?>
	<div id="articles">
		<h3>Liste des articles</h3>
		<ul>
<?php

		$generalConf=$this->data["storage"]->getCall("getGeneralConf");
		if(isset($generalConf['articleTri']) AND ($generalConf['articleTri'] == "DESC"))
			$desc=true;
		else
			$desc=false;

		if(isset($smenu["articles"]) AND $smenu["articles"]){
			if($desc)
				$smenu["articles"]=array_reverse($smenu["articles"]);

			foreach($smenu["articles"] as $v){
				$art=$this->data["storage"]->call("getArticle",$v);
				echo '<li><a href="?t=article&m='.$smenu["uuid"].'&a='.$art['uuid'].'">'.$art['title'] . ' -- (' .$art['uuid'].')</a></li>';
			}
		}
?>

		</ul>

		<input type="submit" id="createarticle" name="createarticle" value="Cr&eacute;er un article" />
	</div>

<?php 
	}
?>
	</form>
<?php
	$this->getInclude("adminfooter");
?>