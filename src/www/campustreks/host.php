<?php $publichtml = "c:/wamp64/www/campustreks/"; ?>
<html>
  <head>
    <title>Host - CampusTreks</title>
	<?php include($publichtml.'templates/head.php'); ?>
  </head>
  <body>
	<!-- Header -->
	<?php include($publichtml.'templates/header.php'); ?>
	<!-- Content -->
	<div class="content">
	  <div>
	  <form action='post.php' method='POST'>
	    <div class="sidebar">
		  <h4>Select Hunt</h4>
		  <input type="radio" name="gender" value="male" checked>Hunt 1
		  <br>
		  <input type="radio" name="gender" value="female">Hunt 2
		  <br>
		  <input type="radio" name="gender" value="other">Hunt 3 
		  <br> 
		</div>
	    <div class="sidebar-content">
		  <h4>Launch a Hunt Session</h4>
		  <input type="submit" value="Create New">
		  <h4>Control Panel</h4>
		  <div style="height:160px;background-color:#52AA8A;margin:8;padding:8">
		    <h4>Players Joined</h4>
			<li>HF</li>
			<li>HF</li>
			<li>HF</li>
			<br>
		    <input type="submit" value="Start Game">
		  </div>
	    </div>
	  </form> 
	  
	  </div>
	</div>
	<!-- Footer -->
	<?php include($publichtml.'templates/footer.php'); ?>
  </body>
</html>
