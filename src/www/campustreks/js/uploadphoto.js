var app = new Vue({
    el: '#objectives',
    data() {
        return {
            objectives: {},
        }
    },
    methods: {
        getObjectives(){
            var teamName = 'yeet';//hardcoded for now
            var gamePin = 'NOU1';//hardcoded
            fetch("hunt_sessions/"+ gamePin +".json")
            .then(response => response.json())
            .then(data => (this.objectives = data["teams"][teamName]["objectives"]["photo"]));
            
        }
    },
    beforeMount(){
        this.getObjectives();
    }
});