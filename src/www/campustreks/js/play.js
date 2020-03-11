Vue.component('game-start', {
    template: `
    <div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5>Join Hunt</h5>
                    </div>
                    <div class="card-body">
                        <form @submit.prevent="joinGame()">
                            <div class='form-group'>
                                <input class="form-control" type='text' v-model='pin' name='pin' maxlength='4' size='12' placeholder='Pin'>
                                <p id='pin-error' style="display: none">Game not found</p>
                            </div>
                            <div class='form-group'>
                                <input class="form-control" type='text' v-model='nickname' name='nickname' maxlength='15' minlength='2' size='18' placeholder='Nickname'>
                                <p id="name-error" style="display: none">Nickname taken</p>
                            </div>
                            <button class='btn btn-outline-primary' type='submit'>Play</button>
                            <p id="form-error" style="display: none">Please fill in all fields</p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    `,
    data() {
        return {
            pin: null,
            nickname: null
        }
    },
    methods: {
        /**
         * Sends an ajax request to join a Game
         * @author Jakub Kwak
         * */
        joinGame() {
            $("#pin-error").css("display", "none")
            $("#name-error").css("display", "none")
            $("#form-error").css("display", "none")
            $.ajax({
                type: "POST",
                url: "api/join_game.php",
                data: {
                    pin: this.pin,
                    nickname: this.nickname
                },
                success: (data) => {
                    if (data === "join-success") {
                        this.$emit("start-game")
                    } else if (data === "pin-error") {
                        $("#pin-error").css("display", "block")
                    } else if (data === "name-error") {
                        $("#name-error").css("display", "block")
                    } else if (data === "form-error") {
                        $("#form-error").css("display", "block")
                    }
                }
            });
        }
    }
})


Vue.component('team-table', {
    props: {
        'jsondata' : Object,
        'currentteam' : String
    },
    template: `
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5>Select Team</h5>
                </div>
                <div>
                    <table id='tableData' class="table table-striped">
                        <thead class="thead-dark">
                            <tr>
                                <th>Team</th>
                                <th>Players</th>
                                <th>Join</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(data, team) in jsondata.teams" v-if='team!=""' :key="data.id">
                                    <td>{{ team }}</td>
                                    <td>{{ data.players.join(', ') }} ({{ data.players.length }})</td>
                                    <td><input type="submit" @click="joinTeam(team)" :disabled="currentteam==team" class="btn btn-outline-primary" value="Join"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="card-body">
                    <div class='form-group'>
                        <div id='currentTeam' class="btn-group" role="group" v-if='currentteam==""'>
                            <button type="button" class='btn btn-outline-primary' @click="$emit('toggle-component', 2)" value="Create Team">New Team</button>
                            <button type="button" class='btn btn-outline-primary' @click="$emit('quit-game')" value="Quit">Leave</button>
                        </div>
                        <div id='currentTeam' class='btn-group' role="group" v-if='currentteam!=""'>
                            <button type="button" class='btn btn-outline-primary' @click='joinTeam("")'>Leave team</button>
                            <button type="button" class='btn btn-outline-primary' @click='playGame()'>Play game</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    `,
    methods: {
        /**
         * Adds the user to the chosenteam
         * @author James Caddock
         * @param string
         */
        joinTeam(chosenteam) {
            $.ajax({
                type: "POST",
                url: "api/join_team.php",
                data: {chosenteam: chosenteam},
                success: (data) => {
                    if (data === "join-team-success") {
                        if (chosenteam == "") {
                            this.$emit('in-team', chosenteam)
                        } else {
                            this.$emit('in-team', chosenteam)
                        }
                    }
                }
            });
        },
        playGame() {
            $.ajax({
                type: "POST",
                url: "api/play_game.php",
                success: (data) => {
                    if (data === "play-game-success") {
                        this.$emit("toggle-component", 3)
                    } else if (data === "game-already-started") {
                        this.$emit('toggle-component', 3)
                    }
                }

            })
        }
    }
})


