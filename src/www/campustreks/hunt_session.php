<html>
  <head>
    <title>Host - CampusTreks</title>
    <link rel="stylesheet" href="css/hunt_session_stylesheet.css">
	<?php include('templates/head.php'); ?>
  </head>
  <body>
    <?php 
    if(isset($_GET['sessionID'])){
        $huntSessionID = $_GET['sessionID'];
        $json_data = file_get_contents('hunt_sessions/' . $huntSessionID . '.json');
        $hunt_session_data = json_decode($json_data, true);
    }
    else{
        header('Location: /host.php');
        die();
    }
    ?>
	<!-- Header -->
	<?php include('templates/header.php'); ?>
	<!-- Content -->
    <main class="page host-page">
        <section class="portfolio-block project-no-images">
            <div class="container">
                <div class="heading">
                    <h2>Game Pin</h2>
                    <h3><?php echo $hunt_session_data['gamePIN']; ?></h3>
                </div>
                <div class="content">
                    <div id="submissions">submissions placeholder</div>
                    <div id="leaderboard">
                        <?php include('templates/leaderboard.php');?>
                    </div>
                    <button>End Game</button>
                </div>
            </div>
        </section>
    </main>
	<!-- Footer -->
	<?php include('templates/footer.php'); ?>
  </body>
</html>
