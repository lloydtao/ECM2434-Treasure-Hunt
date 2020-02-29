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
        </div>

        <div id='create-form' class='form-group' v-else>
            <form class="" id="createteam" @submit.prevent="createTeam(newTeam)">
                Team name: <br>
                <input type="text" name="newTeam" maxlength='15' minlength='2'>
                <input type="submit" class='btn btn-outline-primary' value="Create">
            </form>
        </div>

        <div id='currentTeam' class='form-group' style="display:none">
            <p id="team"></p>
            <button type="button" class='btn btn-outline-primary' onclick="leaveTeam()">Leave team</button>
            <button type="button" class='btn btn-outline-primary'>Play game</button>
        </div>
    </div>
    `,
    data() {
        return {
            pin: null,
            nickname: null,
            maketeam: false,
            jsondata: []
        }
    },
    methods: {
        joinGame() {
            $.ajax({
                type: "POST",
                url: "joingame.php",
                data: {
                    pin: this.pin,
                    nickname: this.nickname
                },
                success: function (data) {
                    if (data === "join-success") {
                        console.log(data)
                    }
                }
            });

            this.fetchJson()
        },
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
        createTeam(newTeam) {
            $.ajax({
                type: "POST",
                url: "createteam.php",
                data: {newTeam: newTeam},
                success: function (data) {
                    if (data === "create-success") {
                        console.log(data)
                    } else { console.log(data) }
                }
            });

            this.maketeam = false
        },
        checkGame() {
            $.ajax({
                type: "POST",
                url: "checkgame.php",
                dataType: "json",
                success: function (data) {
                    if (data["status"] === "fail") {
                        //no game
                        console.log(data)
                    } else if (data["status"] === "success") {
                        console.log(data)
                        this.fetchJson()
                        this.pin = data["gameID"]
                        this.nickname = data["nickname"]
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
                success: function (data) {
                    if (data === "session-killed") {
                        console.log(data)
                    } else { console.log(data) }
                }
            });
            
            this.$emit('no-session')
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