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
            $("#join-form").submit(function (e) {
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
            });

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
	    <div id="game-join">
			<form id='join-form' method='POST'>
			  <div class='container'>
				<div class='form-group'>
				  <input class="form-control" type='text' name='pin' maxlength='4' size='12' placeholder='Pin'>
				  <p id='pin-error' style="display: none">Game not found</p>
				</div>
				<div class='form-group'>
				  <input class="form-control" type='text' name='nickname' id='nickname' maxlength='15' minlength='2' size='18' placeholder='Nickname'>
				  <p id="name-error" style="display: none">Nickname taken</p>
				</div>
				<button class='btn btn-outline-primary' type='submit'>Play</button>
				<p id="form-error" style="display: none">Please fill in all fields</p>
			  </div>
			</form>
		</div>
	
	    <div class="container">
			<div id='team-table' class='form-group' style='display:none'>
				<table id='tableData'>
					<tr>
						<th>Team Name</th>
						<th>No. Players</th>
						<th>Players</th>
						<th>Join</th>
					</tr>

          <?php 
          teamDisplay();
         ?>

				</table>

				<form><input type="button" class='btn btn-outline-primary' value="Create Team" onclick="createTeam()">
        <form id="refresh" method="POST"><input type="button" class='btn btn-outline-primary' value="Refresh"></form>
        </form>
			</div>

			<div id='create-form' class='form-group' style='display:none'>
				<form class="" id="createteam" method="post">
					Team name: <br>
					<input type="text" name="teamName" value="">
					<input type="submit" class='btn btn-outline-primary' name="createButton" value="Create team">
				</form>
			</div>

			<div id='currentTeam' class='form-group' style="display:none">
				<p id="team"></p>
				<button type="button" class='btn btn-outline-primary' onclick="leaveTeam()">Leave team</button>
				<button type="button" class='btn btn-outline-primary'>Play game</button>
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