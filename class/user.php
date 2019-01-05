<?php

namespace igblog;
class user{

	private $user;
	private $server;
	function __construct($server){
		$this->server=$server;
		if(!isset($server["uid"])){
			$this->user=false;
			return false;
		}

		$this->user=array(
			"id"=>$server["uid"],
			"sn"=>$server["sn"],
			"cn"=>$server["cn"],
			"givenName"=>$server["givenName"],
			"mail"=>$server["mail"],
		);

	}

	public function getUser(){
		return $this->user;
	}


}
?>