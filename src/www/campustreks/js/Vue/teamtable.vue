export <template>
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
            <input type="button" class='btn btn-outline-primary' @click="$emit('team-create')" value="Create Team">
            <input type="button" class='btn btn-outline-primary' @click="$emit('fetch-json')" value="Refresh">
            <input type="button" class='btn btn-outline-primary' @click="quitGame()" value="Quit">
        </div>

        <div id='currentTeam' class='form-group' v-if='currentteam!=""'>
            <p id="team"></p>
            <button type="button" class='btn btn-outline-primary' @click='joinTeam("")'>Leave team</button>
            <button type="button" class='btn btn-outline-primary'>Play game</button>
        </div>
    </div>
</template>

<script>
export default {
    name: "teamtable",
    props: {
        'jsondata' : Object,
        'currentteam' : String
    },
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
                        this.$emit('quit-game')
                    }
                }
            });
        }
    }
}
</script>