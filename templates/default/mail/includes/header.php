<?php
	require igblog\fonctions::getConfFile("mail");
	$title=$config['fromName'];

$header=<<<EOD
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset==UTF-8">
<meta name="viewport" content="width=device-width initial=-scale=1.0">
<title>$title</title>
<link rel='stylesheet' id='passenger-damion-css'  href='https://fonts.googleapis.com/css?family=Open+Sans%3A400italic%2C600italic%2C700italic%2C400%2C600%2C700%7CAllura&#038;ver=4.2.21' type='text/css' media='all' />
<style type="text/css">
body, html{
	margin:0;
	padding:20px;
	text-align:center;
	width:100%;
	color: #666666;
	background-color:#fff;
}

div.messageContent{
	margin: 10px;
	padding: 20px;
	background-color: #D2C29D;
}

.body{
	text-align:center;
	background-color:#D2C29D;
	color: #666666;
}
div.wrap{
	text-align:justify;
	margin-left:20px;
	margin-right:20px;
	width:auto;
	height:auto;
	background-color:white;
	padding:20px;
}
div.header{
	background-color:white;
	margin-left:20px;
	margin-right:20px;
	margin-top:20px;
	padding:20px;
	width:100%;
}
div.header h1{
    text-align: center;
    font-size: 30px;
    margin: 0;
    padding: 0;
    display: inline-block;
    min-width: 50%;
    transition: all 0.5s;
	font-family: 'Allura', cursive;
}
div.blanck{
	height:20px;
}
</style>
</head>
<body class="body">
	<div class="blanck"></div>
	<div class="header"><h1>$title<h1></div>

	<div class="wrap">
EOD;

$ret=$header;
?>