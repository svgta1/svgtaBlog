<?php

namespace igblog;

class fonctions{

	public static function getConfFile($file){
		try{
			$envData=(new \josegonzalez\Dotenv\Loader(dirname(dirname(__FILE__)).'/.env'))
				->parse()
				->expect('CONF_BLOG_DIR')
				->putenv(false);

			$dir=getenv('CONF_BLOG_DIR');
		}catch(\RuntimeException $e){
			$dir=dirname(dirname(__FILE__)).'/config';
		}catch(\InvalidArgumentException $e){
			$dir=dirname(dirname(__FILE__)).'/config';
		}catch(\LogicException $e){
			$dir=getenv('CONF_BLOG_DIR');
		}

		$file=$dir.'/'.$file.'.php';
		if(!is_file($file))
			throw new \Exception("conf file not found");

		return $file;
	}

	public function genUUID(){
		return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
			// 32 bits for "time_low"
			mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),

			// 16 bits for "time_mid"
			mt_rand( 0, 0xffff ),

			// 16 bits for "time_hi_and_version",
			// four most significant bits holds version number 4
			mt_rand( 0, 0x0fff ) | 0x4000,

			// 16 bits, 8 bits for "clk_seq_hi_res",
			// 8 bits for "clk_seq_low",
			// two most significant bits holds zero and one for variant DCE1.1
			mt_rand( 0, 0x3fff ) | 0x8000,

			// 48 bits for "node"
			mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
		);
	}

	public function oToAr($o){
		$ar=array();
		if($o)
		foreach($o as $k=>$v){
			if(is_object($v))
				$ar[$k]=self::oToAr($v);
			else
				$ar[$k]=$v;
		}

		return $ar;
	}

	public function getMenu($id,$menu){
		$ret='<ul>';
		foreach($menu as $k=>$v){
			$ret .= '<li>';
			$ret .= '<a href="?t='.$id.'&m='.$v["uuid"].'">'.$v['name'].'</a>';
			if(isset($v["subMenu"]) AND $v["subMenu"]){
				$ret .= self::getMenu($id,$v["subMenu"]);
			}
			$ret .= '</li>';
		}
		

		$ret .='</ul>';

		return $ret;
		
	}

	private function _getMenuBlog($id,$menu){
		$ret='<ul>';
		foreach($menu as $k=>$v){
			if($v['visible']){
				$ret .= '<li>';
				$ret .= '<a href="?t='.$id.'&m='.$v["uuid"].'">'.$v['name'].'</a>';
				if(isset($v["subMenu"]) AND $v["subMenu"]){
					$ret .= self::getMenu($id,$v["subMenu"]);
				}
				$ret .= '</li>';
			}
		}
		

		$ret .='</ul>';

		return $ret;
		
	}


	public function getMenuBlog($id,$menu){
		$ret='<ul>';
		if(!$menu)
			return false;
		foreach($menu as $k=>$v){
			if($v['visible']){
				if(isset($v["subMenu"]) AND $v["subMenu"])
					$sMenu=true;
				else
					$sMenu=false;
				$ret .= '<li>';
				if($sMenu){
					$ret .= '<a href="?m='.$v["uuid"].'" class="havesubmenu">'.$v['name'].'</a>';
					$ret .= self::_getMenuBlog($id,$v["subMenu"]);
				}else
					$ret .= '<a href="?m='.$v["uuid"].'">'.$v['name'].'</a>';

				$ret .= '</li>';
			}
		}

		$storage = new storage\storage();
		$globalConf=$storage->call("getGeneralConf");

		if(isset($globalConf['contact']) AND $globalConf['contact']['actif'])
			$ret .= '<li><a href="?t=contact">Contactez nous</a></li>';

		$ret .='</ul>';

		return $ret;
		
	}


	public function newMenu($parent = false, $position = 1){

		$ar=array(
			"uuid"=>self::genUUID(),
			"name"=>"Nom du Menu",
			"parent"=>$parent,
			"visible"=>true,
			"subMenu"=>array(),
			"position"=>$position,
		);

		return $ar;
	}

	public function newArticle($parent = false, $position = 1){

		$ar=array(
			"uuid"=>self::genUUID(),
			"title"=>"titre",
			"parent"=>$parent,
			"visible"=>true,
			"position"=>$position,
		);

		return $ar;
	}


	public function setPosition($ar){
		$storage= new storage\storage();

		$ret=array();
		foreach($ar as $v){
			array_push($ret,$v);
		}

		foreach($ret as $k=>$uuid){
			$sm=$storage->getcall("getArticlesMenu",$uuid);
			$sm["position"]=$k + 1;
			$storage->call("setArticlesMenu",$uuid,$sm);
		}

		return $ret;

	}

	public function isAdmin($mail){
		require self::getConfFile("admin");
		$adminListe=$config["mailAuth"];
		if(!in_array($mail,$config["mailAuth"]))
			return false;
		else
			return true;
	}
}


?>