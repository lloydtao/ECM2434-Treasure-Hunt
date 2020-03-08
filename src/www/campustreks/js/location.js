/**Attempt to get the user's location and compare it with objLoc
 * @param  {} objLoc - The location that the user is trying to check into
 */
function compareLocation(objLoc) {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position){
            getLocationSuccess(objLoc, position);
        }, errorCallback_highAccuracy, 
        {maximumAge:600000, timeout:10000, enableHighAccuracy: true}
        );
        
    } else { 
        document.getElementById("demo").innerHTML = "Your browser does not support location";
    }
}
/**If the error is a time out try to get location again with lower accuracy,
 * else display the error
 * @param  {} error - the error thrown by getCurrentPosition
 */
function errorCallback_highAccuracy(error) {
    if (error.code == error.TIMEOUT)
    {
        // Attempt to get GPS loc timed out after 5 seconds, 
        // try low accuracy location
        navigator.geolocation.getCurrentPosition(function(position){
            getLocationSuccess(objLoc, position);
        }, 
            errorCallback_lowAccuracy,
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
}
/**Display error if getting location is unsuccessful
 * @param  {} error - the error thrown by getCurrentPosition
 */
function errorCallback_lowAccuracy(error) {
    var msg = "Can't get your location (low accuracy attempt). Error = ";
    if (error.code == 1)
        msg += "PERMISSION_DENIED";
    else if (error.code == 2)
        msg += "POSITION_UNAVAILABLE";
    else if (error.code == 3)
        msg += "TIMEOUT";
    msg += ", msg = "+error.message;
    
    alert(msg);
}
/**Check if distance between user and the check in location is within a tolerance 
 * and update the json to show that the objective is complete
 * @param  {} objLoc
 * @param  {} pos
 */
function getLocationSuccess(objLoc, pos){
    console.log(pos);
    var a =Math.abs(distance(objLoc, pos));
    console.log(a);
    if (a < 10){
        $.post("writeToJson.php",
        {Game:gamePin, Team:team, Id:id},
        );
    }else{
        document.getElementById("demo").innerHTML = "Too far away from the objective";
    }
}


/**
 * Uses the Haversine formula to calculate the distance beween two points
 * @param  pos1 - The first position
 * @param  pos2 - The second position
 * @returns Distance betweent the two points
 */
function distance(pos1, pos2){
    function toRad(angle){
        return angle*Math.PI/180;
    }

    var R = 6371e3; //metres
    console.log('alfh');
    var lat1 = toRad(pos1.coords.latitude);
    var lat2 = toRad(pos2.coords.latitude);
    var diffLong  = toRad(pos2.coords.longitude - pos1.coords.longitude);
    var diffLat = toRad(lat2 - lat1);

    var a = Math.pow(Math.sin(diffLat/2), 2) + Math.cos(lat1) * Math.cos(lat2) * Math.pow(Math.sin(diffLong/2), 2)

    var c = 2 * Math.atan(Math.sqrt(a) * Math.sqrt(1 - a));

    return R * c;
}