<?php 
	$this->getInclude("adminheader"); 
?>

	<h1>Bonjour <?php echo $this->data['user']['cn']?></h1>
	<div><img src="<?php echo $this->data['user']['image']?>" /></div>

	


<?php 
	$this->getInclude("adminfooter");
?>