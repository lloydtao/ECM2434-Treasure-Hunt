Vue.component('game-start', {
    props: {
        insession: {
            type: Boolean,
            required: true
        }
    },
    template:  `
    <div id="game-join">
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


            <tr content="no-cache" v-for="(data, key) in jsondata.teams" v-if="realTeam(key)">
                <td>{{ key }}</td>
                <td>{{ data.players.length }}</td>
                <td v-for="player in data.players">{{ player }}</td>
                <td><input type="submit" @click="joinTeam(key)" class="btn btn-outline-primary" value="Join"></td>
            </tr>


        </table>
            <div>
                <input type="button" class='btn btn-outline-primary' @click="maketeam = true" value="Create Team">
                <input type="button" class='btn btn-outline-primary' @click="fetchJson()" value="Refresh">
                <input type="button" class='btn btn-outline-primary' @click="killSession()" value="Quit">
            </div>

            <div id='currentTeam' class='form-group' v-if="inteam">
                <p id="team"></p>
                <button type="button" class='btn btn-outline-primary' @click='joinTeam("")'>Leave team</button>
                <button type="button" class='btn btn-outline-primary'>Play game</button>
            </div>
        </div>

        <form class='form-group'  @submit.prevent="createTeam(newteam)" v-else>
            Team name: <br>
            <input type="text" v-model="newteam" maxlength='15' minlength='2'>
            <p id="team-error" style="display: none">Team name taken</p>
            <p id="team-form-error" style="display: none">Invalid Team name</p>
            <input type="submit" class='btn btn-outline-primary' value="Create">
            <input type="button" class='btn btn-outline-primary' @click="maketeam = false" value="Back">
        </form>
    </div>
    `,
    data() {
        return {
            pin: null,
            nickname: null,
            newteam: "",
            inteam: true,
            maketeam: false,
            jsondata: []
        }
    },
    mounted() {
        this.checkGame()
    },
    methods: {
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
        joinGame() {
            $("#pin-error").css("display", "none");
            $("#name-error").css("display", "none");
            $("#form-error").css("display", "none");
            $.ajax({
                type: "POST",
                url: "joingame.php",
                data: {
                    pin: this.pin,
                    nickname: this.nickname
                },
                success: (data) => {
                    if (data === "join-success") {
                        this.fetchJson()
                    } else if (data === "pin-error") {
                        $("#pin-error").css("display", "block");
                    } else if (data === "name-error") {
                        $("#name-error").css("display", "block");
                    } else if (data === "form-error") {
                        $("#form-error").css("display", "block");
                    }
                }
            });
        },
        alertSession() {
            if (this.jsondata != [] && this.nickname != null) {
                this.$emit('has-session')
            } 
        },
        realTeam(chosenteam){
            if (chosenteam != "") {
                return true
            } return false
        },
        joinTeam(chosenteam) {
            $.ajax({
                type: "POST",
                url: "jointeam.php",
                data: {chosenteam: chosenteam},
                success: function (data) {
                    if (data === "join-team-success") {
                        console.log(data)
                    } else { console.log(data) }
                }
            });
        },
        createTeam(newteam) {
            $("#team-error").css("display", "none")
            $("#team-form-error").css("display", "none")
            $.ajax({
                type: "POST",
                url: "createteam.php",
                data: {newteam: newteam},
                success: (data) => {
                    if (data === "create-success") {
                        this.maketeam = false
                        console.log(data)
                    } else if (data === "team-error") {
                        $("#team-error").css("display", "block")
                        console.log(data)
                    } else if (data === "team-form-error") {
                        $("#team-form-error").css("display", "block")
                        console.log(data)
                    }
                }
            });
        },
        checkGame() {
            $.ajax({
                type: "POST",
                url: "checkgame.php",
                dataType: "json",
                success: (data) => {
                    if (data["status"] === "fail") {
                        console.log(data)
                        this.killSession()
                    } else if (data["status"] === "success") {
                        console.log(data)
                        this.pin = data["gameID"]
                        this.nickname = data["nickname"]
                        this.fetchJson()
                        if (data["teamName"] != null) {
                            //go to game
                        } else {
                            //go to team select
                        }
                    } console.log(data)
                }
            });
        },
        killSession() {
            $.ajax({
                type: "POST",
                url: "killsession.php",
                success: (data) => {
                    if (data === "session-killed") {
                        this.$emit('no-session')
                    } else { console.log(data) }
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