var x = document.getElementById("demo");

function getLocation() {
    if (navigator.geolocation) {
      navigator.geolocation.getCurrentPosition(showPosition, showError);
    } else { 
      x.innerHTML = "Geolocation is not supported by this browser.";
    }
}

function showPosition(position) {
    x.innerHTML = "Latitude: " + position.coords.latitude + 
    "<br>Longitude: " + position.coords.longitude;
}

function showError(error){
    switch(error.code){
        case error.PERMISSION_DENIED:
            
    }
}

/**
 * Uses the Haversine formula to calculate the distanc ebewee two points
 * @param  pos1 - The first position
 * @param  pos2 - The second position
 * @returns Distance betweent the two points
 */
function distance(pos1, pos2){
    var R = 6371e3; //metres
    var lat1 = pos1.coords.latitude;
    var lat2 = pos2.coords.latitude;
    var diffLong  = (pos2.coords.longitude - pos1.coords.longitude).toRadians();
    var diffLat = (lat2 - lat1).toRadians();

    var a = Math.pow(Math.sin(diffLat/2), 2) + Math.cos(lat1) * Math.cos(lat2) * Math.pow(Math.sin(diffLong/2), 2)

    var c = 2 * Math.atan(Math.sqrt(a) * Math.sqrt(1 - a));

    return R * c;
}