<?php
	session_start();
	if($_SERVER["REQUEST_SCHEME"] == "http"){
		$redirect = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
		header('location: ' . $redirect);
		exit();
	}

	if(!isset($_SESSION['blogIG']))
		$_SESSION['blogIG']=array();

	require_once('/home/www/authent/lib/_autoload.php');
 
	$as = new SimpleSAML_Auth_Simple('multisource');
	$as->requireAuth();
	$attributes = $as->getAttributes();
	$attributesData = $as->getAuthDataArray();

	$authproc = array(
		array('class' => 'core:AttributeMap',  'authoauth2:microsoft2name'),
		array('class' => 'core:AttributeMap',  'authoauth2:facebook2name'),
		array('class' => 'core:AttributeMap',  'authoauth2:oidc2name'),
		array('class' => 'core:AttributeMap',  'authIg:facebook'),
	);

	foreach($authproc as $map){
		$att=new sspmod_core_Auth_Process_AttributeMap($map,false);
		$att->process($attributesData);
	}
	session_write_close(); 

	session_name('blogIg');
	session_start();

	foreach($attributesData['Attributes'] as $k=>$v){
		$_SESSION['blogIG'][$k]=$v[0];
	}

	$_SESSION['blogIG']['logState']=serialize($as);

	if(isset($_REQUEST) AND isset($_REQUEST['_SessBlog']))
		header('Location: '.$_REQUEST['_SessBlog']);
	else
		header('Location: ../ ');
?>