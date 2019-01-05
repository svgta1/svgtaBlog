	<div>
	<h1>Pour nous contacter, veuillez remplir le formuaire.</h1>
	<form name="f" method="POST">
		<input type="text" id="sn" name="sn" placeholder="Votre nom" value="<?php echo $ar["sn"];?>" <?php if($disable) echo 'readonly'; ?> required/></br>
		<input type="text" id="givenanme" name="givename" placeholder="Votre prénom" value="<?php echo $ar["givenname"];?>" <?php if($disable) echo 'readonly'; ?> required/></br>
		<input type="email" id="mail" name="mail" placeholder="Votre email" value="<?php echo $ar["mail"];?>" <?php if($disable) echo 'readonly'; ?> required/></br>
		<input type="text" id="subject" name="subject" placeholder="Sujet du message" required /><br />
		<textarea name="text" id="text" placeholder="Votre message" required ></textarea></br>
		<input type="submit" id="subContact" name="subContact" value="Envoyer">
	</form>
	</div>

	<script type="text/javascript">
		var f=false;
		$$('input[type="text"]').each(function(elm){
			if(!elm.readOnly && (f==false)){
				f=true;
				elm.focus();
			}
		});
	</script>