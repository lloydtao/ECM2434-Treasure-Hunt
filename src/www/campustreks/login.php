<html>
  <head>
    <title>Login - CampusTreks</title>
	<?php include('templates/head.php'); ?>
  </head>
  <?php
  // Redirect to home.php if already logged in
  include "checklogin.php";
  if (CheckLogin()) {
    header("location:create.php");
  }
  ?>
  <body>
    <!-- Header -->
	<?php include('templates/header.php'); ?>
	<!-- Content -->
     <main class="page login-page">
        <section class="portfolio-block contact">
            <div class="container">
                <div class="heading">
                    <h2>Log In</h2>
                </div>
                <form action="/loginhandler.php" method="post">
                    <div class="form-group">
						<label for="email">Email</label>
						<input class="form-control item" type="text" id="email">
					</div>
                    <div class="form-group">
						<label for="password">Password</label>
						<input class="form-control item" type="password" id="password">
					</div>
					<button class="btn btn-primary btn-block btn-lg" type="submit">Log In</button>
					<br>
					<?php 
						if (isset($_GET["loginFailed"])) echo "<p class='error'>Incorrect login details.</p>"; 
					?>
                </form>
            </div>
        </section>
    </main>
	<!-- Footer -->
	<?php include('templates/footer.php'); ?>
  </body>
</html>
