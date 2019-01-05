<?php
namespace igblog;

class XHML_Loader{
	private $data;
	private $theme;
	protected $defaultTemplateDir;
	protected $templateDir;
	private $components;
	private $resourcesDir;

	public function __construct(){
		require fonctions::getConfFile("config");

		$templateDir=dirname(dirname(__FILE__)).'/templates';
		$this->defaultTemplateDir=$templateDir .'/default';

		if(isset($config['theme']))
			$this->theme = $config['theme'];
		else 
			$this->theme = "default";

		$templateDir .= '/'.$this->theme;

		if(!is_dir($templateDir))
			$templateDir=$this->defaultTemplateDir;

		if(!is_dir($templateDir))
			throw new \Exception ('Default Template not found');

		$this->templateDir=$templateDir;

		$this->components=$config["components"];
		$this->resourcesDir=$config["resourcesDir"];

	}

	public function show($file,$data){
		$this->data=$data;
		$templateDir=$this->templateDir;

		$templateFile=$templateDir.'/'.$file.'.php';
		if(!is_file($templateFile))
			$templateFile=$this->defaultTemplateDir.'/'.$file.'.php';

		if(!is_file($templateFile))
			throw new \Exception ('File not found in template OR default template');

		require $templateFile;
		if(isset($ret))
			return $ret;
	}

	public function getInclude($file){
		$templateDir=$this->templateDir.'/includes';
		if(!is_dir($templateDir))
			$templateDir=$this->defaultTemplateDir.'/includes';

		if(!is_dir($templateDir))
			throw new \Exception ('Default includes Template not found');

		$templateFile=$templateDir.'/'.$file.'.php';
		if(!is_file($templateFile))
			$templateFile=$this->defaultTemplateDir.'/includes/'.$file.'.php';

		if(!is_file($templateFile))
			throw new \Exception ('File not found in template OR default template : '.$templateFile);

		require $templateFile;
		if(isset($ret))
			return $ret;

	}
	public function getComponents($file){
		$defaultDir=dirname(dirname(__FILE__)).'/www/resources/default';
		$themeDir=dirname(dirname(__FILE__)).'/www/resources/'.$this->theme;
		if(is_file($themeDir.'/'.$file))
			return '/resources/'.$this->theme.'/'.$file;
		else if(is_file($defaultDir.'/'.$file))
			return '/resources/default/'.$file;
		else
			throw new \Exception ('Component File not found in template OR default template :'.$file);
	}

	public function getComponentsFromConfig(){
		$sortie=array();
		foreach($this->components as $k=>$ar){
			foreach($ar as $component){
				$file=$k.'/'.$component.'.'.$k;
				$file=$this->getComponents($file);
				if($k == "css"){
					array_push($sortie,'<link rel="stylesheet" type="text/css" href="/'.$this->resourcesDir.$file.'" media="screen">');
				}else if($k == "js"){
					array_push($sortie,'<script type="text/javascript" src="/'.$this->resourcesDir.$file.'"></script>');
				}else{
					throw new \Exception ('Component not known :'.$file);
				}
			}
		}

		return $sortie;
	}
}


?>