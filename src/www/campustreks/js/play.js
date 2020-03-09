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
            <button type="button" class='btn btn-outline-primary' @click='$emit("toggle-component", 3)'>Submit Photo</button>
            <button type="button" class='btn btn-outline-primary'>Play game</button>
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


Vue.component('photo-submit', {
    props: {
        currentteam: String,
        pin: String
    },
    template: `
    <div>
        <div class="heading">
            <h2>Submit photo</h2>
        </div>
        <div v-if="showUpload">
            <img width="500px" v-if="objectives[currentObjective]['completed']"
                v-bind:src="imgPath">
            <form id="uploadForm" v-on:submit.prevent enctype="multipart/form-data">
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
            //get Json data
            fetch("hunt_sessions/" + this.pin + ".json")
                .then(response => response.text())
                .then((response) => {
                    var json = response;
                    if (json !== '') {
                        var huntSessionData = JSON.parse(json);
                    }
                    //save objective data to vue component
                    this.objectives = huntSessionData["teams"][this.currentteam]["objectives"]['photo'];
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
                });
        },
        submitForm() {
            $(function () {
                var formData = new FormData($('#uploadForm')[0]);
                formData.append("objective_id", photoSubmit.currentObjective);
                $.ajax({
                    type: "POST",
                    url: "/api/upload_photo.php",
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function (response) {
                        response = $.parseJSON(response);
                        if (response['status'] === 'ok') {
                            photoSubmit.showUpload = false;
                            photoSubmit.currentObjective = null;
                            photoSubmit.getObjectives();
                        } else if (response['status'] === 'error' ) {
                            alert(response['message']);
                            //@TODO consider using custom error box
                        }
                    },
                    error: function (response) {
                        console.log(response);
                    }

                });
            });

            return false;
        },
        showUploadForm(index) {
            this.currentObjective = index;
            //used to prevent image caching
            var date = new Date;
            this.imgPath = this.objectives[this.currentObjective]['path'] + "?" + date.getSeconds();
            this.showUpload = true;
        },
        hideUploadForm(){
            this.currentObjective = null;
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
    mounted() {
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
                var safejson = '../hunt_sessions/' + encodeURI(reqjson) + '.json?' + randomString
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
                        this.togglecomponent = 0
                        this.currentteam = ""
                        this.pin = null
                        clearInterval(this.gameInterval)
                    } else if (data["status"] === "success") {
                        if (this.togglecomponent == 0) {
                            this.togglecomponent = 1
                        }
                       
                        this.pin = data["gameID"]
                        
                        if (data["teamName"] != "" && data["teamName"] != null) {
                            this.currentteam = data["teamName"]
                        } else {
                            this.currentteam = ""
                        }
                    }
                    this.fetchJson()
                }
            });
        }
    }
})