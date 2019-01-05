<?php
	$conf=$this->data["storage"]->getcall("getGeneralConf");
	$this->getInclude("adminheader"); 
	$contactList=$this->data["storage"]->getcall("getContact");

	$contact=false;
	foreach($contactList as $ar){
		if($ar['uuid'] == $_REQUEST["r"])
			$contact=$ar;
	}

	if(!$contact)
		header('Location: ?t=contact');

	if(isset($_POST) AND isset($_POST["subContact"])){
		$ar=array(
			"sn"=>$_POST["sn"],
			"givenname"=>$_POST["givenname"],
			"mail"=>$_POST["mail"],
			"uuid"=>$_POST["uuid"],
		);
		$this->data["storage"]->call("updateContact",$ar);
		header('Location: ?t=contact');
	}

	if(isset($_POST) AND isset($_POST["delContact"])){
		$this->data["storage"]->call("delContact",$_POST["uuid"]);
		header('Location: ?t=contact');
	}

?>

	<form name="f" method="POST">
		<input type="text" id="sn" name="sn" placeholder="Nom" value="<?php echo $contact["sn"]; ?>" required/></br>
		<input type="text" id="givenname" name="givenname" placeholder="Prénom" value="<?php echo $contact["givenname"]; ?>" required/></br>
		<input type="email" id="mail" name="mail" placeholder="Email" value="<?php echo $contact["mail"]; ?>" required/></br>
		<input type="hidden" id="uuid" name="uuid" value="<?php echo $contact["uuid"]; ?>" /></br>
		<input type="submit" id="subContact" name="subContact" value="Mettre à jour">
		<input type="submit" id="delContact" name="delContact" value="Supprimer" class="del">
	</form>



<?php 
	$this->getInclude("adminfooter");
?>