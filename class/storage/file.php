<?php
namespace igblog\storage;
use igblog\fonctions as fonctions;

class file {

	private $dataSore;
	private $confFile;
	
	const IMG="images";
	const IMGLISTE="images.json";
	const IMGDB="db";
	const MENU="menus";
	const ARTICLE="articles";
	const CONF="config";
	const IMGCOMPRESS=80;

	const MAIL="mail";
	const MAILCONTACT="mail/contact";
	
	const CONTACT = "contact";
	const COMMENT = "comment";


	public function __construct(){
		require fonctions::getConfFile("file");
		$this->confFile=$config;
		if(!is_dir($config["dataStore"]))
			mkdir($config["dataStore"]);

		if(!is_dir($config["dataStore"].'/'.self::IMG))
			mkdir($config["dataStore"].'/'.self::IMG);


		if(!is_dir($config["dataStore"].'/'.self::MENU))
			mkdir($config["dataStore"].'/'.self::MENU);


		if(!is_dir($config["dataStore"].'/'.self::ARTICLE))
			mkdir($config["dataStore"].'/'.self::ARTICLE);

		if(!is_dir($config["dataStore"].'/'.self::CONF))
			mkdir($config["dataStore"].'/'.self::CONF);

		if(!is_dir($config["dataStore"].'/'.self::MAIL))
			mkdir($config["dataStore"].'/'.self::MAIL);

		if(!is_dir($config["dataStore"].'/'.self::MAILCONTACT))
			mkdir($config["dataStore"].'/'.self::MAILCONTACT);

		if(!is_dir($config["dataStore"].'/'.self::CONTACT))
			mkdir($config["dataStore"].'/'.self::CONTACT);


		if(!is_dir($config["dataStore"].'/'.self::COMMENT))
			mkdir($config["dataStore"].'/'.self::COMMENT);



		$this->dataStore=$config["dataStore"];
	}

	public function getMenuRaw(){
		$menuListe=$this->dataStore.'/'.self::MENU.'/menu.json';
		if(!is_file($menuListe))
			return array();

		$json=json_decode(file_get_contents($menuListe));
		$ar=fonctions::oToAr($json);
		return $ar;

	}
	public function getMenu(){
		$ar=$this->getMenuRaw();
		$res=$this->constructMenu($ar);

		return $res;
	}

	private function constructMenu($ar){

		$ret=array();
		foreach($ar as $uuid){
			$ret[$uuid]=$this->getArticlesMenu($uuid);
			if(isset($ret[$uuid]["subMenu"]) AND $ret[$uuid]["subMenu"])
				$ret[$uuid]["subMenu"]=$this->constructMenu($ret[$uuid]["subMenu"]);
		}

		return $ret;
	}

	public function getArticlesMenu($menu){
		$menu=$this->dataStore.'/'.self::MENU.'/'.$menu.'.json';
		if(!is_file($menu))
			throw new \Exception("Menu non trouvé : ".$menu);

		$ar=json_decode(file_get_contents($menu),TRUE);
		return $ar;
	}

	public function getArticle($article){
		$article=$this->dataStore.'/'.self::ARTICLE.'/'.$article.'.json';
		if(!is_file($article))
			throw new \Exception("Article non trouvé : ".$article);

		$ar=json_decode(file_get_contents($article), TRUE);
		return $ar;
	}

	public function setMenu($ar){
		$menuListe=$this->dataStore.'/'.self::MENU.'/menu.json';
		file_put_contents($menuListe, json_encode($ar),LOCK_EX);

	}

	public function setArticlesMenu($menu,$ar){
		$menu=$this->dataStore.'/'.self::MENU.'/'.$menu.'.json';
		file_put_contents($menu, json_encode($ar),LOCK_EX);
	}

	public function setArticle($article,$ar){
		$article=$this->dataStore.'/'.self::ARTICLE.'/'.$article.'.json';
		file_put_contents($article, json_encode($ar),LOCK_EX);
	}

