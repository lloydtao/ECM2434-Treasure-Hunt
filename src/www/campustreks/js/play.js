Vue.component('game-start', {
    props: {
        insession: {
            type: Boolean,
            required: true
        }
    },
    template:  `
    <div content="no-cache" id="game-join">
        <form @submit.prevent="joinGame()" v-if="!insession">
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

        <div id='team-table' class='form-group' v-else-if="!maketeam">
            <table id='tableData'>
                <tr>
                <th>Team Name</th>
                <th>No. Players</th>
                <th>Players</th>
                <th>Join</th>
                </tr>


                <tr v-for="(data, key) in jsondata.teams" v-if='key!=""'>
                    <td>{{ key }}</td>
                    <td>{{ data.players.length }}</td>
                    <td v-for="player in data.players">{{ player }}</td>
                    <td><input type="submit" @click="joinTeam(key)" class="btn btn-outline-primary" value="Join"></td>
                </tr>


            </table>
            <div>
                <input type="button" class='btn btn-outline-primary' @click="maketeam = true" value="Create Team">
                <input type="button" class='btn btn-outline-primary' @click="fetchJson()" value="Refresh">
                <input type="button" class='btn btn-outline-primary' @click="endSession()" value="Quit">
            </div>

            <div id='currentTeam' class='form-group' v-if="inteam">
                <p id="team"></p>
                <button type="button" class='btn btn-outline-primary' @click='joinTeam("")'>Leave team</button>
                <button type="button" class='btn btn-outline-primary'>Play game</button>
            </div>
        </div>

        <form class='form-group' @submit.prevent="createTeam()" v-else>
            <div class="container">
                <input class="form-control" type="text" v-model="newteam" name="newteam" maxlength='15' minlength='2' placeholder='Team Name'>
                <p id="team-error" style="display: none">Team name taken</p>
                <p id="team-form-error" style="display: none">Invalid Team name</p>
                <input type="submit" class='btn btn-outline-primary' value="Create">
                <input type="button" class='btn btn-outline-primary' @click="maketeam = false" value="Back">
            </div>
        </form>
    </div>
    `,
    data() {
        return {
            pin: null,
            nickname: null,
            newteam: null,
            inteam: false,
            maketeam: false,
            jsondata: []
        }
    },
    mounted() {
        this.checkGame()
    },
    methods: {
        /**
         * Fetches the json data
         * @author James Caddock
         */
        fetchJson() {
            reqjson = this.pin
            safejson = './hunt_sessions/' + encodeURI(reqjson) + '.json'
            console.log(safejson)
            fetch(safejson)
            .then(response => response.json())
            .then(data => {
                this.jsondata = data
                console.log(data)
                this.alertSession()
            })            
        },
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
                        this.fetchJson()
                    } else if (data === "pin-error") {
                        $("#pin-error").css("display", "block")
                    } else if (data === "name-error") {
                        $("#name-error").css("display", "block")
                    } else if (data === "form-error") {
                        $("#form-error").css("display", "block")
                    }
                }
            });
        },
        /**
         * Lets vue know there's a session
         * @author James Caddock
         */
        alertSession() {
            if (this.jsondata != [] && this.nickname != null) {
                this.$emit('has-session')
            } else {
                this.$emit('no-session')
            } if (this.newteam != null) {
                this.maketeam = false
            }
        },
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
                        console.log(data)
                        this.checkGame()
                    } else { console.log(data) }
                }
            });
        },
        /**
         * Posts the new team data to add to the json
         * @author James Caddock
         */
        createTeam() {
            $("#team-error").css("display", "none");
            $("#team-form-error").css("display", "none");
            $.ajax({
                type: "POST",
                url: "api/createteam.php",
                data: {newteam: this.newteam},
                success: (data) => {
                    if (data === "create-team-success") {
                        console.log(data);
                        this.checkGame();
                    } 
                    else if (data === "team-error") {
                        $("#team-error").css("display", "block");
                        console.log(data);
                    } 
                    else if (data === "team-form-error") {
                        $("#team-form-error").css("display", "block");
                        console.log(data);
                    } 
                    else if (data === "session-error") {
                        console.log(data)
                    } console.log(data)
                }
            });
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
                        console.log(data)
                        this.endSession()
                    } else if (data["status"] === "success") {
                        console.log(data)
                        this.pin = data["gameID"]
                        this.nickname = data["nickname"]
                        this.fetchJson()
                        if (data["teamName"] != "") {
                            this.maketeam = false
                            this.inteam = true
                        } else {
                            this.inteam = false
                        }
                    }
                }
            });
        },
        /**
         * Sends an ajax request to end the current session
         * @author James Caddock
         */
        endSession() {
            $.ajax({
                type: "POST",
                url: "api/endsession.php",
                success: (data) => {
                    if (data === "session-ended") {
                        this.$emit('no-session')
                    }
                }
            });
        }
    },
})



var play = new Vue({
    el: "#play",
    data: {
        insession: false
    },
    methods: {
        hasSession() {
            this.insession = true
        },
        noSession() {
            this.insession = false
        }
    }
})