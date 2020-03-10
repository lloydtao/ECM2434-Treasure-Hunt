Vue.component('game-start', {
    template: `
    <div>
        <form @submit.prevent="joinGame()">
            <div class='container'>
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
            </div>
        </form>
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
                url: "api/joingame.php",
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
    <div class='form-group'>
        <table id='tableData'>
            <tr>
            <th>Team Name</th>
            <th>No. Players</th>
            <th>Players</th>
            <th>Join</th>
            </tr>


            <tr v-for="(data, team) in jsondata.teams" :key="data.id">
                <div  v-if='team!=""'>
                    <td>{{ team }}</td>
                    <td>{{ data.players.length }}</td>
                    <td v-for="player in data.players" :key="player.id">{{ player }}</td>
                    <td><input type="submit" @click="joinTeam(team)" :disabled="currentteam==team" class="btn btn-outline-primary" value="Join"></td>
                </div>
            </tr>


        </table>
        <div>
            <input type="button" class='btn btn-outline-primary' @click="$emit('toggle-component', 2)" value="Create Team">
            <input type="button" class='btn btn-outline-primary' @click="$emit('fetch-json')" value="Refresh">
            <input type="button" class='btn btn-outline-primary' @click="quitGame()" value="Quit">
        </div>

        <div id='currentTeam' class='form-group' v-if='currentteam!=""'>
            <p id="team"></p>
            <button type="button" class='btn btn-outline-primary' @click='joinTeam("")'>Leave team</button>
            <button type="button" class='btn btn-outline-primary' @click='$emit("toggle-component", 3)'>Play game</button>
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
                url: "api/jointeam.php",
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
        /**
         * Sends an ajax request to end the current session
         * @author James Caddock
         */
        quitGame() {
            $.ajax({
                type: "POST",
                url: "api/quitgame.php",
                success: (data) => {
                    if (data === "game-ended") {
                        this.$emit('toggle-component', 0)
                    }
                }
            });
        }
    }
})


Vue.component('create-team', {
    template: `
    <form class='form-group' @submit.prevent="createTeam()">
        <div class="container">
            <input class="form-control" type="text" v-model="newteam" name="newteam" maxlength='15' minlength='2' placeholder='Team Name'>
            <p id="team-error" style="display: none">Team name taken</p>
            <p id="team-form-error" style="display: none">Invalid Team name</p>
            <input type="submit" class='btn btn-outline-primary' value="Create">
            <input type="button" class='btn btn-outline-primary' @click="$emit('team-exit')" value="Back">
        </div>
    </form>
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
                url: "api/createteam.php",
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
    <div class="container">
        <div class="heading">
            <h2>Submit location</h2>
        </div>
        <div class="content">
	    <h4>Current Score: {{ score }}</h4>
            <div>
                <div v-if="alert != 'All location objectives completed!'">
                    <div v-if="question == null">
                        <p>{{ direction }}</p>
                        <button class='btn btn-outline-primary' type="button" v-on:click="submit">Submit Location</button><br>
                    </div>
                    <div v-else>
                        <br>
                        {{ question }}<br>
                        <input v-model='answer' name='answer'> <br>
                        <button class='btn btn-outline-primary' v-on:click="checkQuestion">Submit Answer</button>
                    </div>
                </div>
                <div id="alert" v-show="!(alert==null)">{{ alert }}</div>
            </div>
        </div>
        <button type="button" class='btn btn-outline-primary' @click='$emit("photo-submit")'>Submit Photo</button>
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
             console.log(pos)
             console.log(a)
             console.log(this.objLoc)
		 	if (a < 10){
		 		console.log(true);
		 		this.getQuestionFromDb()
		 	}
		 	else{
		 		clearTimeout(this.timeout)
		 		this.alert = "you are too far from the objective"
				setTimeout(this.alertFade, 1500);
		 		console.log(false);
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
		 	fetch("api/objectivequestion?objectiveID="+this.objectivelist[this.currentObjectiveKey]["objectiveId"])
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
            this.objectivelist = this.jsondata["teams"][this.currentteam]["objectives"]["gps"]
            this.score = this.jsondata["teams"][this.currentteam]["teaminfo"]["score"]
            console.log(this.objectivelist)

			for (let objective in this.objectivelist) {
				if (this.objectivelist[objective]["completed"] === false) {
					this.currentObjectiveKey = objective
					fetch("api/locationdescription.php?objectiveID="+this.objectivelist[this.currentObjectiveKey]["objectiveId"])
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
			fetch("getobjectivelocation.php?ID="+this.objectivelist[this.currentObjectiveKey]["objectiveId"])
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
    <div>
        <div class="heading">
            <h2>Submit photo</h2>
        </div>
        <div v-if="showUpload">
            <form id="uploadForm" v-on:submit.prevent enctype="multipart/form-data">
                <img width="500px" @error="imgPath=null" v-if="imgPath!=null" v-bind:src="imgPath">
                <p>Select image to upload:</p><br>
                <input type="file" name="image" /><br>
                <button class='btn btn-outline-primary' v-on:click="submitForm()">Upload</button>
                <button class='btn btn-outline-primary' v-on:click="hideUploadForm()">Back</button>
            </form>
        </div>
        <div v-else>
            <li v-for="(objective, index) in objectives">
                <button class='btn btn-outline-primary' v-on:click="showUploadForm(index)">{{ objective["description"] }}</button>
            </li>

            <button class='btn btn-outline-primary' v-on:click="$emit('return-table')">Back</button>
        </div>
    </div>
    `,
    data() {
        return {
            objectives: {},
            showUpload: false,
            currentObjective: null,
            imgPath: null
        }
    },
    mounted() {
        this.getObjectives();
        this.currentObjective = null;
    },
    methods: {
        /**
         * Gets photo objectives from JSON and their descriptions from DB
         */
        getObjectives() {
            //save objective data to vue component
            this.objectives = this.huntsessiondata["teams"][this.currentteam]["objectives"]['photo'];
            var objectiveIDs = [];
            //create array of objective IDs
            for (var objective in this.objectives) {
                if (this.objectives.hasOwnProperty(objective)) {
                    objectiveIDs.push(this.objectives[objective]["objectiveId"]);
                }
            }
            //get objective descriptions from DB
            fetch("api/objectivedescription.php?objectiveIDs=" + objectiveIDs)
                .then(response => response.json())
                .then(response => {
                    let index = 0;
                    //add objective descriptions to vue component
                    for (let objective in this.objectives) {
                        if (this.objectives.hasOwnProperty(objective)) {
                            Vue.set(this.objectives[objective], "description", response[index]);
                        }
                        index++;
                    }
                });
        },
        submitForm() {
            var formData = new FormData($('#uploadForm')[0]);
            formData.append("objective_id", this.currentObjective);
            $.ajax({
                type: "POST",
                url: "api/upload_photo.php",
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: (response) => {
                    response = $.parseJSON(response);
                    if (response['status'] === 'ok') {
                        this.showUpload = false;
                        this.currentObjective = null;
                        this.getObjectives();
                    } else if (response['status'] === 'error' ) {
                        alert(response['message']);
                        //@TODO consider using custom error box
                    }
                },
                error: (response) => {
                    console.log(response);
                }

            })
        },
        showUploadForm(index) {
            this.currentObjective = index;
            //used to prevent image caching
            var randomString =  Math.random().toString(18).substring(2, 15)
            this.imgPath = "image_uploads/" + this.pin + this.currentteam + "-" 
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
        gameInterval: null
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
            if (this.pin != null) {
                var reqjson = this.pin
                var randomString =  Math.random().toString(18).substring(2, 15)
                var safejson = 'hunt_sessions/' + encodeURI(reqjson) + '.json?' + randomString
                fetch(safejson)
                .then(response => response.json())
                .then(data => {
                    this.jsondata = data
                })   
            } else {
                this.jsondata = {};
            }
        },
        /**
         * Checks PHP session data with json data
         * @author Jakub Kwak
         */
        checkGame() {
            $.ajax({
                type: "POST",
                url: "api/checkgame.php",
                dataType: "json",
                success: (data) => {
                    if (data["status"] === "fail") {
                        if(data["ingame"] != true) {
                            this.togglecomponent = 0
                            this.currentteam = ""
                            this.pin = null
                        } else {
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

                            if (data["ingame"] == true && this.togglecomponent != 4) {
                                this.togglecomponent = 3
                            }


                        } else {
                            this.currentteam = ""
                        }
                    } console.log(data)
                    this.fetchJson()
                }
            });
        }
    }
})
