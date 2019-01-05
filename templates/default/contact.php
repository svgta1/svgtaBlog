<?php 

	$this->getInclude("header");
	if($_POST AND isset($_POST['subContact'])){
		$ar=array(
			"sn"=>isset($this->data['user']['sn']) ? $this->data['user']['sn'] : htmlspecialchars($_POST['sn']),
			"givenname"=>isset($this->data['user']['givenName']) ? $this->data['user']['givenName'] : htmlspecialchars($_POST['givenName']),
			"mail"=>isset($this->data['user']['mail']) ? $this->data['user']['mail'] : htmlspecialchars($_POST['mail']),
			"subject"=>htmlspecialchars($_POST['subject']),
			"text"=>nl2br(Html2Text\Html2Text::convert(nl2br($_POST['text']))),
		);

		try{
			$mail=new \igblog\mail\mail($ar);
			$mail->send();
		}catch (\Exception $e){
			echo $e->getMessage();
			die();
		}
	
		header('Location: ?t=userrep'); 
		exit();
	}

	if($_POST AND isset($_POST['subAbon'])){
		$ar=array(
			"sn"=>$_POST["abosn"],
			"givenname"=>$_POST["abogivename"],
			"mail"=>$_POST["abomail"],
		);
		$conf=$this->data["storage"]->call("setContact",$ar);

		$ar=array(
			"sn"=>$ar['sn'],
			"givenname"=>$ar['givenname'],
			"mail"=>$ar['mail'],
		);

		try{
			$mail=new \igblog\mail\mail($ar, 'newContact');
			$mail->sendAdmin();
		}catch (\Exception $e){
			echo $e->getMessage();
			echo '<pre>';
			print_r($e);
			echo '</pre>';
			die();
		}



		header('Location: ?t=userabon'); 
		exit();
		
	}

	if(!isset($this->data['globalConf']["contact"]) OR !isset($this->data['globalConf']["contact"]['actif']) OR !$this->data['globalConf']["contact"]['actif'])
		throw new \Exception("Non autorisé");

	$ar=array(
		"sn"=>'',
		"givenname"=>'',
		"mail"=>'',
	);
	$disable=false;

	if($this->data['user']){
		$ar=array(
			"sn"=>isset($this->data['user']['sn']) ? $this->data['user']['sn'] : '',
			"givenname"=>isset($this->data['user']['givenName']) ? $this->data['user']['givenName'] : '',
			"mail"=>isset($this->data['user']['mail']) ? $this->data['user']['mail'] : '',
		);
		$disable=true;
	}



?>
	<div id="contactForm">
<?php
	$contactListe=$this->data["storage"]->getCall("getContactMail");
	if(!in_array($ar['mail'], $contactListe)){

?>
	<div>
		<h1>S'abonner aux actualités</h1>
		<form name="f" method="POST">
		<input type="text" id="abosn" name="abosn" placeholder="Votre nom" value="<?php echo $ar["sn"];?>" <?php if($disable) echo 'readonly'; ?> required/></br>
		<input type="text" id="abogivenanme" name="abogivename" placeholder="Votre prénom" value="<?php echo $ar["givenname"];?>" <?php if($disable) echo 'readonly'; ?> required/></br>
		<input type="email" id="abomail" name="abomail" placeholder="Votre email" value="<?php echo $ar["mail"];?>" <?php if($disable) echo 'readonly'; ?> required/></br>
		<input type="submit" id="subAbon" name="subAbon" value="S'abonner">
		</form>
		<hr />
	</div>


<?php
	}

	$secure=false;
	if((isset($this->data['globalConf']["contact"]['connect']) AND $this->data['globalConf']["contact"]['connect']))
		$secure=true;
	$restriction=false;
	if((isset($this->data['globalConf']["contact"]['restriction']) AND $this->data['globalConf']["contact"]['restriction']))
		$restriction=true;
	if($secure){
		if(!$this->data['user']){
			require dirname(__FILE__).'/includes/secure.php';		
		}else{
			if($restriction){
				$mailList=$this->data["storage"]->getCall("getContactMail");
				if(igblog\fonctions::isAdmin($this->data['user']['mail']))
					array_push($mailList,$this->data['user']['mail']);
				if(in_array($this->data['user']['mail'],$mailList)){
					require dirname(__FILE__).'/includes/contactForm.php';
				}else{
					header("location: ?t=notAutorized");
				}
			}else{
				require dirname(__FILE__).'/includes/contactForm.php';
			}
		}			
	}else{
		require dirname(__FILE__).'/includes/contactForm.php';
	}
		
?>
	</div> <!-- content -->

<?php $this->getInclude("footer"); ?>
