<?php session_start();?>

<!DOCTYPE html>
  <head>
  <meta name="author" content = "James Caddock">
	<meta name="contributors" content="Jakub Kwak, Joseph Lintern">
    <?php include('templates/head.php'); ?>
    <!--<link rel="stylesheet" href="css/mobile.css">-->
  
    <script src="js/jquery-3.4.1.min.js"></script>
    <script>
        $(document).ready(function () {
            /**
             * use ajax to submit the form asynchronously
             * @author Jakub Kwak
             */
            /**$("#join-form").submit(function (e) {
                e.preventDefault();
                $("#pin-error").css("display", "none");
                $("#name-error").css("display", "none");
                $("#form-error").css("display", "none");
                $.ajax({
                    type: "POST",
                    url: "joingame.php",
                    data: $(this).serialize(),
                    success: function (data) {
                        if (data === "join-success") {
                            displayTeams();
                        } else if (data === "pin-error") {
                            $("#pin-error").css("display", "block");
                        } else if (data === "name-error") {
                            $("#name-error").css("display", "block");
                        } else if (data === "form-error") {
                            $("#form-error").css("display", "block");
                        }
                    }
                });
            }); */

            /**
             * @author James Caddock
             */
            $("#createteam").submit(function (e) {
                e.preventDefault();
                $.ajax({
                    type: "POST",
                    url: "createteam.php",
                    data: $(this).serialize(),
                    success: function (data) {
                        if (data === "create-success") {
                            updateTeams();
                        }
                    }
                });
            });
        });

        /**
         * Hide the pin form and display team list
         * @author Joseph Lintern
         */
        function displayTeams() {
            document.getElementById("game-join").style.display = 'none';
            document.getElementById("team-table").style.display = 'block';
        }

        /**
         * Hide the team list and display create team form
         * @author Joseph Lintern
         */
        function createTeam() {
            document.getElementById("team-table").style.display = 'none';
            document.getElementById("create-form").style.display = 'block';
        }

        /**
         * Show the current team info
         * @author Joseph Lintern
         */
        function showCurrentTeam(x) {
            var myTable = document.getElementById('tableData');
            var teamName1 = myTable.rows.item(x.rowIndex).cells;

            document.getElementById("team").innerHTML = "Current team is: " + teamName1.item(0).innerHTML + "<br> The team members are: " + teamName1.item(2).innerHTML;
            document.getElementById("currentTeam").style.display = 'block';
        }

        /**
         * Show the teams
         * @author James Caddock
         */
        function updateTeams() {
          document.getElementById("team-table").style.display = 'block';
          document.getElementById("create-form").style.display = 'none';
        }

        /**
         * Hide the current team info
         * @author Joseph Lintern
         */
        function leaveTeam() {
            document.getElementById("currentTeam").style.display = 'none';
        }
    </script>
  </head>

  <body>
    <main class="page">
      <section class="portfolio-block hire-me">
        <div id="play">
          <game-start :insession="insession"></game-start>
        </div>
      </div>
    </section>
    </main>
  </body>
</html>

<?php 

function teamDisplay()
{
  $pin = $_SESSION["gameID"];
  //read and parse hunt json
  $filename = './hunt_sessions/' . $pin . '.json';
  $jsonString = file_get_contents($filename);
  $parsedJson = json_decode($jsonString, true);

  $teamList = $parsedJson["teams"];

  echo '<div id = "tableteams">';
  foreach ($teamList as $key => $value) {
    $t = $key;

    if (!($t == "")) {
      $playerList = $value["players"];
      $playerCount = count($playerList);
      echo '<tr onclick="showCurrentTeam(this)">';
      echo '<td>'. $t .'</td>';
      echo '<td>' . $playerCount . '</td>';
      echo '<td>';
      $counter = 0;
      foreach ($playerList as $player) {
        $counter += 1;
        echo $player;
        if (!($counter == $playerCount)) { 
          echo ', ';
        }
      }

      echo '</td>';
      echo '<td>';
      echo '<form action="jointeam.php" method="POST">';
      echo '<input type="hidden" name="tableteam" value="'.$t.'">';
      echo '<input type="submit" class="btn btn-outline-primary" value="Join"></form>';
      echo '</td>';
      echo '</tr>';
    }
  }
  echo '</div>';
}
?>

<script src="https://unpkg.com/vue"></script>
<script src="js/play.js"></script>