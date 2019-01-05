<?php 

	if($_POST){
		$articleId=$_REQUEST["a"];
		$menuId=$_REQUEST["m"];
		$article=$this->data["storage"]->getCall("getArticle",$articleId);

		if(isset($_POST["updatearticle"])){
			$article["title"]=$_POST["title"];
			$article["visible"]=isset($_POST["visible"]) ? true : false;
			$article["position"]=isset($_POST["position"]) ? $_POST["position"] : time();
			$article["content"]=$_POST["content"];
			$this->data["storage"]->call("setArticle",$articleId,$article);

			$listAbo=$this->data["storage"]->getCall("getContact");
			foreach($listAbo as $abo){
				try{
					$ar=array(
						"sn"=>$abo['sn'],
						"givenname"=>$abo['givenname'],
						"mail"=>$abo['mail'],
						"lien"=>"?m=".$menuId."&a=".$articleId,
					);
	
					$mail=new \igblog\mail\mail($ar,"aboArticle");
					$mail->sendNews();
				}catch (\Exception $e){
					echo $e->getMessage();
					die();
				}
			}
		}

		if(isset($_POST["delarticle"])){
			$idparent=$this->data["storage"]->call("delArticle",$articleId);
			header('Location: ?t=sitemenu&m='.$idparent);
		}
	}


	$this->getInclude("adminheader");
	$article=$this->data["storage"]->getcall("getArticle",$_REQUEST["a"]);
	$menu= $this->data["storage"]->getcall("getArticlesMenu",$_REQUEST["m"]);
	if(isset($article["visible"]))
		$checked=$article["visible"];
	else
		$checked=true;

	if(isset($article["content"]))
		$content=$article["content"];
	else
		$content="Text de l'article";

?>
<script type="text/javascript">
	tinymce.init({
		selector: 'textarea',
		language: 'fr_FR',
		height : 500,
		plugins: [
			"advlist autolink lists link image charmap print preview anchor",
			"searchreplace visualblocks code fullscreen",
			"insertdatetime media table contextmenu paste image imagetools wordcount paste"
		],
		toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image insert",
		schema: 'html5',
		paste_data_images: true,
		image_list: 'listImage.php',
		image_advtab: true,
		images_upload_handler: function (blobInfo, success, failure) {
			var xhr, formData;

			xhr = new XMLHttpRequest();
			xhr.withCredentials = false;
			xhr.open('POST', 'uploadImage.php');

			xhr.onload = function() {
				var json;

				if (xhr.status != 200) {
					failure('HTTP Error: ' + xhr.status);
					return;
				}

				json = JSON.parse(xhr.responseText);

				if (!json || typeof json.location != 'string') {
				failure('Invalid JSON: ' + xhr.responseText);
					return;
				}
				try {
					success(json.location);
				}catch(error){
					console.error(error);
				}
			};
			
			var bInfo=blobInfo.blob();

			if(bInfo.name){
				var fname=bInfo.name;
			}else{

				var tname=blobInfo.filename();
				var ext=tname.split('.').pop();
				var d=new Date();
				var a=d.getFullYear();
				var m=d.getMonth() + 1;
				if(m < 10)
					m= "0"+m;
				var j=d.getDate();
				if(j < 10)
					j="0"+j;
				var h=d.getHours();
				if(h < 10)
					h="0"+h;
				var i=d.getMinutes();
				if(i < 10)
					i="0"+i;
				var s=d.getSeconds();
				if(s < 10)
					s="0"+s;
				var ms=d.getMilliseconds();
				if(ms < 10)
					ms ="00"+ms;
				else if(ms < 100)
					ms ="0"+ms;

				var fname = 'img.'+ a.toString() + m.toString() + j.toString() + h.toString() + i.toString() + s.toString() + '.' + ms.toString() + '.' + ext;
			}

			formData = new FormData();
			formData.append('file', blobInfo.blob(),fname);

			xhr.send(formData);
		},
		content_style: "img {max-width: 99%; max-height: 99%; width: auto; height: auto;}",
	});
</script>

	<div>
		Retourner &agrave; <a href="?t=sitemenu&m=<?php echo $menu["uuid"];?>"><?php echo $menu["name"]; ?></a>
	</div>
	<form name="f" method="POST">
		<input type="hidden" name="from" id="from" value="article" />
		<label for="title">Titre</label>
		<input type="text" id="title" name="title" value="<?php echo $article["title"]; ?>"><br />
		<!--<label for="position">Position</label>
		<input type="text" id="position" name="position" value="<?php echo $article["position"]; ?>"><br /> -->
		<label for="visible">Visible</label>
		<input type="checkbox" id="visible" name="visible" value="<?php echo $checked; ?>" <?php if($checked) echo "checked"; ?>/><br />
		<input type="submit" id="updatearticle" name="updatearticle" value="Mettre &agrave; jour" />	<input type="submit" id="delarticle" name="delarticle" value="Supprimer l'article" />
		<textarea id="content" name="content"><?php echo $content; ?></textarea>


	</form>

<?php 
	$this->getInclude("adminfooter");
?>