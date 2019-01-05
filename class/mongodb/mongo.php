<?php
namespace igblog\mongodb;
use \MongoDB\Client as client;

class mongo extends client{

	private $db;

	public function __construct(){
		require \voyage\fonctions::getConfFile("mongo");
		$this->db=$config['db'];
		$param=array(
			"username"=>$config['userName'],
			"password"=>$config['password'],
			"authSource"=>$config['authSource'],
		);
		if(isset($config["replicatSet"]) AND $config['replicatSet'])
			$param["replicaSet"]=config['replicatSet'];

		$uri="mongodb://".$config["host"].":".$config["port"];
		parent::__construct($uri,$param);
	}

	public function connectDB(){
		return $this->selectDatabase($this->db);
	}
}

?>