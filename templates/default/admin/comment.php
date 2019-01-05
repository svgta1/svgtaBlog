<?php
	$conf=$this->data["storage"]->getcall("getGeneralConf");
	$this->getInclude("adminheader"); 
	$menuAr=$this->data["storage"]->getcall("getMenu");

	if($_POST){
		if(isset($_POST['validCom'])){
			$ar=array(
				"sn"=>$_POST["sn"],
				"givenName"=>$_POST["givenName"],
				"mail"=>$_POST["mail"],
				"msg"=>$_POST["msg"],
				"uuid"=>$_POST["uuid"],
				"valide"=>true,
				"date"=>$_POST["date"],
			);
			$this->data["storage"]->call("setComment",$_POST['art'],$ar);

			$listAbo=$this->data["storage"]->getCall("getContact");
			foreach($listAbo as $abo){
				try{
					$ar1=array(
						"sn"=>$abo['sn'],
						"givenname"=>$abo['givenname'],
						"mail"=>$abo['mail'],
						"lien"=>"?m=".$_POST['menu']."&a=".$_POST['art'],
						"from"=>$ar["givenName"].' '.$ar["sn"],
						"text"=>"",
					);
	
					$mail=new \igblog\mail\mail($ar1,"comment");
					$mail->sendNews();
				}catch (\Exception $e){
					echo $e->getMessage();
					die();
				}
			}

		}
		if(isset($_POST['hideCom'])){
			$ar=array(
				"sn"=>$_POST["sn"],
				"givenName"=>$_POST["givenName"],
				"mail"=>$_POST["mail"],
				"msg"=>$_POST["msg"],
				"uuid"=>$_POST["uuid"],
				"valide"=>false,
				"date"=>$_POST["date"],
			);
			$this->data["storage"]->call("setComment",$_POST['art'],$ar);
		}
		if(isset($_POST['delCom'])){
			$this->data["storage"]->call("delComment",$_POST['art'],$_POST['uuid']);
		}


	}

	foreach($menuAr as $menu){
		if(isset($menu['articles']) AND (count($menu['articles']) >0 )){
			foreach($menu['articles'] as $art){
				$article=$this->data["storage"]->getCall("getArticle",$art);
				if($comment=$this->data["storage"]->getCall("getComment",$art)){
					$artAr=$this->data["storage"]->getcall("getArticle",$art);
					echo '<div>';
					echo '<div class="article">';
					echo '<h2>'.$article["title"].'</h2>';
					echo $article["content"];
					echo '</div>';
					echo '<div class="comment">';
					foreach($comment as $com){
						echo '<div style="display:block;">';
						echo '<form method="POST" name="f'.$com["uuid"].'">';
						echo '<input type="text" readonly name="username" value="'.$com["givenName"].' '.$com["sn"].'">';
						echo '<input type="email" readonly name="mail" value="'.$com["mail"].'">';
						if(isset($com['date']))
							echo '<input type="text" readonly name="dateVi" value="'.date('Y/m/d H:i:s',$com['date']).'">';
						echo '<input type="hidden" name="date" value="'.$com['date'].'">';
						echo '<input type="hidden" name="uuid" value="'.$com["uuid"].'">';
						echo '<input type="hidden" name="art" value="'.$art.'">';
						echo '<input type="hidden" name="givenName" value="'.$com["givenName"].'">';
						echo '<input type="hidden" name="sn" value="'.$com["sn"].'">';
						echo '<input type="hidden" name="menu" value="'.$menu["uuid"].'">';
						echo '<textarea name="msg" readonly>'.$com['msg'].'</textarea>';
						if($com['valide'])
							echo '<input type="submit" name="hideCom" value="Masquer le commentaire" class="hide">';
						else
							echo '<input type="submit" name="validCom" value="Afficher le commentaire" class="aff">';
						echo '<input type="submit" name="delCom" value="Supprimer le commentaire" class="del">';
						echo '</form>';
						echo '</div>';
					}

					echo '</div>';
					echo '<hr />';
					echo '</div>';
				}
			}
		}
	}
?>
<?php 
	$this->getInclude("adminfooter");
?>