<?php
	$conf=$this->data["storage"]->getcall("getGeneralConf");
	$this->getInclude("adminheader");

	$imageListe=$this->data["storage"]->getcall("getAllFile");

?>
	<div class="imageListe">
<?php 
	foreach($imageListe as $image){
		echo '<div>';
		echo '<a href="?t=imgProperties&img='.$image->uuid.'" target="blogImage"><img src="'.$image->value.'" alt="'.$image->title.'" /></a> ';
		echo '</div>';
	}
?>
	</div>

<?php 
	$this->getInclude("adminfooter");
?>