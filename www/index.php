<?php

	namespace igblog;
	session_name('blogIg');
	session_start();
	require dirname(dirname(__FILE__)).'/class/autoload.php';
	require dirname(dirname(__FILE__)).'/vendor/autoload.php';


	if(!isset($_SESSION['blogIG']))
		$_SESSION['blogIG']=array();

	$userC= new user($_SESSION['blogIG']);
	$user=$userC->getUser();

	require fonctions::getConfFile("config");
	
	$rest=false;
	if(isset($config["auth"]) AND (count($config["auth"]) > 0))
		$rest=true;

	if($rest AND (!$user)){
		header('Location: secure/');
		exit();
	}


	$storage = new storage\storage();
	$globalConf=$storage->call("getGeneralConf");

	$menu=$storage->call("getMenu");

	$data=array(
		"siteName"=>$globalConf["siteName"],
		"siteDesc"=>$globalConf["desc"],
		"menu"=>$menu,
		"storage"=>$storage,
		"globalConf"=>$globalConf,
	);
	foreach($data as $k=>$v){
		if(is_string($v))
			$data[$k]=utf8_encode($v);
	}
	$data["user"] = $user;
	if($rest)
	if(!in_array($user["mail"],$config["auth"])){
		$t=new XHML_Loader();
		$data['menu']=false;
		$t->show('notAutorized',$data);
		exit();
	}


	reset($menu);
	$fm=current($menu);
	if(!isset($_REQUEST['m']))
		$m=$fm['uuid'];
	else
		$m=$_REQUEST['m'];

	try{
		$m=$storage->call("getArticlesMenu",$m);
	}catch(\Exception $e){
		try{
			$m=$storage->call("getArticlesMenu",$fm['uuid']);
		}catch (\Exception $e){
			$t=new XHML_Loader();
			$t->show('construct',array());

			exit();
		}

	}

	$data['smenu']=$m;


	$t=new XHML_Loader();
	if(isset($_REQUEST['t'])){			
		try{
			$t->show($_REQUEST['t'],$data);
		}catch (\Exception $e){
			$t->show('index',$data);
		}
	}else{
		$t->show('index',$data);
	}

?>