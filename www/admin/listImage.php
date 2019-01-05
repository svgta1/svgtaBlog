<?php
	namespace igblog;
	session_name('blogIg');
	session_start();
	if(!isset($_SESSION['blogIG']))
		$_SESSION['blogIG']=array();

	require dirname(dirname(dirname(__FILE__))).'/class/autoload.php';
	require dirname(dirname(dirname(__FILE__))).'/vendor/autoload.php';

	require fonctions::getConfFile("admin");
	$userC= new user($_SESSION['blogIG']);
	$user=$userC->getUser();
	if(!in_array($user["mail"],$config["mailAuth"])){
		header('Location: ../secure/?__SessBlog=admin/listImage.php');
	}


	$storage = new storage\storage();
	$ret=$storage->call("getAllFile");

	echo json_encode($ret);

?>