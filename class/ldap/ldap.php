<?php
namespace igblog\ldap;

class ldap{

	public $ldapConn;

	public function __construct(){
		require \voyage\fonctions::getConfFile("ldap");

		if(!$ldapconn = \ldap_connect($config['host'],$config['port']))
			throw new \Exception("LDAP Connexion failed");

		ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);
		if(!ldap_bind($ldapconn, $config['userSearch'], $config['password']))
			throw new \Exception("LDAP authentication failed");

		$this->ldapConn=$ldapconn;

			
	}

	public function __destruct(){
		if($this->ldapConn)
			ldap_close($this->ldapConn);
	}
}

?>