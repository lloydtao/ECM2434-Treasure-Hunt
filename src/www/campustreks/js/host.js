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
                <li v-for="team in teamscores" v-if='team[0]!=""'>
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
        },
        teamUpdate(photoID, team) {
            if (this.teamscores.length != 0) {
                var newscores = this.teamscores
                for (let t in newscores) {
                    if (newscores[t][0] == team) {
                        console.log(this.photosubmission[photoID]["score"])
                        newscores[t][1] = newscores[t][1] - this.photosubmission[photoID]["score"] + this.newscore
                    }
                }

                newscores.sort(this.sortScores)
                this.teamscores = newscores
            } else {
                this.updateLeaderboard()
            }
        },
        switchCurrentPhoto(dir) {
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
        },
        submitScore(photoID, team, objective) {
            this.switchCurrentPhoto('next')
            this.teamUpdate(photoID, team)

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
                            //console.log(response)
                        } else { //console.log(params) 
                        }
                    } else {
                        //console.log(params)
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

            if (this.teamscores.length == 0) {
                newscores.sort(this.sortScores)
                this.teamscores = newscores
            }
        }
    }
})




var host = new Vue({
    el: "#host",
    methods: {

    }
})