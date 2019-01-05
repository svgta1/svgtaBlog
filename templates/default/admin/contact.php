<?php
	$conf=$this->data["storage"]->getcall("getGeneralConf");
	$this->getInclude("adminheader"); 

	if(!isset($conf['contact']))
		$checked=array(
			"actif"=>false,
			"restriction"=>false,
			"connect"=>false,
		);
	else
		$checked=array(
			"actif"=>isset($conf['contact']["actif"]) ? $conf['contact']["actif"] : false,
			"restriction"=>isset($conf['contact']["restriction"]) ? $conf['contact']["restriction"] : false,
			"connect"=>isset($conf['contact']["connect"]) ? $conf['contact']["connect"] : false,
		);

?>
	<form name="f" method="POST">
		<input type="hidden" name="from" id="from" value="contact" />
		<div style="background-color:rgba(0,0,0,0.1); display:block;">

			<h3>Liste des comptes connus</h3>
<?php
	$contact=$this->data["storage"]->getcall("getContact");

	if($contact){
		echo '<ul>';
		foreach($contact as $c){
			echo "<li><a href=\"?t=contactInfo&r=".$c["uuid"]."\">".$c["sn"]." ".$c["givenname"]." (".$c["mail"].")</a></li>";
		}
		echo '</ul>';
	}

?>
		<input type="submit" name="adcontact" id="adcontact" value="Ajouter un compte" />
		</div>


	</form>
<?php 
	$this->getInclude("adminfooter");
?>