	</div>

	<script type="text/javascript">
		blogIg.init();
	</script>
	<div class="footer">
		<div>
			<ul>
<?php
	if($this->data['user']){
		if($isAdmin=\igBlog\fonctions::isAdmin($this->data['user']['mail']))
			echo '<li><a href="./admin">Aller à l\'administration</a></li>';
		echo '<li><a href="./secure/logout.php">Se déconnecter</a></li>';
		
	 }else{
		echo '<li><a href="./secure/">Se connecter</a></li>';
	}

	$d=date('Y');
	if($d == "2018")
		$copyr=$d;
	else
		$copyr="2018 - ".$d

?>
			</ul>
		</div>
		<div class="copyright">
			<div id="div_copyright" style="display:none;">©<?php echo $copyr; ?> Svgta. Tous les droits sont réservés.</div>
			<img id="img_copyright" alt="Copyright Svgta" src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHhtbG5zOnhsaW5rPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5L3hsaW5rIiB3aWR0aD0iMTIwMCIgaGVpZ2h0PSIxMjAwIiB2aWV3Qm94PSIwIDAgMTIwMCAxMjAwIj48Zz48ZyB0cmFuc2Zvcm09InRyYW5zbGF0ZSg2MDAgNjAwKSBzY2FsZSgwLjY5IDAuNjkpIHJvdGF0ZSgwKSB0cmFuc2xhdGUoLTYwMCAtNjAwKSIgc3R5bGU9ImZpbGw6I0ZGRkZGRiI+PHN2ZyBmaWxsPSIjRkZGRkZGIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHhtbG5zOnhsaW5rPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5L3hsaW5rIiB2ZXJzaW9uPSIxLjEiIHg9IjBweCIgeT0iMHB4IiB2aWV3Qm94PSIwIDAgMTAwIDEwMCIgZW5hYmxlLWJhY2tncm91bmQ9Im5ldyAwIDAgMTAwIDEwMCIgeG1sOnNwYWNlPSJwcmVzZXJ2ZSI+PHBhdGggZD0iTTI2LjI0NSw2Ni4xODdjMC4zMS0xNy4wOTcsMTUuMDczLTEzLjUyLDExLjk2Ni0yNC43MWMtOS4xMjMtNy4yNTktMTAuODgxLTE3Ljg3NS0yLjc5OC0yNC44NjggIGMtMC4xNTUtNy4zMDQsNi42ODQtMTEuMDM0LDYuNjg0LTExLjAzNHM0Ljk3Myw0Ljk3NCw0LjUwNyw5Ljc5MWM0LjM1My0xLjM5OSwxMS4xOTEsMy4xMDksMTIuMjc5LDQuNjYzICBjMi4zMzItMy44ODYsMTAuNTY3LTMuNzI5LDEwLjU2Ny0zLjcyOXMyLjY0MywxMC40MTItNC4wNCwxNC4xNDNjLTAuOTMzLDkuMDEzLTcuNzM1LDEzLjUwOC0xMi4xMjMsMTMuODMzICBjLTAuNjcyLDcuNzAzLDExLjc4OSwxMS42MzgsMTAuNDc3LDI1LjU3NWM4LjI2NCw0LjA1MSwxMS42NDIsMTAuMzMsNi42MTgsMTYuODU0Yy03LjcxOSwxMC4wMjEtMjcuODg4LDEwLjcyMS0yOS45OTYsNi4yMTYgIGMtMi4yMDItNC43MDUsMS40MDYtOC45NjgsOS42MDUtNy40OTJjNy41LDEuMzUsMjQuNDMyLTMuOTM2LDExLjY0Mi0xMS4zMTRjLTMuMjgsNC43NTUtNy4wNTEsNy4yMTUtMTUuMzM5LDcuMzA1ICBDMzMuNjQ5LDgxLjU1NiwyNi4wNTgsNzYuNTksMjYuMjQ1LDY2LjE4N3oiPjwvcGF0aD48L3N2Zz48L2c+PC9nPjwvc3ZnPg==" >
		</div>
		<script>
			function hideCR(){
				$("div_copyright").setStyle({display : "none"});
			}
			$("img_copyright").observe('mouseover',function(elm){
				$("div_copyright").setStyle({display : "block"});
			});
			$("img_copyright").observe('mouseout',function(elm){
				hideCR.delay(3);
			});

		</script>
	</div>
</body>
</html>