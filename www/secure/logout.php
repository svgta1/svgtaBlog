<?php
	require_once('/home/www/authent/lib/_autoload.php');
	session_name('blogIg');
	session_start();
	if(!isset($_SESSION['blogIG']['logState']))
		throw new Exception ('c\'est la merde');


	$as=unserialize($_SESSION['blogIG']['logState']);

	if($as->isAuthenticated()){
		$_SESSION['blogIG']=array();
		$as->logout(array(
			'ReturnTo' => '../',
			'ReturnStateParam' => 'LogoutState',
			'ReturnStateStage' => 'MyLogoutState',
		));
	}else{
		echo "ben... pas loggé";
	}
?>