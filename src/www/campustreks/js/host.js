var host = new Vue({
    el: "#host",
    template: `
    <main class="page host-page">
        <section class="portfolio-block project-no-images">
            <div class="container">
                <div class="heading">
                    <h2>Game Pin</h2>
                    <h3><div id="pin"><?php echo $huntSessionData['gameinfo']['gamePin']; ?></div></h3>
                </div>
                <div class="content">
                    <div id="submissions">
                        <script type="text/javascript">
                            function submitScore(){
                                var score = this.parentElement.childNodes[2].value;

                                var xhttp = new XMLHttpRequest();
                                params = "pin="+document.getElementById("pin").innerHTML+"&team="+this.parentElement.childNodes[0].innerHTML+"&submission="+this.parentElement.id+"&score="+score;
                                xhttp.open('POST', 'update_score.php', true);
                                xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                                xhttp.onreadystatechange = function() {
                                    if (xhttp.readyState === XMLHttpRequest.DONE) {
                                        if (xhttp.status === 200) {
                                            var response = xhttp.response;
                                            console.log(response);
                                        }
                                    }
                                };
                                xhttp.send(params);
                                console.log(score);
                            }

                            function refresh(){
                                document.getElementById("submissions").innerHTML = "";
                                var pin = document.getElementById("pin").innerHTML;
                                var xhttp = new XMLHttpRequest();
                                xhttp.onreadystatechange = function() {
                                    if (this.readyState == 4 && this.status == 200) {
                                        var json = this.responseText;
                                        var huntSessionData = JSON.parse(json);
                                        var teams = Object.keys(huntSessionData["teams"]);
                                        for (var team of teams) {
                                            objectives = Object.keys(huntSessionData["teams"][team]["objectives"]);
                                            for (var objective of objectives) {
                                                objective_data = huntSessionData["teams"][team]["objectives"][objective];
                                                if (objective_data["type"] == "photo"){
                                                    var submission = document.createElement("div");
                                                    submission.id = objective;
                                                    submission.className = "submission";

                                                    var teamName = document.createElement("h4");
                                                    teamName.innerHTML = team;
                                                    submission.appendChild(teamName);

                                                    var submissionImage = document.createElement("img")
                                                    submissionImage.src = objective_data["path"];
                                                    submission.appendChild(submissionImage);

                                                    var score = document.createElement("input");
                                                    score.type = "number";
                                                    score.value = objective_data["score"];
                                                    submission.appendChild(score);

                                                    var submit = document.createElement("button");
                                                    submit.type = "button";
                                                    submit.innerHTML = "Submit"
                                                    submit.onclick = submitScore;
                                                    submission.appendChild(submit);

                                                    document.getElementById("submissions").appendChild(submission);
                                                }
                                            }
                                        }
                                    }
                                };
                                xhttp.open("GET", "hunt_sessions/"+pin+".json", true);
                                xhttp.setRequestHeader('pragma', 'no-cache');
                                xhttp.setRequestHeader('cache-control', 'no-cache');
                                xhttp.send();
                            }
                            refresh();
                            setInterval(function(){refresh()}, 5000)
                        </script>
                    </div>
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

                                },
                                beforeMount(){
                                    setInterval(function(){app.updateLeaderboard()}, 1000);
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
    `,
    methods: {

    }
})