	public function getGeneralConf(){
		$conf=$this->dataStore.'/'.self::CONF.'/conf.json';
		if(!is_file($conf))
			$this->setGeneralConf();

		$json=json_decode(file_get_contents($conf));
		return fonctions::oToAr($json);

	}

	public function setGeneralConf(
		$ar = array(
			"siteName"=> "Voyage JM",
			"desc"=> "Blog de notre voyage",
			"articleTri"=>"ASC",
		)
	){
		$conf=$this->dataStore.'/'.self::CONF.'/conf.json';
		if(!is_file($conf)){
			file_put_contents($conf, json_encode($ar),LOCK_EX);
			return false;
		}

		$confAr=$this->getGeneralConf();
		foreach($ar as $k=>$v){
			$confAr[$k]=$v;
		}

		file_put_contents($conf, json_encode($confAr),LOCK_EX);
	}

	public function delMenu($uuid){
		$m=$this->getArticlesMenu($uuid);
		if(isset($m['articles']))
		foreach($m['articles'] as $art){
			$this->delArticle($art);
		}
		$file = $this->dataStore.'/'.self::MENU.'/'.$uuid.'.json';
		unlink($file);
	}

	public function delArticle($uuid){
		$art=$this->getArticle($uuid);

		$menu=$this->getArticlesMenu($art['parent']);

		foreach($menu['articles'] as $k=>$v){
			if($v === $uuid)
				unset($menu['articles'][$k]);
		}
		$this->setArticlesMenu($menu["uuid"],$menu);

		$this->delAllArtComment($uuid);

		$file = $this->dataStore.'/'.self::ARTICLE.'/'.$uuid.'.json';
		unlink($file);

		return $menu['uuid'];

	}

	public function uploadFile($filename,$name,$content){
		//$imagelist=$this->dataStore.'/'.self::IMG.'/'.self::IMGLISTE;
		//if(!is_file($imagelist=$this->dataStore.'/'.self::IMG.'/'.self::IMGLISTE))
		//	$imagelistfile=array();
		//else
		//	$imagelistfile=json_decode(file_get_contents($imagelist),true);

		$dirdb=$this->dataStore.'/'.self::IMG.'/'.self::IMGDB;
		if(!is_dir($dirdb))
			mkdir($dirdb);

		//if(isset($imagelistfile[$filename]))
		//	return $imagelistfile[$filename];

		if(is_file($dirdb.'/'.$filename.'.json'))
			return json_decode(file_get_contents($dirdb.'/'.$filename.'.json'),true);
		
		$date=array(
			"a"=>date("Y"),
			"m"=>date("n"),
			"j"=>date("j"),
			"h"=>date("G"),
		);
		$dir=$this->dataStore.'/'.self::IMG;
		foreach($date as $d){
			$dir .= '/'.$d;
			if(!is_dir($dir))
				mkdir($dir);
		}

		$file=$dir.'/'.$filename;
		move_uploaded_file($content, $file);

		if (in_array(strtolower(pathinfo($name, PATHINFO_EXTENSION)), array("jpg", "jpeg"))) {
			$im=new \Imagick($file);
			$im->optimizeImageLayers();
			$im->setImageCompression(\Imagick::COMPRESSION_JPEG);
			$im->setImageCompressionQuality(self::IMGCOMPRESS);
			$im->writeImages($file, true);
		}

		//$imagelistfile[$filename]=array(
		//	"title"=>$name,
		//	"value"=>$filename,
		//	"location"=>$file,
		//);

		$imgData=array(
			"title"=>$name,
			"value"=>$filename,
			"location"=>$file,
		);


		//file_put_contents($imagelist,json_encode($imagelistfile),LOCK_EX);
		file_put_contents($dirdb.'/'.$filename.'.json',json_encode($imgData),LOCK_EX);


		return $imgData;
	}

