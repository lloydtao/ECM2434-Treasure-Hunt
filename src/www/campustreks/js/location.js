function compareLocation(objLoc) {
    var pos;
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(locationSuccess(position));
        if (Math.abs(distance(objLoc, pos)) < 10){
            alert('sj');
        }
    
    } else { 
        console.log("jhkv");
    }
}

function locationSuccess(position) {
    pos = position;

}

function showPosition(position) {
    return position;
}
/*
function showError(error){
    switch(error.code){
        case error.PERMISSION_DENIED:
            
    }
}*/

/**
 * Uses the Haversine formula to calculate the distance beween two points
 * @param  pos1 - The first position
 * @param  pos2 - The second position
 * @returns Distance betweent the two points
 */
function distance(pos1, pos2){
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