Vue.component('create-team', {
    template: `
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5>New Team</h5>
                    </div>
                    <div class="card-body">
                        <form class='form-group' @submit.prevent="createTeam()">
                                <input class="form-control" type="text" v-model="newteam" name="newteam" maxlength='15' minlength='2' placeholder='Team Name'>
                                <br>
                                <p id="team-error" style="display: none">Team name taken</p>
                                <p id="team-form-error" style="display: none">Invalid Team name</p>
                                <div class="btn-group" role="group">
                                    <input type="submit" class='btn btn-outline-primary' value="Create">
                                    <input type="button" class='btn btn-outline-primary' @click="$emit('team-exit')" value="Back">
                                </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    `,
    data() {
        return {
            newteam: null
        }
    },
    methods: {
        /**
         * Posts the new team data to add to the json
         * @author James Caddock
         */
        createTeam() {
            $("#team-error").css("display", "none");
            $("#team-form-error").css("display", "none")
            $.ajax({
                type: "POST",
                url: "api/create_team.php",
                data: {newteam: this.newteam},
                success: (data) => {
                    if (data === "create-team-success") {
                        this.$emit("team-made", this.newteam)
                        this.$emit("team-exit")
                        this.newteam = null
                    }
                    else if (data === "team-error") {
                        $("#team-error").css("display", "block")
                    }
                    else if (data === "team-form-error") {
                        $("#team-form-error").css("display", "block")
                    }
                }
            });
        }
    }
})


Vue.component('location', {
    props: {
        jsondata: {
            type: Object,
            required: true
        },
        currentteam: {
            type: String,
            required: true
        }
    },
    template: `
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5>Your Progress</h5>
                </div>
                <div class="card-body">
	                <p>Team: {{ currentteam }}</p>
	                <p>Score: {{ score }}</p>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h5>Current Objective</h5>
                </div>
                <div class="card-body">
                    <div>
                        <div v-if="alert != 'All location objectives completed!'">
                            <div v-if="question == null">
                                <h5>Directions: </h5>
                                <p>{{ direction }}</p>
                                <button class='btn btn-outline-primary' type="button" v-on:click="submit">Check-in</button><br>
                            </div>
                            <div v-else>
                                <br>
                                {{ question }}<br>
                                <input v-model='answer' name='answer'> <br>
                                <button class='btn btn-outline-primary' v-on:click="checkQuestion">Submit Answer</button>
                            </div>
                        </div>
                        <div id="alert" v-show="!(alert==null)">
                            {{ alert }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h5>Bonus Objectives</h5>
                </div>
                <div class="card-body">
                    <button type="button" class='btn btn-outline-primary' @click='$emit("photo-submit")'>View</button>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <button type="button" class='btn btn-outline-primary' @click="$emit('quit-game')">Quit Game</button>
                </div>
            </div>
        </div>
    </div>
    `,
    data() {
        return {
            objectivelist: {},
            currentObjectiveKey: null,
            question: null,
            answer: null,
            direction: null,
            alert: null,
            timeout: null,
            interval: null,
            objLoc: null,
            score: 0
        }
    },
    mounted(){
        setTimeout(this.getNextObjective, 100)
        this.interval = setInterval(this.getNextObjective, 1000)
    },
    methods: {
        /**Attempt to get the user's location and compare it with objLoc
         * @param  {} objLoc - The location that the user is trying to check into
         */
        /**If the error is a time out try to get location again with lower accuracy,
         * else display the error
         * @param  {} error - the error thrown by getCurrentPosition
         */
        errorCallback_highAccuracy(error) {
            if (error.code == error.TIMEOUT)
            {
                // Attempt to get GPS loc timed out after 5 seconds,
                // try low accuracy location
                navigator.geolocation.getCurrentPosition(this.getLocationSuccess,
                    this.errorCallback_lowAccuracy,
                    {maximumAge:600000, timeout:10000, enableHighAccuracy: false});
                return;
            }

            var msg = "Can't get your location (high accuracy attempt). Error = ";
            if (error.code == 1)
                msg += "PERMISSION_DENIED";
            else if (error.code == 2)
                msg += "POSITION_UNAVAILABLE";
            msg += ", msg = "+error.message;

            alert(msg);
        },
        /**Display error if getting location is unsuccessful
         * @param  {} error - the error thrown by getCurrentPosition
         */
        errorCallback_lowAccuracy(error) {
            var msg = "Can't get your location (low accuracy attempt). Error = ";
            if (error.code == 1)
                msg += "PERMISSION_DENIED";
            else if (error.code == 2)
                msg += "POSITION_UNAVAILABLE";
            else if (error.code == 3)
                msg += "TIMEOUT";
            msg += ", msg = "+error.message;

            alert(msg);
        },
        /**Check if distance between user and the check in location is within a tolerance
         * and update the json to show that the objective is complete
         * @param  {} objLoc
         * @param  {} pos
         */
        getLocationSuccess(pos){
            var a = Math.abs(this.distance(this.objLoc, pos));
            if (a < 10){
                this.getQuestionFromDb()
            }
            else{
                clearTimeout(this.timeout)
                this.alert = "you are too far from the objective"
                setTimeout(this.alertFade, 1500);
            }
        },
        checkQuestion(){
            $.ajax({
                type: "POST",
                url: "api/check_question.php",
                data: {
                    objectiveID: this.objectivelist[this.currentObjectiveKey]["objectiveId"],
                    answer: this.answer,
                    objectiveKey: this.currentObjectiveKey
                },
                success: (data) => {
                    if (data === "correct") {
                        this.objLoc = null
                        this.question = null
                        this.direction = null
                        this.answer = null
                        Vue.set(this.objectivelist[this.currentObjectiveKey], "completed", true)
                        this.alert = "correct answer"
                        this.timeout = setTimeout(this.alertFade, 1500)
                        this.getNextObjective()

                    }
                    else if (data === "incorrect") {
                        this.alert = "wrong answer"
                        this.timeout = setTimeout(this.alertFade, 1500	)
                    }
                }
            });
        },
        alertFade() {
            if(!(this.alert === "All location objectives completed!")){
                this.alert = null
            }
        },
        getQuestionFromDb(){
            fetch("api/objective_question?objectiveID="+this.objectivelist[this.currentObjectiveKey]["objectiveId"])
                .then(response => response.text())
                .then(data => {
                    this.question = data
                })
        },
        /**
         * Uses the Haversine formula to calculate the distance beween two points
         * @param  pos1 - The first position
         * @param  pos2 - The second position
         * @returns Distance betweent the two points
         */
        distance(pos1, pos2){
            function toRad(angle){
                return angle*Math.PI/180;
            }

            var R = 6371e3; //metres
            var lat1 = toRad(pos1.coords.latitude);
            var lat2 = toRad(pos2.coords.latitude);
            var diffLong  = toRad(pos2.coords.longitude - pos1.coords.longitude);
            var diffLat = toRad(lat2 - lat1);

            var a = Math.pow(Math.sin(diffLat/2), 2) + Math.cos(lat1) * Math.cos(lat2) * Math.pow(Math.sin(diffLong/2), 2)

            var c = 2 * Math.atan(Math.sqrt(a) * Math.sqrt(1 - a));

            return R * c;
        },
        getNextObjective(){
            if (this.currentteam == "") {
                clearInterval(this.interval)
            }

            this.objectivelist = this.jsondata["teams"][this.currentteam]["objectives"]["gps"]
            this.score = this.jsondata["teams"][this.currentteam]["teaminfo"]["score"]

            for (let objective in this.objectivelist) {
                if (this.objectivelist[objective]["completed"] === false) {
                    this.currentObjectiveKey = objective
                    fetch("api/location_description.php?objectiveID="+this.objectivelist[this.currentObjectiveKey]["objectiveId"])
                        .then(response => response.text())
                        .then(data => this.direction = data)
                    return
                }
            }
            clearTimeout(this.timeout)
            this.alert = "All location objectives completed!"
        },
        submit(){
            this.alert = ""
            fetch("get_objective_location.php?ID="+this.objectivelist[this.currentObjectiveKey]["objectiveId"])
                .then(response => response.json())
                .then(data => {
                    this.objLoc = data
                    navigator.geolocation.getCurrentPosition(this.getLocationSuccess, this.errorCallback_highAccuracy,
                        {
                            maximumAge:600000, timeout:10000, enableHighAccuracy: true
                        });
                })
        }
    }
})



