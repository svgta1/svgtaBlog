<?php
	if($_POST and $_POST['submit']){
		$ar=array(
			"siteName"=>htmlentities($_POST["sitename"]),
			"desc"=>htmlentities($_POST["sitedesc"]),
			"articleTri"=>htmlentities($_POST["articletri"]),
			"contact"=>array(
				'actif'=>isset($_POST['contact_actif']) ? true : false,
				'restriction'=>isset($_POST['contact_restriction']) ? true : false,
				'connect'=>isset($_POST['contact_connect']) ? true : false,
			),
			"com"=>array(
				'actif'=>isset($_POST['com_actif']) ? true : false,
				'restriction'=>isset($_POST['com_restriction']) ? true : false,
				'connect'=>isset($_POST['com_connect']) ? true : false,
				'vvalid'=>isset($_POST['com_vvalid']) ? true : false,
				'vrestriction'=>isset($_POST['com_vrestriction']) ? true : false,
				'vconnect'=>isset($_POST['com_vconnect']) ? true : false,

			),

		);

		if($ar['contact']['restriction'])
			$ar['contact']['connect']=true;
		if($ar['com']['restriction'])
			$ar['com']['connect']=true;
		if($ar['com']['vrestriction'])
			$ar['com']['vconnect']=true;



		$this->data["storage"]->call("setGeneralConf",$ar);

	}
	$conf=$this->data["storage"]->getcall("getGeneralConf");
	$this->getInclude("adminheader"); 

	$checked=array(
		"contact.actif"=>isset($conf['contact']["actif"]) ? $conf['contact']["actif"] : false,
		"contact.restriction"=>isset($conf['contact']["restriction"]) ? $conf['contact']["restriction"] : false,
		"contact.connect"=>isset($conf['contact']["connect"]) ? $conf['contact']["connect"] : false,
		"com.actif"=>isset($conf['com']["actif"]) ? $conf['com']["actif"] : false,
		"com.restriction"=>isset($conf['com']["restriction"]) ? $conf['com']["restriction"] : false,
		"com.connect"=>isset($conf['com']["connect"]) ? $conf['com']["connect"] : false,

		"com.vvalid"=>isset($conf['com']["vvalid"]) ? $conf['com']["vvalid"] : false,
		"com.vrestriction"=>isset($conf['com']["vrestriction"]) ? $conf['com']["vrestriction"] : false,
		"com.vconnect"=>isset($conf['com']["vconnect"]) ? $conf['com']["vconnect"] : false,


	);


