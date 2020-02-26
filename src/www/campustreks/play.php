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
                            $("#game-join").css("display", "none");
                            //display team select
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

</div>
</body>
</html>
