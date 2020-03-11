<!DOCTYPE html>
<html lang="en" dir="ltr">
    <head>
        <meta name="author" content = "Marek Tancak">
            <meta name="contributors" content = "Jakub Kwak">
        <title>Host - CampusTreks</title>
        <?php include('templates/head.php'); ?>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <script>
            var gameID;
            var bestTeam;
            var highscore;

            /**
             * Function to end a hunt session using the end_hunt api
             */
            function endHunt() {
                $.ajax({
                    type: "POST",
                    url: "api/end_hunt.php",
                    data: {
                        gameID : gameID,
                        teamName : bestTeam,
                        highscore : highscore
                    },
                    success: function (response) {
                        response = $.parseJSON(response);
                        if (response['status'] === 'ok') {
                            window.location.href = 'host.php'
                        } else if (response['status'] === 'error' ) {
                            alert(response['message']);
                            //@TODO consider using custom error box
                        }
                    }
                });
            }
        </script>
    </head>
    <body>
        <?php
        include "checklogin.php";
        if (!CheckLogin()) {
            header("location:login.php");
        }
        if(isset($_GET['sessionID'])){
            // Get the hunt data 
            $huntSessionID = $_GET['sessionID'];
            $json_data = file_get_contents('hunt_sessions/' . $huntSessionID . '.json');
            $huntSessionData = json_decode($json_data, true);
            // Check if the user is the game master for the session
            if ($huntSessionData["gameinfo"]["master"] != $_SESSION["username"]) {
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
            <div class="container">
                <section class="portfolio-block">
                    <div class="heading">
                        <h2>Active Hunt</h2>
                         <h3>
                            <?php echo "Pin: ", $huntSessionData['gameinfo']['gamePin'];?>
                         </h3>
                         <button class="btn btn-primary" type="button" onclick="endHunt()">End Game</button>
                    </div>
                    <div id="host">
                        <submissions-leaderboard></submissions-leaderboard>
                    </div>
                </section>
            </div>
        </main>
        <!-- Footer -->
        <?php include('templates/footer.php'); ?>
    </body>
</html>
<script src="https://unpkg.com/vue"></script>
<script src="js/host.js"></script>