<?php
	namespace igblog;
	require dirname(dirname(__FILE__)).'/class/autoload.php';
	require dirname(dirname(__FILE__)).'/vendor/autoload.php';
	session_start();

	$storage = new storage\storage();
	$image=$_REQUEST['image'];

	$fileAr=$storage->call('getFile',$image);

	$filename = $fileAr['value'];
	$file_extension = strtolower(substr(strrchr($filename,"."),1));

	switch( $file_extension ) {
		case "gif": $ctype="image/gif"; break;
		case "png": $ctype="image/png"; break;
		case "jpeg":
		case "jpg": $ctype="image/jpeg"; break;
		default:
	}

	header('Content-type: ' . $ctype);
	header('Content-Disposition: inline; filename="'.$fileAr['title'].'"');
	readfile($fileAr['location']);
?>