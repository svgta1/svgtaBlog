<?php
namespace igblog\mail;
use igblog\XHML_Loader;

class template extends XHML_Loader{
	public function __construct(){
		parent::__construct();
		$this->defaultTemplateDir=$this->defaultTemplateDir .'/mail';
		$this->templateDir=$this->templateDir.'/mail';
	}

}

?>