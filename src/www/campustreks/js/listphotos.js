var app = new Vue({
    el: '#objectives',
    data: {
        objectives: {}
    },
    methods: {
        getObjectives(){
            fetch("hunt_sessions/C0A3.json")
            .then(response => response.text())
            .then((response) => {
                var json = response;
                if(json != ''){
                    console.log(json);
                    var huntSessionData = JSON.parse(json);
                    console.log(huntSessionData)
                }
                this.objectives = huntSessionData["teams"]["test"]["objectives"]
            });
        }
    },
    beforeMount(){
        this.getObjectives();
    }
    //template: '<li v-for="(info, objective) in objectives" v-if="(info[\'type\']===\'photo\')"><a v-bind:href="\'new_submit_photo.php?objective=\'+objective">{{ objective }}</a></li>'
});