?>
	<div class="general">
	<form name="f" method="POST">
		<h3>Configuration site</h3>
		<label for="sitename">Nom du site</lable>
		<input type="text" name="sitename" id="sitename" value="<?php echo $conf["siteName"]; ?>" /><br />
		<label for="sitedesc">Petite description</lable>
		<input type="text" name="sitedesc" id="sitedesc" value="<?php echo $conf["desc"]; ?>" /><br />
		Ordre de tri des articles : 
		<select name="articletri" name="articletri">
  			<option value="DESC" <?php if($conf["articleTri"] == "DESC") echo "selected";?>>Descendant</option> 
  			<option value="ASC" <?php if($conf["articleTri"] == "ASC") echo "selected";?>>Ascendant</option>
		</select><br />
		<input type="hidden" name="from" id="from" value="generalconf" />
		<hr />
		<h3>Formulaire de contact</h3>

		<input type="checkbox" id="contact.actif" name="contact.actif" value="<?php echo $checked["contact.actif"]; ?>" <?php if($checked["contact.actif"]) echo "checked"; ?>/>
		<label for="contact.actif">Activer</label><br />
		<input type="checkbox" id="contact.connect" name="contact.connect" value="<?php echo $checked["contact.connect"]; ?>" <?php if($checked["contact.connect"]) echo "checked"; ?>/>
		<label for="contact.connect">Obliger une connexion</label><br />
		<input type="checkbox" id="contact.restriction" name="contact.restriction" value="<?php echo $checked["contact.restriction"]; ?>" <?php if($checked["contact.restriction"]) echo "checked"; ?>/>
		<label for="contact.restriction">Limiter aux comptes abonnés</label><br />

		
		<hr />
		<h3>Commentaires sur articles</h3>
		<h4>Accès</h4>
		<input type="checkbox" id="com.actif" name="com.actif" value="<?php echo $checked["com.actif"]; ?>" <?php if($checked["com.actif"]) echo "checked"; ?>/>
		<label for="com.actif">Activer</label><br />
		<input type="checkbox" id="com.connect" name="com.connect" value="<?php echo $checked["com.connect"]; ?>" <?php if($checked["com.connect"]) echo "checked"; ?>/>
		<label for="com.connect">Obliger une connexion</label><br />

		<input type="checkbox" id="com.restriction" name="com.restriction" value="<?php echo $checked["com.restriction"]; ?>" <?php if($checked["com.restriction"]) echo "checked"; ?>/>
		<label for="com.restriction">Limiter aux comptes abonnés</label><br />
		<h4>Visibilité</h4>
		<input type="checkbox" id="com.vvalid" name="com.vvalid" value="<?php echo $checked["com.vvalid"]; ?>" <?php if($checked["com.vvalid"]) echo "checked"; ?>/>
		<label for="com.vvalid">Valider les commentaires avant diffusion</label><br />
		<input type="checkbox" id="com.vconnect" name="com.vconnect" value="<?php echo $checked["com.vconnect"]; ?>" <?php if($checked["com.vconnect"]) echo "checked"; ?>/>
		<label for="com.vconnect">Obliger une connexion</label><br />

		<input type="checkbox" id="com.vrestriction" name="com.vrestriction" value="<?php echo $checked["com.vrestriction"]; ?>" <?php if($checked["com.vrestriction"]) echo "checked"; ?>/>
		<label for="com.vrestriction">Limiter aux comptes abonnés</label><br />


		<input type="submit" name="submit" id="submit" value="Valider" /><br />


	</form>
	</div>
	<div class="general">
		<h3>Aide</h3>
			<h4>Configuration site</h4>
			<ul>
				<li><b>Nom du site</b> : nom qui s'affiche en haut du site</li>
				<li><b>Petite description</b> : description qui s'affiche en haut du site</li>
				<li><b>Ordre de tri des articles</b> : choix de l'ordre de tri des articles suivant la date de création.</li>
			</ul>
			<h4>Formulaire de contact</h4>
			<ul>
				<li><b>Activer</b> : Active le formulaire pour contacter l'administrateur.</li>
				<li><b>Limiter aux comptes abonnés</b> : seuls les comptes listés (adresse email) sont autorisées à contacter l'administrateur.<br />Ceci implique une connexion obligatoire pour accéder au formulaire.</li>
				<li><b>Obliger une connexion</b> : tout le monde peut contacter l'administrateur, mais doit d'abord se connecter. cette option est recommandée pour éviter les spams.</li>
			</ul>
			<h4>Commentaires sur articles</h4>
			<h5>Accès</h5>
			<ul>
				<li><b>Activer</b> : Active le formulaire des commenaitres sur les articles.</li>
				<li><b>Limiter aux comptes abonnés</b> : seuls les comptes listés (adresse email) sont autorisées à publier un commentaire.<br />Ceci implique une connexion obligatoire pour accéder au formulaire.</li>
				<li><b>Obliger une connexion</b> : tout le monde publier un commentaire, mais doit d'abord se connecter. cette option est recommandée pour éviter les spams.</li>
			</ul>
			<h5>visibilité</h5>
			<ul>
				<li><b>Valider</b> : Validation des commentaires par l'administrateur avant diffusion.</li>
				<li><b>Limiter aux comptes abonnés</b> : seuls les comptes listés (adresse email) sont autorisées à voir les commentaires.<br />Ceci implique une connexion obligatoire pour accéder au formulaire.</li>
				<li><b>Obliger une connexion</b> : tout le monde peut voir les commentaires, mais doit d'abord se connecter.</li>
			</ul>



	<div>

<?php 
	$this->getInclude("adminfooter");
?>