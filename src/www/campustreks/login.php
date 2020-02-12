<html>
  <head>
    <title>Home - CampusTreks</title>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<link rel="stylesheet" href="/css/stylesheet.css" />
  <link rel="stylesheet" href="/css/login_stylesheet.css" />
	<link rel="icon" type="image/png" href="/img/favicon-32x32.png" sizes="32x32" />
	<link rel="icon" type="image/png" href="/img/favicon-16x16.png" sizes="16x16" />
  </head>
  <?php
  // Redirect to home.php if already logged in
  include "checklogin.php";
  if (CheckLogin()) {
    header("location:home.php");
  }
  ?>
  <body>
    <div>
      <form action="/loginhandler.php" method="post">
        <div class="login-box">
          <div class="login-content">
            <input type="email" name="email" placeholder="E-mail adress">
          </div>
          <div class="login-content">
            <input type="password" name="password" placeholder="Password">
          </div>
          <button class="login-submit" type="submit">Log In</button>
          <br>
          <?php if (isset($_GET["loginFailed"])) echo "<p class='error'>E-mail or password incorrect</p>"; ?>
        </div>
      </form>
    </div>
  </body>
</html>
