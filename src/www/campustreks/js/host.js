Vue.component('start-hunt', {
    template: `
    <div class="container">
        <div class="heading">
            <h2>Host a Hunt</h2>
        </div>
        <div class="row" v-for="hunt in hunts" :key="hunt['huntid']">
            <div class="col-md-6 col-lg-4">
                <div class="project-card-no-image">
                    <h3>{{ hunt['name'] }}</h3>
                    <h4>Author: {{ hunt['username'] }}
                    <img v-if="!hunt['verified']" src="img/exeter-logo.png" height="14px" width="14px">
                    </h4>
                    <h4>{{ hunt['description'] }}</h4>
                    <a class="btn btn-outline-primary btn-sm" role="button" @click="startHunt(hunt['huntid'])">Host</a>
                    <div class="tags">High Score: {{ hunt['highscore'] }} - {{ hunt['bestteam'] }}</div>
                </div>
            </div>
        </div>
        <div v-show="hunts.length==0">
            <p>No hunts found. Click <a href="/create.php">here</a> to create a new hunt.</p>
        </div>
    </div>
    `,
    data() {
        return {
            hunts: {}
        }
    },
    mounted() {
        this.getHunts()
    },
    methods: {
        getHunts() {
            $.ajax({
                type: "POST",
                url: "api/query_hunts.php",
                dataType: "json",
                success: (data) => {
                    if (data["status"] === "fail") {
                        //console.log(data)
                    } else if (data["status"] === "success") {
                        this.hunts = data["results"]
                    }
                    
                }
            });
        },
        startHunt(huntID) {
            $.ajax({
                type: "POST",
                url: "api/start_hunt.php",
                data: { huntID: huntID },
                success: (data) => {
                    if (data === "start-hunt-success") {
                        this.$emit("hunt-started")
                        console.log(data)
                    } 
                }
            });
        }
    }
})



Vue.component('hunt-session', {
    props: {
        jsondata: Object,
        gameid: String,
        username: String
    },
    template: `
        <div class="container">
            <section class="portfolio-block">
                <div class="heading">
                    <h2>Active Hunt</h2>
                    <h3>Pin: {{ gameid }}</h3>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group" id="submissions">
                            <div v-for="photo in photosubmission" v-if="currentPhoto == photo.photoID">
                                <div class="card">
                                    <div class="card-header">
                                        <h5>Photo Submissions</h5>
                                    </div>
                                    <div class="card-body align-items-center">
                                        <h5>Team: {{ photo.team }}</h5>
                                        <p>Objective: {{ photo.description }}</p>
                                        <div class="card-img">
                                            <img class="img-fluid shadow" :src='photo.image'>
                                        </div>
                                    </div>
                                    <div>
                                        <form @submit.prevent="submitScore(photo.photoID, photo.team, photo.objective)">
                                        <p>Current Score: {{ photo.score }}</p>
                                            <div class="btn-group" role="group" aria-label="Basic example">
                                                <button type="button" class='btn btn-secondary' @click="switchCurrentPhoto('prev')">&lt;</button>
                                                <input type="number" min="0" max="100" class="input-group" placeholder="Score" v-model.number="newscore" :name="newscore">
                                                <button type="submit" class='btn btn-outline-primary'>Send</button>
                                                <button type="button" class='btn btn-secondary' @click="switchCurrentPhoto('next')">&gt;</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5>Leaderboard</h5>
                            </div>
                            <div class="card-body">
                                <div id="leaderboard" content="no-cache">
                                    <table class="table table-striped">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th scope="col">Rank</th>
                                                <th scope="col">Team</th>
                                                <th scope="col">Score</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="(team, index) in teamscores">
                                                <td>{{ index + 1 }}</td>
                                                <td>{{ team[0] }}</td>
                                                <td>{{ team[1] }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <button class="btn btn-secondary" type="button" @click="$emit('end-hunt')">End Game</button>
                    </div>
                </div>
            </section>
        </div>
    `,
    data() {
        return {
            photosubmission: [],
            currentPhoto: 0,
            teamscores: [],
            newscore: null,
            updateTimeout: null
        }
    },
    mounted() {
        setTimeout(this.updateLeaderboard, 200)
    },
    methods: {
        teamUpdate(photoID, team) {
            if (this.teamscores.length != 0) {

                var newtscores = this.teamscores
                var nscore = this.newscore
                var oldscore = this.photosubmission[photoID]["score"]
                for (t in newtscores) {
                    if (newtscores[t][0] == team) {
                        newtscores[t][1] -= oldscore
                        this.photosubmission[photoID]["score"] = nscore
                        newtscores[t][1] += nscore
                    }
                }

                this.teamscores = newtscores
                this.teamscores.sort(this.sortScores)
            
            } else { 
                this.updateLeaderboard()
            }
        },
        switchCurrentPhoto(dir) {
            if (this.photosubmission.length > 1) {
                var newPhoto = this.currentPhoto;
                var counter = -1
                for (let photo in this.photosubmission) {
                    if (dir == "next" && this.currentPhoto == (photo[0]-1)) {
                        newPhoto = photo[0]
                    } else if (dir == "prev" && this.currentPhoto == (photo[0]+1)) {
                        newPhoto = photo[0]
                    }
                    counter++
                }
                if (dir =="next" && newPhoto == this.currentPhoto) {
                    if (this.currentPhoto == 0) {
                        this.currentPhoto += 1
                    } else {
                        this.currentPhoto = 0
                    }
                } else if (dir == "prev" && newPhoto == this.currentPhoto) {
                    if (this.currentPhoto == counter) {
                        this.currentPhoto -= 1
                    } else {
                        this.currentPhoto = counter
                    }
                } else {
                    this.currentPhoto = newPhoto
                }
            }
        },
        submitScore(photoID, team, objective) {
            clearTimeout(this.updateTimeout)

            this.switchCurrentPhoto('next')
            this.teamUpdate(photoID, team)

            var xhttp = new XMLHttpRequest()
            params = "pin="+this.jsondata["gameinfo"]["gamePin"]+"&team="+team+"&submission="+objective+"&score="+this.newscore
            this.newscore = null
            xhttp.open('POST', 'update_score.php', true)
            xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded")
            xhttp.onreadystatechange = function() {
                if (xhttp.readyState === XMLHttpRequest.DONE) {
                    if (xhttp.status === 200) {
                    } else { //console.log(params) 
                    }
                } else {
                    //console.log(params)
                }
            };
            xhttp.send(params)
            
            this.updateTimeout = setTimeout(this.updateLeaderboard, 1000)
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
            if (this.jsondata["gameinfo"]["master"] != this.username) {
                this.$emit('game-ended')
                return
            }

            var teamlist = this.jsondata["teams"]                
            var newphotosubmission = []
            var counter = 0

            for (let team in teamlist) {
                if (teamlist[team] != "") {
                    var objectivelist = teamlist[team]["objectives"]["photo"]
                    for (let objective in objectivelist) {
                        if (objectivelist[objective]["completed"] === true) {
                            newphotosubmission.push({"photoID": counter, "team": team, "image": objectivelist[objective]["path"], 
                                                    "objective": objective, "score": objectivelist[objective]["score"]})
                            counter++
                        }
                    }
                }
            }
            this.photosubmission = newphotosubmission

            var newtscores = []
            var teamlist = this.jsondata["teams"]
            for (let team in teamlist) {
                if (team != "") {
                    newtscores.push([team, teamlist[team]["teaminfo"]["score"]])
                }
            }

            newtscores.sort(this.sortScores)
            this.teamscores = newtscores
            
            this.updateTimeout = setTimeout(this.updateLeaderboard, 1000)

            //store highscore data globally for end hunt button
            if (typeof this.teamscores[0] != "undefined") {
                this.$emit('best-team', this.teamscores[0][0])
                this.$emit('high-score',this.teamscores[0][1])
            }
        }
    }
})




