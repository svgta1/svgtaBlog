<?php
	$conf=$this->data["storage"]->getCall("getGeneralConf");
	$this->getInclude("adminheader");

	if(isset($_POST) AND isset($_POST["subContact"])){
		$ar=array(
			"sn"=>$_POST["sn"],
			"givenname"=>$_POST["givenname"],
			"mail"=>$_POST["mail"],
		);
		$conf=$this->data["storage"]->call("setContact",$ar);
		header('Location: ?t=contact');
	}


?>
	<form name="f" method="POST">
		<input type="text" id="sn" name="sn" placeholder="Nom" value="" required/></br>
		<input type="text" id="givenname" name="givenname" placeholder="Prénom" value="" required/></br>
		<input type="email" id="mail" name="mail" placeholder="Email" value="" required/></br>
		<input type="submit" id="subContact" name="subContact" value="Valider">
	</form>

<?php 
	$this->getInclude("adminfooter");
?>