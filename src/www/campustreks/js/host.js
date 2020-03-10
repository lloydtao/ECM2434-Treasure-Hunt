Vue.component('submissions-leaderboard', {
    template: `
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
                                <p>Objective: {{ photo.objective }}</p>
                                <div class="card-img">
                                    <img class="img-fluid shadow" :src='photo.image'>
                                </div>
                            </div>
                            <div>
                                <form @submit.prevent="submitScore(photo.photoID, photo.team, photo.objective)">
                                <p>Current Score: {{ photo.score }}</p>
                                    <div class="btn-group" role="group" aria-label="Basic example">
                                        <button type="button" class='btn btn-secondary' @click="switchCurrentPhoto('prev')">&lt;</button>
                                        <input type="number" class="input-group" placeholder="Score" v-model.number="newscore" :name="newscore">
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
        </div>
    `,
    data() {
        return {
            photosubmission: [],
            currentPhoto: 0,
            jsondata: [],
            teamscores: [],
            newscore: null,
            gameID: null,
            updateTimeout: null
        }
    },
    beforeMount() {
        this.updateLeaderboard()
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
            
            this.updateTimeout = setTimeout(this.updateLeaderboard, 10000)
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
            if (this.gameID === null) {
                url_string = window.location.href
                url = new URL(url_string)
                this.gameID = url.searchParams.get("sessionID")
            }

            randomString =  Math.random().toString(18).substring(2, 15)
            safejson = './hunt_sessions/'+this.gameID+'.json?' + randomString

            fetch(safejson)
            .then(response => response.json())
            .then(data => {
                var teamlist = data["teams"]                
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
                this.jsondata = data
            })   

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
        }
    }
})




var host = new Vue({
    el: "#host",
    methods: {

    }
})