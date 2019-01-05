<?php

spl_autoload_register(function ($class_name) {
	$ar=explode("\\",$class_name);
	$dir=dirname(__FILE__);
	for($i = 1; $i < count($ar); $i++){
		$dir .= '/'.$ar[$i];
	}
	$file=$dir.'.php';

	if(is_file($file))
		include $file;
    
});

?>