Vue.component('photo-submit', {
    props: {
        currentteam: String,
        pin: String,
        huntsessiondata: Object
    },
    template: `
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5>Photo Objectives</h5>
                </div>
                <div class="card-body">
                    <p>
                        Submit photos here.<br>
                        Teams will receive bonus points for the best pictures!
                    </p>
                    <div v-if="showUpload">
                        <form id="uploadForm" v-on:submit.prevent enctype="multipart/form-data">
                            <h4> {{objectives[currentObjective]["description"]}} </h4>
                            <img width="100%" @error="imgPath=null" v-if="imgPath!=null" v-bind:src="imgPath">
                            <p>Select image to upload:</p><br>
                            <input type="file" accept="image/*" capture="camera" name="image" /><br>
                            <button class='btn btn-outline-primary' v-on:click="submitForm()">Upload</button>
                            <button class='btn btn-outline-primary' v-on:click="hideUploadForm()">Back</button>
                        </form>
                        <p v-if="errorMessage!=null" style="margin-top:10px; margin-bottom:-20px">{{ errorMessage }}</p>
                    </div>
                    <table class="table table-striped" v-else>
                        <thead class="thead-dark">
                            <tr>
                                <th scope="col">Objective</th>
                                <th scope="col">Submit</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(objective, index) in objectives">
                                <td>{{ objective["description"] }}</td>
                                <td><button class='btn btn-outline-primary' v-on:click="showUploadForm(index)">Submit</button></td>
                            </tr>
                        </tbody>
			            <button class='btn btn-outline-primary' v-on:click="$emit('return-table')">Back</button>
                    </table>
                </div>
            </div>
        </div>
    </div>
    `,
    data() {
        return {
            objectives: {},
            showUpload: false,
            currentObjective: null,
            imgPath: null,
            errorMessage: null
        }
    },
    mounted() {
        this.objectives = this.huntsessiondata["teams"][this.currentteam]["objectives"]['photo'];
        this.currentObjective = null;
    },
    methods: {
        submitForm() {
            var formData = new FormData($('#uploadForm')[0]);
            formData.append("objective_id", this.currentObjective);
            $.ajax({
                type: "POST",
                url: "api/upload_photo.php",
                data: formData,
                dataType: "json",
                cache: false,
                contentType: false,
                processData: false,
                success: (response) => {
                    if (response['status'] === 'ok') {
                        this.showUpload = false;
                        this.currentObjective = null;
                        this.objectives = this.huntsessiondata["teams"][this.currentteam]["objectives"]['photo'];
                    } else if (response['status'] === 'error' ) {
                        this.errorMessage = response['message']
                        setTimeout(this.errorMessageFade, 1000)
                    }
                },
                error: (response) => {
                    this.errorMessage = response['message']
                    setTimeout(this.errorMessageFade, 1000)
                }

            })
        },
        errorMessageFade() {
            this.errorMessage = null
        },
        showUploadForm(index) {
            this.currentObjective = index;
            //used to prevent image caching
            var randomString =  Math.random().toString(18).substring(2, 15)
            this.imgPath = "../image_uploads/" + this.pin + this.currentteam + "-"
                + this.currentObjective + ".jpg?" + randomString

            this.showUpload = true;
        },
        hideUploadForm(){
            this.currentObjective = null;
            this.imgPath = null;
            this.showUpload = false;
        }
    }
})




