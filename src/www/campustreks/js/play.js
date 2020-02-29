Vue.component('game-start', {
    props: {
        insession: {
            type: Boolean,
            required: true
        },
        jsondata: {
            type: JSON,
            required: true
        }
    },
    template:  `
    <div id="game-join">
        <form id='join-form' @submit.prevent="fetchJson()" v-if="!insession">
            <div class='container'>
                <div class='form-group'>
                    <input class="form-control" type='text' v-model='pin' name='pin' maxlength='4' size='12' placeholder='Pin'>
                    <p id='pin-error' style="display: none">Game not found</p>
                </div>
                <div class='form-group'>
                    <input class="form-control" type='text' name='nickname' id='nickname' maxlength='15' minlength='2' size='18' placeholder='Nickname'>
                    <p id="name-error" style="display: none">Nickname taken</p>
                </div>
                <button class='btn btn-outline-primary' type='submit'>Play</button>
                <p id="form-error" style="display: none">Please fill in all fields</p>
            </div>
        </form>

        <div id='team-table' class='form-group' v-else v-if="!">
        <table id='tableData'>
            <tr>
            <th>Team Name</th>
            <th>No. Players</th>
            <th>Players</th>
            <th>Join</th>
            </tr>

            <?php teamDisplay() ?>

        </table>

            <form><input type="button" class='btn btn-outline-primary' value="Create Team" onclick="createTeam()">
            <input type="button" class='btn btn-outline-primary' @click="fetchJson" value="Refresh">
            </form>
        </div>

        <div id='create-form' class='form-group' style='display:none' v-else>
            <form class="" id="createteam" method="post">
                Team name: <br>
                <input type="text" name="teamName" value="">
                <input type="submit" class='btn btn-outline-primary' name="createButton" value="Create team">
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
            nickname: null
        }
    },
    methods: {
        fetchJson() {
            reqjson = this.pin
            safejson = './hunt_sessions/' + encodeURI(reqjson) + '.json'
            console.log(safejson)
            fetch(safejson)
            .then(response => response.json())
            .then(data => {
                jsondata = data
            })
        }
    }
})



var play = new Vue({
    el: "#play",
    data: {
        insession: false,
        jsondata: ""
    }
})