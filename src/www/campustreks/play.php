<!DOCTYPE html>
<head>
    <meta name="author" content="James Caddock">
    <meta name="contributors" content="Jakub Kwak, Joseph Lintern">
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="/css/mobile.css"/>
    <link rel="stylesheet" href="/css/stylesheet.css"/>

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
         * Hide the current team info
         * @author Joseph Lintern
         */
        function leaveTeam() {
            document.getElementById("currentTeam").style.display = 'none';
        }
    </script>
</head>

<body>
<div id="game-join">
    <form id='join-form' method='POST'>
        <div class='play-box'>
            <div class='play-content'>
                <input type='text' name='pin' maxlength='4' size='4' placeholder='Pin'>
                <p id='pin-error' style="display: none">Game not found</p>
            </div>
            <div class='play-content'>
                <input type='text' name='nickname' maxlength='15' minlength='2' size='18' placeholder='Nickname'>
                <p id="name-error" style="display: none">Nickname taken</p>
            </div>
            <button class='play-submit' name='submit' type='submit'>Play</button>
            <p id="form-error" style="display: none">Please fill in all fields</p>
        </div>
    </form>
</div>
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
            <td>
                <form><input type="button" value="Join"></form>
            </td>
        </tr>
        <tr onclick="showCurrentTeam(this)">
            <td>Hackers</td>
            <td>3</td>
            <td>Alice, Bob, Eve</td>
            <td>
                <form><input type="button" value="Join"></form>
            </td>
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
