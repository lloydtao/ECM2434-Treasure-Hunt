Vue.component('start-hunt', {
    template: `
    <div class="container">
        <div class="heading">
            <h2>Host a Hunt</h2>
        </div>
        <div class="row" v-for="hunt in hunts" :key="hunt.HuntID">
            <div class="col-md-6 col-lg-4">
                <div class="project-card-no-image">
                    <h3>hunt.name</h3>
                    <h4>Author: hunt.username
                    <img v-if="hunt.verified" src="img/exeter-logo.png" height="14px" width="14px">
                    </h4>
                    <h4>hunt.description</h4>
                    <a class="btn btn-outline-primary btn-sm" role="button" href="#" @click=startHunt(hunt.HuntID)>Host</a>
                    <div class="tags">High Score: hunt.highscore</div>
                </div>
            </div>
        }
        } else {
            echo 'No hunts found. Click <a href="/create.php">here</a> to create a new hunt.<br>';
        }
        </div>
    </div>
    `,
    data() {
        return {
            hunts: {}
        }
    },

})



Vue.component('submissions-leaderboard', {
    template: `
        <div>
            <div class="form-group" id="submissions">
                <div v-for="photo in photosubmission" v-if="currentPhoto == photo.photoID">
                    <h4>{{ photo.team }}</h4>
                    <img class="img-fluid" :src='photo.image'>

                    <div>
                        <form @submit.prevent="submitScore(photo.photoID, photo.team, photo.objective)" style="margin-left: -40px">
                            <button type="button" class='btn btn-outline-primary' @click="switchCurrentPhoto('prev')">Previous</button>
                            <input type="number" v-model.number="newscore" :name="newscore" required style="width:40%;">
                            <button type="submit" class='btn btn-outline-primary'>Submit</button>
                            <button type="button" class='btn btn-outline-primary' @click="switchCurrentPhoto('next')">Next</button>
                        </form>
                    </div>
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