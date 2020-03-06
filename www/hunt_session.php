<html>
  <head>
    <meta name="author" content = "Marek Tancak">
      <meta name="contributors" content = "Jakub Kwak">
    <title>Host - CampusTreks</title>
    <link rel="stylesheet" href="css/hunt_session_stylesheet.css">
    <?php include('templates/head.php'); ?>
  </head>
  <body>
    <?php
    include "checklogin.php";
    if (!CheckLogin()) {
        header("location:login.php");
    }


    if(isset($_GET['sessionID'])){
        $huntSessionID = $_GET['sessionID'];
        $json_data = file_get_contents('hunt_sessions/' . $huntSessionID . '.json');
        $hunt_session_data = json_decode($json_data, true);
        if ($hunt_session_data["gameinfo"]["master"] != $_SESSION["username"]) {
            header('Location: /host.php');
            die();
        }
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
                    <h3><?php echo $hunt_session_data['gameinfo']['gamePin']; ?></h3>
                </div>
                <div class="content">
                    <div id="submissions"></div>
                        <?php
                            if(isset($_GET['sessionID'])){
                                $huntSessionID = $_GET['sessionID'];
                                $json_data = file_get_contents('hunt_sessions/' . $huntSessionID . '.json');
                                $hunt_session_data = json_decode($json_data, true);
                            }
                            $teams = array_values($hunt_session_data['teams']);
                            for ($i=1; $i<count($teams); $i++){
                                $submissions = $teams[$i]['objectives'];
                                for ($j=1; $j<count($submissions)+1; $j++){
                                    if ($submissions[$j]["type"]=="photo"){
                                        echo '<img src="'.$submissions[$i]["path"].'">';
                                        echo '<br>';
                                    }
                                } 
                            }
                        ?>
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