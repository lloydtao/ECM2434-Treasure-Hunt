<!DOCTYPE html>
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="/css/mobile.css">
    <link rel="stylesheet" href="/css/stylesheet.css">

    <script>
        function displayTeams()
        {
            document.getElementById("test").style.display = 'none';
            document.getElementById("team-table").style.display = 'block';
        }

        function createTeam()
        {
            document.getElementById("team-table").style.display = 'none';
            document.getElementById("create-form").style.display = 'block';
        }

        function showCurrentTeam(x)
        {
            var myTable = document.getElementById('tableData');
            var teamName1 = myTable.rows.item(x.rowIndex).cells;

            document.getElementById("team").innerHTML = "Current team is: " + teamName1.item(0).innerHTML + "<br> The team members are: " + teamName1.item(2).innerHTML;
            document.getElementById("currentTeam").style.display = 'block';
        }

        function leaveTeam()
        {
            document.getElementById("currentTeam").style.display = 'none';
        }

    </script>
  </head>

  <body>
    <form id="test" action='play.php' method='POST'>
      <div class='play-box'>
        <div class='play-content'>
          <input type='text' name='pin' maxlength='8' size='12' placeholder='Pin'>
        </div>
        <div class='play-content'>
          <input type='text' name='nickname' maxlength='15' minlength='2' size='18' placeholder='Nickname'>
        </div>
        <button id="test2" class='play-submit' type='button' onclick="displayTeams()">Play</button>
      </div>
    </form>

    <div id='team-table' style='display:none'>
        <table id='tableData'>
            <tr>
                <th>Team Name</th>
                <th>No. Players</th>
                <th>Players</th>
                <th>Join</th>
            </tr>
            <tr onclick="showCurrentTeam(this)">
                <td>Team1</td>
                <td>4</td>
                <td>Player1, Player2, Player3, Player4</td>
                <td><form><input type="button" value="Join"></form></td>
            </tr>
            <tr onclick="showCurrentTeam(this)">
                <td>Hackers</td>
                <td>3</td>
                <td>Alice, Bob, Eve</td>
                <td><form><input type="button" value="Join"></form></td>
            </tr>
        </table>

        <form><input type="button" value="Create Team" onclick="createTeam()"></form>
    </div>

    <div id='create-form' style='display:none'>
        <form class="" action="" method="post">
            Team name: <br>
            <input type="text" name="teamName" value="">
            <input type="submit" name="createButton" value="Create team">
        </form>
    </div>

    <div id='currentTeam' style="display:none">
        <p id="team"></p>
        <button type="button" onclick="leaveTeam()">Leave team</button>
        <button type="button">Play game</button>
    </div>
  </body>
</html>
