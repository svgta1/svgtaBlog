<?php
	namespace igblog;
	session_name('blogIg');
	session_start();
	if(!isset($_SESSION['blogIG']))
		$_SESSION['blogIG']=array();

	require dirname(dirname(dirname(__FILE__))).'/class/autoload.php';
	require dirname(dirname(dirname(__FILE__))).'/vendor/autoload.php';

	require fonctions::getConfFile("admin");
	$adminListe=$config["mailAuth"];

	$storage= new storage\storage();

	$userC= new user($_SESSION['blogIG']);
	$user=$userC->getUser();

	$confFile=$storage->getCall("getGeneralConf");
	if(isset($confFile["adminListe"]))
		$adminListe=array_merge($adminListe, $confFile["adminListe"]);

	if(!in_array($user["mail"],$config["mailAuth"])){
		header('Location: ../secure/?__SessBlog=admin');
	}


	if($_POST AND isset($_POST["from"])){
		if($_POST["from"] == "sitemenu"){
			$globalMenu=$storage->getcall("getMenuRaw");

			if(isset($_REQUEST["m"])){
				$menuId=$_REQUEST["m"];
				$subMenu=$storage->getcall("getArticlesMenu",$_REQUEST["m"]);
				$position=count($subMenu["subMenu"]);
			}else{
				$menuId=false;
				$subMenu=false;
				$position=count($globalMenu);
			}

			$position ++;

			if(isset($_POST["updatemenu"]) AND $menuId){
				$subMenu["name"]=$_POST["name"];
				$subMenu["visible"]=isset($_POST["visible"]) ? true : false;
				$subMenu["position"]=$_POST["position"];
				$storage->call("setArticlesMenu",$subMenu["uuid"],$subMenu);

				if($subMenu["parent"]){
					$parent=$storage->getcall("getArticlesMenu",$subMenu["parent"]);
					$sb=$parent["subMenu"];
				}else{
					$sb=$globalMenu;
				}

				$pos=$subMenu["position"] - 1;
				if ($pos < 0)
					$pos = 0;
				$a=array($pos => $subMenu["uuid"]);
				foreach($sb as $k => $v){
					if(!isset($a[$k]) AND ($v != $subMenu["uuid"]))
						$a[$k]=$v;
					else if($v != $subMenu["uuid"])
						array_push($a,$v);
				}
				ksort($a);
				$ar=fonctions::setPosition($a);
				if($subMenu["parent"]){
					$parent["subMenu"]=$ar;
					$storage->call("setArticlesMenu",$parent["uuid"],$parent);
				}else{
					$storage->call("setMenu",$ar);
				}

			}

			if(isset($_POST["delmenu"]) AND $menuId){
				if($subMenu["parent"]){
					$parent=$storage->getcall("getArticlesMenu",$subMenu["parent"]);
					$idparent=$parent["uuid"];
					$sb=$parent["subMenu"];
				}else{
					$sb=$globalMenu;
					$idparent=$parent["false"];
				}

				foreach($sb as $k=>$v){
					if($v == $subMenu["uuid"])
						unset($sb[$k]);
				}
				ksort($sb);
				fonctions::setPosition($sb);
				$storage->call("delMenu",$menuId);
				if($subMenu["parent"]){
					$parent["subMenu"]=$sb;
					$storage->call("setArticlesMenu",$parent["uuid"],$parent);
				}else{
					$storage->call("setMenu",$sb);
				}

				if($idparent)
					header('Location: ?t=sitemenu&m='.$idparent);
				else
					header('Location: ?t=sitemenu');
				die();



			}

			if(isset($_POST["createmenu"])){
				$newMenu=fonctions::newMenu($menuId,$position);
				$storage->call("setArticlesMenu",$newMenu["uuid"],$newMenu);

				if(!$menuId){
					array_push($globalMenu,$newMenu["uuid"]);
					$storage->call("setMenu",$globalMenu);
				}else{
					array_push($subMenu["subMenu"],$newMenu["uuid"]);
					$storage->call("setArticlesMenu",$menuId,$subMenu);
				}

				header('Location: ?t=sitemenu&m='.$newMenu["uuid"]);
				die();
			}
			if(isset($_POST["createarticle"]) AND $menuId){
				if(isset($subMenu["articles"])){
					$position=count($subMenu["articles"]) + 1;
				}else{
					$subMenu["articles"]=array();
					$position=1;
				}
				$newArticle=fonctions::newArticle($menuId,$position);
				$storage->call("setArticle",$newArticle["uuid"],$newArticle);
				
				array_push($subMenu["articles"],$newArticle["uuid"]);
				$storage->call("setArticlesMenu",$subMenu["uuid"],$subMenu);
				header('Location: ?t=article&m='.$menuId.'&a='.$newArticle["uuid"]);
				die();

			}

		}
		if($_POST["from"] == "contact" AND isset($_POST["adcontact"])){
			header('Location: ?t=contactAd');
			die();

		}

	}
	
	$menu=$storage->call("getMenu");


	$data=array(
		"nav"=>$config["menu"],
		"menu"=>$menu,
		"user"=>$user,
		"storage"=>$storage,
	);

	$t=new XHML_Loader();
	if(isset($_REQUEST['t'])){
		try{
			$data["target"] = $_REQUEST['t'];
			$t->show('admin/'.$_REQUEST['t'],$data);
		}catch (\Exception $e){
			$t->show('admin/index',$data);
		}
	}else{
		$t->show('admin/index',$data);
	}


?>