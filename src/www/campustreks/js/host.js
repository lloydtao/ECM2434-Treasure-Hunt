Vue.component('submissions-leaderboard', {
    template: `
        <div>
            <div class="form-group" id="submissions">
                <div v-for="photo in photosubmission">
                    <h4>{{ photo[0] }}</h4>
                    <img class="img-fluid" :src='photo[1]' height="150" width="150">
                    <form @submit.prevent="submitScore(photo[0], photo[2])" style="width:50%; height:10%; margin-right:50px; margin-top:-90px">
                        <input type="number" v-model="newscore" :name="newscore" required style="width:40%;">
                        <button type="submit" class='btn btn-outline-primary'>Submit</button>
                    </form>
                </div>
            </div>

            <div id="leaderboard" content="no-cache">
                <li v-for="team in teamscores">
                    {{ team[0] }}<br>
                    {{ team[1] }}
                </li>
            </div>
        </div>
    `,
    data() {
        return {
            photosubmission: [],
            jsondata: [],
            teamscores: [],
            newscore: null,
            gameID: null
        }
    },
    mounted() {
        this.fetchJson()
        setInterval(this.updateLeaderboard, 1000)
    },
    methods: {
        /**
         * Fetches the json data
         * @author James Caddock
         */
        fetchJson() {
            if (this.gameID === null) {
                url_string = window.location.href
                url = new URL(url_string)
                this.gameID = url.searchParams.get("sessionID")
            }
            safejson = './hunt_sessions/'+this.gameID+'.json'
            console.log(safejson)
            fetch(safejson)
            .then(response => response.json())
            .then(data => {
                var teamlist = data["teams"]                
                var newphotosubmission = []

                for (let team in teamlist) {
                    var objectivelist = teamlist[team]["objectives"]["photo"]
                    for (let objective in objectivelist) {
                        console.log(objectivelist[objective])
                        if (objectivelist[objective]["completed"] === true) {
                            newphotosubmission.push([team, objectivelist[objective]["path"], objective])
                        }
                    }
                }
                this.photosubmission = newphotosubmission 
                this.jsondata = data     
            })            
        },
        submitScore(team, objective) {
            if (this.newscore != null) {
                var xhttp = new XMLHttpRequest()
                params = "pin="+this.jsondata["gameinfo"]["gamePin"]+"&team="+team+"&submission="+objective+"&score="+this.newscore
                this.newscore = null
                xhttp.open('POST', 'update_score.php', true)
                xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded")
                xhttp.onreadystatechange = function() {
                    if (xhttp.readyState === XMLHttpRequest.DONE) {
                        if (xhttp.status === 200) {
                            var response = xhttp.response
                            console.log(response)
                        } else { console.log(params) }
                    } else {
                        console.log(params)
                    }
                };
                xhttp.send(params)
            }
        },
        sortScores(a, b) {
            if (a[1] === b[1]) {
                return 0;
            }
            else {
                return (a[1] > b[1]) ? -1 : 1;
            }
        },
        updateLeaderboard(){
            this.fetchJson()

            var newscores = []
            var teamlist = this.jsondata["teams"]
            for (let team in teamlist) {
                if (teamlist[team] != "") {
                    newscores.push([team, teamlist[team]["teaminfo"]["score"]])
                }
            }

            newscores.sort(this.sortScores)
            this.teamscores = newscores
        }
    }
})




var host = new Vue({
    el: "#host",
    methods: {

    }
})