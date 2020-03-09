export <template>
    <form class='form-group' @submit.prevent="createTeam()">
        <div class="container">
            <input class="form-control" type="text" v-model="newteam" name="newteam" maxlength='15' minlength='2' placeholder='Team Name'>
            <p id="team-error" style="display: none">Team name taken</p>
            <p id="team-form-error" style="display: none">Invalid Team name</p>
            <input type="submit" class='btn btn-outline-primary' value="Create">
            <input type="button" class='btn btn-outline-primary' @click="$emit('team-exit')" value="Back">
        </div>
    </form>
</template>

<script>
export default {
    name: "createteam",
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
}
</script>