var host = new Vue({
    el: "#host",
    data: {
        huntstarted: false,
        username: null,
        gameid: null,
        jsondata: {},
        sessionInterval: null,
        bestteam: null,
        highscore: null
    },
    mounted() {
        this.sessionIntervalStart()
    },
    methods: {
        /**
         * Fetches the json data
         * @author James Caddock
         */
        fetchJson() {
            if (this.gameid != null) {
                var reqjson = this.gameid
                var randomString =  Math.random().toString(18).substring(2, 15)
                var safejson = 'hunt_sessions/' + encodeURI(reqjson) + '.json?' + randomString
                fetch(safejson)
                .then(response => response.json())
                .then(data => {
                    this.jsondata = data
                })   
            } else {
                this.jsondata = {}
            }
        },
        sessionIntervalStart() {
            this.checkSession()
            this.sessionInterval = setInterval(this.checkSession, 1000)
        },
        checkSession() {
            $.ajax({
                type: "POST",
                url: "api/check_game.php",
                dataType: "json",
                success: (data) => {
                    if (data["status"] === "fail") {
                        this.huntstarted = false
                        clearInterval(this.sessionInterval)
                    } else if (data["status"] === "success") {
                        if (data["username"] != null && data["gameID"] != null)
                            this.username = data["username"]
                            this.gameid = data["gameID"]
                            this.fetchJson()
                            this.huntstarted = true
                        } console.log(data)
                }
            });
        },
        endHunt() {
            $.ajax({
                type: "POST",
                url: "api/end_hunt.php",
                data: {
                    gameID : this.gameid,
                    teamName : this.bestTeam,
                    highscore : this.highscore
                },
                dataType: "json",
                success: (response) => {
                    if (response['status'] === 'ok') {
                        this.huntstarted = false
                        this.gameid = null
                        clearInterval(this.sessionInterval)
                        this.jsondata = {}
                        this.highscore = null
                        this.bestteam = null
                    } else if (response['status'] === 'error' ) {
                        alert(response['message']);
                        //@TODO consider using custom error box
                    }
                }
            });
        }
    }
})