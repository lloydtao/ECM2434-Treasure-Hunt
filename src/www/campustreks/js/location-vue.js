var app = Vue({
	el: '#objectives',
	data: {
		teamData: {},
		gpsSubmissions: [],
		currentObjective: {}
	},
	methods: {
		fetchJSON(){
			fetch(safejson)
			.then(response => response.json())
			.then(data => {
				var teamlist = data["teams"]
				this.teamData = teamlist["test team 1"]
				this.gpsSubmissions = []
				console.log(teamData);
				var objectivelist = teamData["objectives"]["gps"]
			})    
		},
		getNextObjective(){
			for (let objective in objectivelist) {
				console.log(objective)
				if (objectivelist[objective]["completed"] === false) {
					currentObjective = objectivelist[objective]
					break
				}
			}    
			console.log(currentObjective)
		}
	},
	template: ""
});