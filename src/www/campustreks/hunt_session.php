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
        $huntSessionData = json_decode($json_data, true);
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
    <div id="host">
        <main class="page host-page">
            <section class="portfolio-block project-no-images">
                <div class="container">
                    <div class="heading">
                        <h2>Game Pin</h2>
                        <h3><div id="pin"><?php echo $huntSessionData['gameinfo']['gamePin']; ?></div></h3>
                    </div>
                    
                    <submissions></submissions>
                        <div id="leaderboard" content="no-cache">

                            <li v-for="team in teamScores">
                                {{ team[0] }}<br>
                                {{ team[1] }}
                            </li>
                            <script src="https://cdn.jsdelivr.net/npm/vue"></script>
                            <script type="text/javascript">
                                var app = new Vue({
                                    el: '#leaderboard',
                                    data: {
                                        teamScores: [],
                                        weatherDataList: [1,2,3],
                                        huntData: {}
                                    },
                                    methods: {
                                        updateLeaderboard(){
                                            function sortScores(a, b) {
                                                if (a[1] === b[1]) {
                                                    return 0;
                                                }
                                                else {
                                                    return (a[1] > b[1]) ? -1 : 1;
                                                }
                                            }
                                            var headers = new Headers();
                                            headers.append('pragma', 'no-cache');
                                            headers.append('cache-control', 'no-cache');

                                            var init = {
                                            method: 'GET',
                                            headers: headers,
                                            };

                                            fetch("hunt_sessions/"+document.getElementById("pin").innerHTML+".json",init)
                                            .then(response => response.text())
                                            .then((response) => {
                                                var json = response;
                                                this.teamScores = [];
                                                if(json != ''){
                                                    var huntSessionData = JSON.parse(json);
                                                    var teams = Object.keys(huntSessionData["teams"]);
                                                    for (var team of teams){
                                                        if(team!=''){
                                                            this.teamScores.push([team, huntSessionData["teams"][team]["teamInfo"]["score"]]);
                                                        }
                                                    }

                                                    this.teamScores.sort(sortScores);
                                                }
                                            })

                                        }

                                    }
                                });
                            </script>
                        </div>
                        <button class="btn btn-primary" type="button">End Game</button>
                        <button class="btn btn-primary" type="button" onclick = refresh()>Refresh</button>
                    </div>
                </div>
            </section>
        </main>
    <div>
    <!-- Footer -->
    <?php include('templates/footer.php'); ?>

</body>

</html>

<script src="js/jquery-3.4.1.min.js"></script>
<script src="https://unpkg.com/vue"></script>
<script src="js/host.js"></script>
