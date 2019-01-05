<?php
	$conf=$this->data["storage"]->getcall("getGeneralConf");
	$this->getInclude("adminheader");
	$img=$this->data["storage"]->getcall("getFile",$_REQUEST['img']);

	if(isset($_POST) and isset($_POST['subName']) AND $_POST['subName']){
		$img['title']=$_POST['imgname'].'.'.$_POST['ext'];
		$this->data["storage"]->call("updateFile",$img);
	}

	if(isset($_POST) and isset($_POST['delName']) AND $_POST['delName']){
		print_r($this->data["storage"]->call("delFile",$img));
		header('Location: ?t=images');
	}


	$img=$this->data["storage"]->getcall("getFile",$_REQUEST['img']);
	$imgInfo=pathinfo($img['title']);


?>
	<div class="imgDetail">
		<div>
			<a href="<?php echo $img['view'];?>" target="blogImgdetail" /><img src="<?php echo $img['view'];?>" /></a>
		</div>
		<div>
			<form name="f" method="POST">
				<label for="imgname"> Nom de l'image : </label>
				<input type="text" id="imgname" name="imgname" value="<?php echo $imgInfo['filename'];?>">
				<input type="submit" value="Changer le nom de l'image" name="subName">
				<input type="submit" value="Supprimer l'image" name="delName" class="del">
				<input type="hidden" name="ext" value="<?php echo $imgInfo['extension'];?>">
			</form>
		</div>
	</div>


<?php 
	$this->getInclude("adminfooter");
?>