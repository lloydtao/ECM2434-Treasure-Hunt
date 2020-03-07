<?php session_start();?>

<!DOCTYPE html>
  <head>
  <meta name="author" content = "James Caddock">
	<meta name="contributors" content="Jakub Kwak, Joseph Lintern">
    <?php include('templates/head.php'); ?>
  </head>

  <body>
    <main class="page">
      <section class="portfolio-block hire-me">
        <div id="play">
          <game-start :insession="insession" @has-session="hasSession" @no-session="noSession"></game-start>
        </div>
      </div>
    </section>
    </main>
  </body>
</html>


<script src="js/jquery-3.4.1.min.js"></script>
<script src="https://unpkg.com/vue"></script>
<script src="js/play.js"></script>