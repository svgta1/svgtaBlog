<?php
	$conf=$this->data["storage"]->getcall("getGeneralConf");
	$this->getInclude("adminheader"); 
	$mailListe=$this->data["storage"]->getcall("getMail",0,20);

?>
	<h3>Liste des 20 derniers mails reçus par ordre décroissant de date d'envoi</h3>
	<div id="mailListe">
		<ul>
<?php 
	if($mailListe)
	foreach($mailListe as $mail){
		$subject = isset($mail['subject']) ? $mail['subject'] : '' ;
		$text = isset($mail['text']) ? $mail['text'] : '' ;

		echo '<li><b>Ref système</b> : '.$mail['uuid'].'<br/><br/><ul>';
		echo '<li><b>De</b> : '.$mail['givenname'].' '.$mail['sn'].'</li>';
		echo '<li><b>email</b> : <a href="mailto:'.$mail['mail'].'?subject=Re-'.$subject.'" >'.$mail['mail'].'</a></li>';
		echo '<li><b>Date d\'envoi</b> : '.$mail['sendDate'].'</li>';
		echo '<li><b>Sujet</b> : '.$subject .'</li>';
		echo '<li><b>Contenu</b> : <div class="content">'.$text.'</div></li>';
		echo '</ul></li>';
	}

?>
		</ul>
	</div>

<?php 
	$this->getInclude("adminfooter");
?>