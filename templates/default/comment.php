<?php
	$art=$_REQUEST['a'];
	$article=$this->data["storage"]->getCall("getArticle",$art);

	if(isset($_POST['subComment'])){
		$confFile=$this->data["storage"]->getCall("getGeneralConf");
		$commentAr=array(
			"sn"=>strip_tags($_POST['sn']),
			"givenName"=>strip_tags($_POST['givenName']),
			"mail"=>strip_tags($_POST['mail']),
			"msg"=>nl2br(Html2Text\Html2Text::convert(nl2br($_POST['text']))),
			"uuid"=>igblog\fonctions::genUUID(),
			"valide"=>$confFile["com"]["vvalid"] ? false : true,
			"date"=>time(),
		);
		try{
			$this->data["storage"]->call("setComment",$art,$commentAr);
			header('location: ?m='.$_REQUEST['m']);
		}catch(\Exception $e){
			echo '<pre>';
			print_r($e);
			echo '</pre>';
			die();
		}

		if(!$confFile["com"]["vvalid"]){
			$listAbo=$this->data["storage"]->getCall("getContact");
			foreach($listAbo as $abo){
				try{
					$ar1=array(
						"sn"=>$abo['sn'],
						"givenname"=>$abo['givenName'],
						"mail"=>$abo['mail'],
						"lien"=>"?m=".$_REQUEST["m"]."&a=".$art,
						"from"=>$commentAr["givenName"].' '.$commentAr["sn"],
					);
	
					$mail=new \igblog\mail\mail($ar1,"comment");
					$mail->sendNews();
				}catch (\Exception $e){
					echo $e->getMessage();
					die();
				}
			}
		}

		$ar=array(
			"sn"=>isset($commentAr['sn']) ?$commentAr['sn'] : htmlspecialchars($_POST['sn']),
			"givenname"=>isset($commentAr['givenName']) ? $commentAr['givenName'] : htmlspecialchars($_POST['givenName']),
			"mail"=>isset($commentAr['mail']) ? $commentAr['mail'] : htmlspecialchars($_POST['mail']),
			"subject"=>'Nouveau commentaire',
			"text"=>$commentAr['msg'],
			"lien"=>"?m=".$_REQUEST["m"]."&a=".$art,
			"from"=>$commentAr["givenName"].' '.$commentAr["sn"],

		);

		$mail=new \igblog\mail\mail($ar,"comment");
		$mail->sendAdmin();


	}

	$this->getInclude("header"); 

	$disable=false;
	if($this->data['user']){
		$ar=array(
			"sn"=>isset($this->data['user']['sn']) ? $this->data['user']['sn'] : '',
			"givenName"=>isset($this->data['user']['givenName']) ? $this->data['user']['givenName'] : '',
			"mail"=>isset($this->data['user']['mail']) ? $this->data['user']['mail'] : '',
		);
		$disable=true;
	}

?>
	<div id="content" class="comment">
		<div>
			<h1>Pour saisir un commentaire, veuillez remplir le formuaire.</h1>
			<form name="f" method="POST">
				<input type="text" id="sn" name="sn" placeholder="Votre nom" value="<?php echo isset($ar["sn"]) ? $ar["sn"] : '' ;?>" <?php if($disable) echo 'readonly'; ?> required/></br>
				<input type="text" id="givenanme" name="givenName" placeholder="Votre prénom" value="<?php echo isset($ar["givenName"]) ? $ar["givenName"] : '';?>" <?php if($disable) echo 'readonly'; ?> required/></br>
				<input type="email" id="mail" name="mail" placeholder="Votre email" value="<?php echo isset($ar["mail"]) ? $ar["mail"] : '';?>" <?php if($disable) echo 'readonly'; ?> required/></br>
				<textarea name="text" id="text" placeholder="Votre message" required ></textarea></br>
				<input type="submit" id="subComment" name="subComment" value="Envoyer">
			</form>

		</div>
		<div>
<?php
	if($article['visible']){
		echo '<div class="article" id="$art">';
		echo '<h2>'.$article["title"].'</h2>';
		echo str_replace('../getImage.php','getImage.php',$article["content"]);
		echo '</div>';
	}
?>
		</div>
	</div>


<?php $this->getInclude("footer"); ?>