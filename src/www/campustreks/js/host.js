Vue.component('submissions', {
    template: `
        <div class="content">
            <div id="submissions">
                <div v-for="photo in photosubmission">
                    <h4>{{ photo[0] }}</h4>
                    <img class="img-fluid" :src='photo[1]'>
                    <form @submit.prevent="submitScore(photo[0], photo[2], photo[3])">
                        <input type="number" name="p" value="">
                        <button type="submit" class='btn btn-outline-primary'>Submit</button>
                    </form>
                </div>
            </div>
        </div>
    `,
    data() {
        return {
            photosubmission: [],
            jsondata: []
        }
    },
    mounted() {
        this.fetchJson()
    },
    methods: {
        /**
         * Fetches the json data
         * @author James Caddock
         */
        fetchJson() {
            safejson = './hunt_sessions/LVTY.json'
            console.log(safejson)
            fetch(safejson)
            .then(response => response.json())
            .then(data => {
                var teamlist = data["teams"]
                this.jsondata = data
                this.photosubmission = []
                console.log(teamlist)
                for (let team in teamlist) {
                    console.log(teamlist[team])
                    console.log(team)
                    var objectivelist = teamlist[team]["objectives"]["photo"]
                    for (let objective in objectivelist) {
                        console.log(objective)
                        if (objectivelist[objective]["completed"] === true) {
                            this.photosubmission.push([team, objectivelist[objective]["path"], objective, objectivelist[objective]["score"]])
                        }
                    }
                }      
                console.log(this.photosubmission[0])
            })            
        },
        submitScore(team, objective, score) {
            var xhttp = new XMLHttpRequest();
            params = "pin="+this.jsondata["gameinfo"]["gamePin"]+"&team="+team+"&submission="+objective+"&score="+score
            xhttp.open('POST', 'update_score.php', true);
            xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhttp.onreadystatechange = function() {
                if (xhttp.readyState === XMLHttpRequest.DONE) {
                    if (xhttp.status === 200) {
                        var response = xhttp.response;
                        console.log(response);
                    } else { console.log(params) }
                } else {
                    console.log(params)
                }
            };
            xhttp.send(params);
        },
        refresh() {
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
                            if (objective_data["type"] == "photo" && objective_data["completed"] == true){
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
    }
})







var host = new Vue({
    el: "#host",
    methods: {

    }
})