var play = new Vue({
    el: "#play",
    data: {
        togglecomponent: 0,
        pin: null,
        jsondata: {},
        currentteam: "",
        gameInterval: null,
        endGameMessage: null
    },
    beforeMount() {
        this.startGame()
    },
    methods: {
        startGame() {
            this.checkGame()
            this.gameInterval = setInterval(this.checkGame, 1000)
        },
        /**
         * Fetches the json data
         * @author James Caddock
         */
        fetchJson() {
            if (this.pin != null){
                var reqjson = this.pin
                var randomString =  Math.random().toString(18).substring(2, 15)
                var safejson = 'hunt_sessions/' + encodeURI(reqjson) + '.json?' + randomString
                fetch(safejson)
                    .then(response => response.json())
                    .then(data => {
                        this.jsondata = data
                    })
            }
        },
        /**
         * Checks PHP session data with json data
         * @author Jakub Kwak
         */
        checkGame() {
            $.ajax({
                type: "POST",
                url: "api/check_game.php",
                dataType: "json",
                data: { type : "play" },
                success: (data) => {
                    if (data["status"] === "fail") {
                        if(data["game"] == "inactive" && this.togglecomponent != 1 && this.togglecomponent != 2) {
                            this.togglecomponent = 0
                            this.currentteam = ""
                            this.pin = null
                        } else {
                            if(data["game"] == "active") {
                                this.endGameMessage = "Game has Finished"
                            } else {
                                this.endGameMessage = "Game has Prematurely Finished"
                            }
                            this.togglecomponent = 5
                        }

                        clearInterval(this.gameInterval)
                    } else if (data["status"] === "success") {
                        if (this.togglecomponent == 0) {
                            this.togglecomponent = 1
                        }

                        this.pin = data["gameID"]

                        if (data["teamName"] != "" && data["teamName"] != null) {
                            this.currentteam = data["teamName"]

                            if (data["game"] == "active" && this.togglecomponent != 4) {
                                this.togglecomponent = 3
                            }


                        } else {
                            this.currentteam = ""
                        }
                    }
                    this.fetchJson()
                }
            });
        },
        /**
         * Sends an ajax request to end the current session
         * @author James Caddock
         */
        quitGame() {
            $.ajax({
                type: "POST",
                url: "api/quit_game.php",
                success: (data) => {
                    if (data === "game-ended") {
                        this.checkGame()
                    }
                }
            });
        }
    }
})