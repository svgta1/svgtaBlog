<?php
namespace igblog\storage;
use igblog\fonctions as fonctions;

class storage{

	private $storageListe;

	public function __construct(){
		require fonctions::getConfFile("config");
		$this->storageListe=array();
		foreach($config["storage"] as $storage){
			$a=new $storage();
			array_push($this->storageListe,$a);
		}
	}

	public function call($method){
		$args=func_get_args();
		$method=$args[0];
		unset($args[0]);
		ksort($args);

		foreach($this->storageListe as $k=>$storage){
			if($k===0)
				$ret=call_user_func_array(array($storage, $method), $args);
			else
				call_user_func_array(array($storage, $method), $args);
		}

		return $ret;
	}

	public function getcall($method){
		$args=func_get_args();
		$method=$args[0];
		unset($args[0]);
		ksort($args);

		$ret=call_user_func_array(array($this->storageListe[0], $method), $args);
		return $ret;
	}

}


?>