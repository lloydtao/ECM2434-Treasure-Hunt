// hard code
var teamName = "test team 1";
var gameID = "3Z0X";

var objective = new Vue({
	el: '#objectives',
	data: {
		teamData: {},
		objectivelist: {},
		currentObjective: "",
		currentObjectiveKey: "",
		q: "test",
		show: false
	},
	methods: {
		 /**Attempt to get the user's location and compare it with objLoc
		 * @param  {} objLoc - The location that the user is trying to check into
		 */
		 compareLocation(objLoc) {
		 	navigator.geolocation.getCurrentPosition(function(position){
		 		console.log(position);
		 		objective.getLocationSuccess(objLoc, position);
		 	}, this.errorCallback_highAccuracy, 
		 	{
		 		maximumAge:600000, timeout:10000, enableHighAccuracy: true
		 	});
		 },
		 /**If the error is a time out try to get location again with lower accuracy,
		 * else display the error
		 * @param  {} error - the error thrown by getCurrentPosition
		 */
		 errorCallback_highAccuracy(error) {
		 	if (error.code == error.TIMEOUT)
		 	{
		        // Attempt to get GPS loc timed out after 5 seconds, 
		        // try low accuracy location
		        navigator.geolocation.getCurrentPosition(function(position){
		        	objective.getLocationSuccess(objLoc, position);
		        }, 
		        this.errorCallback_lowAccuracy,
		        {maximumAge:600000, timeout:10000, enableHighAccuracy: false});
		        return;
		    }
		    
		    var msg = "Can't get your location (high accuracy attempt). Error = ";
		    if (error.code == 1)
		    	msg += "PERMISSION_DENIED";
		    else if (error.code == 2)
		    	msg += "POSITION_UNAVAILABLE";
		    msg += ", msg = "+error.message;
		    
		    alert(msg);
		},
		 /**Display error if getting location is unsuccessful
		 * @param  {} error - the error thrown by getCurrentPosition
		 */
		 errorCallback_lowAccuracy(error) {
		 	var msg = "Can't get your location (low accuracy attempt). Error = ";
		 	if (error.code == 1)
		 		msg += "PERMISSION_DENIED";
		 	else if (error.code == 2)
		 		msg += "POSITION_UNAVAILABLE";
		 	else if (error.code == 3)
		 		msg += "TIMEOUT";
		 	msg += ", msg = "+error.message;

		 	alert(msg);
		 },
		 /**Check if distance between user and the check in location is within a tolerance 
		 * and update the json to show that the objective is complete
		 * @param  {} objLoc
		 * @param  {} pos
		 */
		 getLocationSuccess(objLoc, pos){
		 	var a =Math.abs(this.distance(objLoc, pos));
		 	if (a < 10){
		 		console.log(true);
		 		this.show = true
		 		this.getQuestionFromDb()
		 	}
		 	else{
		 		console.log(false);
		 	}
		 },
		 checkQuestion(){
		 	fetch("api/check_question.php?objectiveID="+this.currentObjective["objectiveId"]+"&answer="+document.getElementById("answer").value+
		 		"&teamName="+teamName+"&gameID="+gameID+"&objectiveKey="+this.currentObjectiveKey)
		 	.then(response => response.text())
		 	.then(data => {
		 		if(data == "correct"){
		 			this.show = false
		 			alert("correct answer")
		 			this.fetchJSON()
		 		}
		 		else if (data == "incorrect"){
		 			alert("wrong answer")
		 		}
		 	})
		 },
		 getQuestionFromDb(){
		 	fetch("api/objectivequestion?objectiveID="+"2")
		 	.then(response => response.text())
		 	.then(data => {
		 		this.q = data
		 	})
		 },
		 /**
		 * Uses the Haversine formula to calculate the distance beween two points
		 * @param  pos1 - The first position
		 * @param  pos2 - The second position
		 * @returns Distance betweent the two points
		 */
		 distance(pos1, pos2){
		 	function toRad(angle){
		 		return angle*Math.PI/180;
		 	}

		    var R = 6371e3; //metres
		    var lat1 = toRad(pos1.coords.latitude);
		    var lat2 = toRad(pos2.coords.latitude);
		    var diffLong  = toRad(pos2.coords.longitude - pos1.coords.longitude);
		    var diffLat = toRad(lat2 - lat1);

		    var a = Math.pow(Math.sin(diffLat/2), 2) + Math.cos(lat1) * Math.cos(lat2) * Math.pow(Math.sin(diffLong/2), 2)

		    var c = 2 * Math.atan(Math.sqrt(a) * Math.sqrt(1 - a));

		    return R * c;
		},
		fetchJSON(){
			fetch("hunt_sessions/3Z0X.json")
			.then(response => response.json())
			.then(data => {
				var teamlist = data["teams"]
				this.teamData = teamlist["test team 1"]
				this.objectivelist = this.teamData["objectives"]["gps"]
				this.getNextObjective()
			})    
		},
		getNextObjective(){
			for (let objective in this.objectivelist) {
				if (this.objectivelist[objective]["completed"] === false) {
					this.currentObjectiveKey = objective
					this.currentObjective = this.objectivelist[objective]
					break
				}
				alert("location objectives complete");
			}    
		},
		submit(){
			fetch("getobjectivelocation.php?ID="+this.currentObjective["objectiveId"])
			.then(response => response.json())
			.then(data => {this.compareLocation(data)})
		}
	},
	beforeMount(){
		this.fetchJSON()
	}
});