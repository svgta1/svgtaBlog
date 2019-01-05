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
		header('Location: ../secure/?__SessBlog=admin/uploadImage.php');
	}


	$storage = new storage\storage();


  /*******************************************************
   * Only these origins will be allowed to upload images *
   ******************************************************/
  $accepted_origins = array("http://localhost", "http://192.168.1.1", "https://sd-110171.dedibox.fr");

  /*********************************************
   * Change this line to set the upload folder *
   *********************************************/

  reset ($_FILES);
  $temp = current($_FILES);
  if (is_uploaded_file($temp['tmp_name'])){
    if (isset($_SERVER['HTTP_ORIGIN'])) {
      // same-origin requests won't set an origin. If the origin is set, it must be valid.
      if (in_array($_SERVER['HTTP_ORIGIN'], $accepted_origins)) {
        header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);
      } else {
        header("HTTP/1.1 403 Origin Denied");
        return;
      }
    }

    /*
      If your script needs to receive cookies, set images_upload_credentials : true in
      the configuration and enable the following two headers.
    */
    // header('Access-Control-Allow-Credentials: true');
    // header('P3P: CP="There is no P3P policy."');

    // Sanitize input
    if (preg_match("/([^\w\s\d\-_~,;:\[\]\(\).])|([\.]{2,})/", $temp['name'])) {
        header("HTTP/1.1 400 Invalid file name.");
        return;
    }

    // Verify extension
    if (!in_array(strtolower(pathinfo($temp['name'], PATHINFO_EXTENSION)), array("gif", "jpg", "png", "jpeg"))) {
        header("HTTP/1.1 400 Invalid extension.");
        return;
    }

    // Accept upload if there was no origin, or if it is an accepted origin
	$uuid=sha1_file($temp['tmp_name']);
	$fileName=$uuid.'.'.pathinfo($temp['name'], PATHINFO_EXTENSION);

	$fileupload=$storage->call("uploadFile",$fileName,$temp['name'],$temp['tmp_name']);

	$loc='../getImage.php?image='.$fileupload['value'];
    echo json_encode(array('location' => $loc));
  } else {
    // Notify editor that the upload failed
    header("HTTP/1.1 500 Server Error");
  }
?>
