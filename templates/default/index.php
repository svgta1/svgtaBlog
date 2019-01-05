<?php 
	if(isset($_REQUEST["a"]) AND isset($_REQUEST["r"]) AND ($_REQUEST['r'] == 'comment')){
		require 'comment.php';
		exit();
	}

	$generalConf=$this->data["storage"]->getCall("getGeneralConf");
	if(isset($generalConf['articleTri']) AND ($generalConf['articleTri'] == "DESC"))
		$desc=true;
	else
		$desc=false;

	if(!$this->data['user'])
		$connected=false;
	else
		$connected=true;

	$contact=false;
	$mailList=$this->data["storage"]->getCall("getContactMail");
	if($connected AND (in_array($this->data['user']['mail'],$mailList)))
		$contact=true;

	$seeCom=true;
	if($generalConf["com"]["vconnect"] AND !$connected)
		$seeCom=false;
	if($generalConf["com"]["vrestriction"] AND !$contact)
		$seeCom=false;

	$writeCom=true;
	if($generalConf["com"]["connect"] AND !$connected)
		$writeCom=false;
	if($generalConf["com"]["restriction"] AND !$contact)
		$writeCom=false;



	
	$this->getInclude("header"); 

?>
	<div id="content">
	<?php
		if(isset($this->data["smenu"]["articles"])){
			if($desc)
				$this->data["smenu"]["articles"]=array_reverse($this->data["smenu"]["articles"]);
			foreach($this->data["smenu"]["articles"] as $art){
				if(isset($_REQUEST['a']) AND !($_REQUEST['a']===$art))
					continue;

				$article=$this->data["storage"]->call("getArticle",$art);
				if($article['visible']){
					echo '<div class="article" id="'.$art.'"><div>';
					echo '<h2>'.$article["title"].'</h2>';
					echo str_replace('../getImage.php','getImage.php',$article["content"]);
					echo '</div><div>';
					if($generalConf['com']["actif"]){
						$sessBlog=$_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].'/'.$_SERVER['REQUEST_URI'];
						echo '<h4>Commentaires</h4>';
						$commentListe=$this->data["storage"]->getCall("getComment", $art);
						if($seeCom){
							if($commentListe){
								foreach($commentListe as $comment){
									$msg='<span style="color:#6B5B95;"><b>'.$comment['givenName'].' '.$comment['sn'] .' </b></span>'.$comment['msg'];
									if(($generalConf["com"]["vvalid"] AND $comment['valide']) OR !($generalConf["com"]["vvalid"]))
										echo '<div class="comment">'.$msg.'</div>';
								}
							}
						}else if($commentListe){
							if(!$connected){
								echo '<a href="secure/?_SessBlog='.$sessBlog.'">Veuillez vous connecter pour voir les commentaires</a><br />';
							}
						}

						$m=isset($_REQUEST['m']) ? $_REQUEST['m'] : '';
						if($writeCom){
							echo '<br /><a href="?m='.$m.'&a='.$art.'&r=comment">Laissez un commentaire sur cet article</a>';
						}else if(!$connected){
							echo '<br /><a href="secure/?_SessBlog='.$sessBlog.'">Veuillez vous connecter pour laisser un commentaire</a><br />';
						}
					}
					echo '</div></div>';
				}
			}
		}
	?>
	</div>
<?php $this->getInclude("footer"); ?>