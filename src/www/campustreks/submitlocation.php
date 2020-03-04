<!DOCTYPE html>
<html>
    <head>
    
    </head>
    <body>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <script>
            var gamePin  = "1234";
            var team = "team2";
            var game = {
                "gameinfo": {
                    "gamePin": "1234",
                    "huntID": "1"
                },
                "teams": {
                    "team0": {
                    "teaminfo": {
                        "teamname": "",
                        "score": "0"
                    },
                    "players": {
                        "player0": {
                        "playername": "User1"
                        },
                        "player1": {
                        "playername": "User2"
                        },
                        "player2": {
                        "playername": "UserThree"
                        }
                    },
                    "objectives": {
                    }
                    },
                    "team1": {
                    "teaminfo": {
                        "teamname": "TeamOne",
                        "score": "10"
                    },
                    "players": {
                        "player0": {
                        "playername": "User1"
                        },
                        "player1": {
                        "playername": "User2"
                        }
                    },
                    "objectives": {
                        "objective0": {
                        "type": "photo",
                        "completed": true,
                        "objectiveId": 1,
                        "path": "photo.png",
                        "score": "10"
                        },
                        "objective1": {
                        "type": "gps",
                        "completed": false,
                        "objectiveId": "3"
                        }
                    }
                    },
                    "team2": {
                    "teaminfo": {
                        "teamname": "Team2",
                        "score": "0"
                    },
                    "players": {
                        "player2": {
                        "playername": "UserThree"
                        }
                    },
                    "objectives": {
                        "objective0": {
                        "type": "gps",
                        "completed": false,
                        "objectiveId": "3"
                        },
                        "objective1": {
                        "type": "photo",
                        "completed": false,
                        "objectiveId": 1,
                        "path": "",
                        "score": "0"
                        }
                    }
                    }
                }
                };
            var objLoc;
            
            console.log(game);
            for(objective in game["teams"][team]["objectives"]){
                if(objective["type"] == "gps" && objective["completed"]){
                    var id = objective["objectiveId"];
                    break;
                }
            }
            $(document).ready(function(){
                $("button").click(function(e){
                    e.preventDefault();
                    $.ajax({
                        type: "POST",
                        url: "getobjectivelocation.php",
                        data: {ID:id},
                        dataType: "json", 
                        success: function(data){
                            objLoc = data
                        }
                    });
                });
            });
            document.getElementById("demo").innerHTML = "Latitude: " + objLoc.Latitude + 
            " Longitude: " + objLoc.Longitude;
        </script>
        <button></button>
        <p id="demo"></p>
        <script src="js/location.js"></script>
    </body>
</html>