	public function getFile($filename){
		//$imagelist=$this->dataStore.'/'.self::IMG.'/'.self::IMGLISTE;
		//$imagelistfile=json_decode(file_get_contents($imagelist),true);
		$file=$this->dataStore.'/'.self::IMG.'/'.self::IMGDB.'/'.$filename.'.json';

		//if(!isset($imagelistfile[$filename]))
		//	throw new \Exception("File not found : ".$filename);

		if(!is_file($file))
			throw new \Exception("File not found : ".$filename);


		//return $imagelistfile[$filename];
		$_file=json_decode(file_get_contents($file),true);
		$_file['view']="../getImage.php?image=".$_file['value'];
		return $_file;
		
	}

	public function updateFile($fileAr){
		$filename=$fileAr['value'];
		$file=$this->dataStore.'/'.self::IMG.'/'.self::IMGDB.'/'.$filename.'.json';
		if(!is_file($file))
			throw new \Exception("File not found : ".$filename);

		$_file=json_decode(file_get_contents($file),true);
		$_file=$fileAr;
		
		file_put_contents($file,json_encode($_file),LOCK_EX);
	}

	public function delFile($fileAr){
		$filename=$fileAr['value'];
		$file=$this->dataStore.'/'.self::IMG.'/'.self::IMGDB.'/'.$filename.'.json';
		if(!is_file($file))
			throw new \Exception("File not found : ".$filename);

		$_file=json_decode(file_get_contents($file),true);
		unlink($_file['location']);
		unlink($file);

		return $_file;

	}

	public function getAllFile(){
		$ar=array();
		$dir=$this->dataStore.'/'.self::IMG.'/'.self::IMGDB;
		$scan=scandir($dir);
		foreach($scan as $file){
			$path_parts=pathinfo($dir.'/'.$file);
			if($path_parts['extension'] == "json"){
				$content=json_decode(file_get_contents($dir.'/'.$file),true);
				$o=new \stdClass;
				$o->title=$content['title'];
				$o->value="../getImage.php?image=".$content['value'];
				$o->uuid=$content['value'];
				//array_push($ar,$o);

				$ar[$content['title'].' '.$content['value']]=$o;
			}
		}

		ksort($ar);
		return $ar;

	}

	public function setMail($ar,$mailId){
		$mailListe=$this->dataStore.'/'.self::MAIL.'/listMail.json';
		if(!is_file($mailListe))
			$mailAr=array();
		else
			$mailAr=json_decode(file_get_contents($mailListe),TRUE);

		array_unshift($mailAr, $mailId);
		file_put_contents($mailListe,json_encode($mailAr),LOCK_EX);

		$file=$this->dataStore.'/'.self::MAILCONTACT.'/'.$mailId.'.json';
		file_put_contents($file,json_encode($ar),LOCK_EX);
	}
	public function getMail($start,$limit){
		$mailListe=$this->dataStore.'/'.self::MAIL.'/listMail.json';
		if(!is_file($mailListe))
			return false;

		$mailAr=json_decode(file_get_contents($mailListe),TRUE);
		if(count($mailAr) === 0)
			return false;

		$ret=array();
		for($i=$start; $i < $limit; $i++){
			if(isset($mailAr[$i])){
				$file=$this->dataStore.'/'.self::MAILCONTACT.'/'.$mailAr[$i].'.json';
				if(is_file($file))
					array_push($ret,json_decode(file_get_contents($file), TRUE));
			}
		}

		if(count($ret) === 0)
			return false;

		return $ret;
	}

	private function infoContact($uuid){
		if(!($contactList = $this->getContact()))
			throw new \Exception("Contact liste not found : ".$filename);

		$contact=false;
		foreach($contactList as $k=>$ar){
			if($ar['uuid'] == $uuid){
				$contact=array(
					"info"=>$ar,
					"key"=>$k,
				);
			}
		}

		if(!$contact)
			throw new \Exception("Contact not found : ".$uuid);


		return $contact;
	}

	public function delContact($uuid){
		$contact=$this->infoContact($uuid);
		$contactList = $this->getContact();
		unset($contactList[$contact["key"]]);
		$file=$this->dataStore.'/'.self::CONTACT.'/contact.json';
		file_put_contents($file,json_encode($contactList),LOCK_EX);

	}

