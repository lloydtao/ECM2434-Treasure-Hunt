export <template>
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
</template>

<script>
export default {
    name: "gamestart",
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
}
</script>