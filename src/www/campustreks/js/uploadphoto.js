var app = new Vue({
    el: '#objectives',
    data: {
        objectives: {}
    },
    methods: {
        getObjectives(){
            fetch("hunt_sessions/C0A3.json")
            .then(response => response.json())
            .then(data => (this.objectives = data["teams"]["test"]["objectives"]));
            
        }
    },
    beforeMount(){
        this.getObjectives();
    }
});