	public function updateContact($ar){
		$contact=$this->infoContact($ar["uuid"]);
		$contactList = $this->getContact();

		$contactList[$contact["key"]]=$ar;


		$file=$this->dataStore.'/'.self::CONTACT.'/contact.json';
		file_put_contents($file,json_encode($contactList),LOCK_EX);

	}

	public function setContact($ar){
		$contact=$this->getContact();
		if($contact)
			$contactAr=$contact;
		else
			$contactAr=array();

		$ar["uuid"]=fonctions::genUUID();
		array_push($contactAr, $ar);

		$arT=array();
		foreach($contactAr as $ar){
			$k=$ar['sn'].' '.$ar['givenname'].' '.$ar["uuid"];
			$arT[$k]=$ar;
		}
		ksort($arT);
		$contactAr=array();
		foreach($arT as $ar){
			array_push($contactAr, $ar);
		}

		$file=$this->dataStore.'/'.self::CONTACT.'/contact.json';
		file_put_contents($file,json_encode($contactAr),LOCK_EX);
		
	}

	public function getContact(){
		$contact=$this->dataStore.'/'.self::CONTACT.'/contact.json';
		if(!is_file($contact))
			return false;

		$contactAr=json_decode(file_get_contents($contact),TRUE);
		if(count($contactAr) === 0)
			return false;

		return $contactAr;
	}

	public function delAllArtComment($artUUID=false){
		if(!$artUUID)
			throw new \Exception('Article inconnu');

		$comment=$this->dataStore.'/'.self::COMMENT.'/'.$artUUID.'.json';
		if(!is_file($comment))
			return false;
		unlink($comment);

	}

	public function delComment($artUUID=false, $comUUID=false){
		if(!$artUUID)
			throw new \Exception('Article inconnu');
		if(!$comUUID)
			throw new \Exception('identifiant commentaire inconnu');
		if(!$commentAr=$this->getComment($artUUID)){
			$commentAr=array();
		}

		foreach($commentAr as $k=>$a){
			if($a['uuid'] == $comUUID){
				unset($commentAr[$k]);
				break;
			}
		}

		file_put_contents($this->dataStore.'/'.self::COMMENT.'/'.$artUUID.'.json', json_encode($commentAr),LOCK_EX);

	}

	public function setComment($artUUID=false, $comment=array(
		"sn"=>false,
		"givenName"=>false,
		"mail"=>false,
		"msg"=>false,
		"uuid"=>false,
		"valide"=>false,
	)){
		if(!$artUUID)
			throw new \Exception('Article inconnu');
		if(!$comment["uuid"])
			throw new \Exception('identifiant commentaire inconnu');


		if(!$commentAr=$this->getComment($artUUID)){
			$commentAr=array();
		}

		$exist=false;
		foreach($commentAr as $k=>$a){
			if($a['uuid'] == $comment['uuid']){
				$exist=$k;
				break;
			}
		}
		if($exist === false)
			array_push($commentAr, $comment);
		else
			$commentAr[$exist] = $comment;

		

		file_put_contents($this->dataStore.'/'.self::COMMENT.'/'.$artUUID.'.json', json_encode($commentAr),LOCK_EX);

		return $commentAr;
	}


	private function sortComment($a,$b){
		return ($a['date'] < $a['date']) ? -1 : 1;
	}

	public function getComment($artUUID=false){
		if(!$artUUID)
			return false;

		$comment=$this->dataStore.'/'.self::COMMENT.'/'.$artUUID.'.json';
		if(!is_file($comment))
			return false;

		$commentAr=json_decode(file_get_contents($comment),TRUE);
		if(count($commentAr) === 0)
			return false;

		usort($commentAr,'self::sortComment');

		return $commentAr;
	}


	public function getContactMail(){
		$contactListe=$this->getContact();
		$mail=array();
		foreach($contactListe as $c){
			array_push($mail,$c['mail']);
		}

		return $mail;
	}
}

?>
