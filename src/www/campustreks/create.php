<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta name="author" content="Michael Freeman">
    <meta name="contributors" content="Jakub Kwak">
    <style>
        .error {
            color: #FF0000;
        }

        #map {
            height: 500px;
            margin: auto;
        }
    </style>
    <title>Create - CampusTreks</title>

    <script type="text/javascript">
    var dberr = "";
    var hunterr = "";
    </script>

    <?php include('templates/head.php');
    // Redirect to login.php if not already logged in
    include "checklogin.php";
    if (!CheckLogin()) {
        header("location:login.php");
    }

    include "utils/connection.php";
    $conn = openCon();
    $user = $_SESSION["username"];

    $titleErr = $descriptionErr = "";
    $locationObjectives = 0;
    $objectives = 1;
    $title = $description = "";

    /**
     *Removes whitespace, slashes && special characters from strings
     * @param string $data
     * @return string $data
     */
    function makeSafe($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);

        return $data;
    }

    // Only run if the submit button has been pressed
    if (isset($_POST['submit'])) {
        $title = makeSafe($_POST["title"]);
        $description = makeSafe($_POST["description"]);
        // Count how many objectives have been added
        while (array_key_exists("objective{$objectives}Directions", $_POST) || array_key_exists("objective{$objectives}Description", $_POST)) {
            if(array_key_exists("objective{$objectives}Directions", $_POST))
                $locationObjectives++;
            $objectives++;

        }


        // Check the hunt title and description have been set
        if (!$title || !$description) {
            if (!$title)
                $titleErr = "Required field";
            if (!$description)
                $descriptionErr = "Required field";
            if($locationObjectives == 0)
                echo "<script type='text/javascript'>hunterr = \"At least one Location objective needed\";</script>";
        } else {
            if($locationObjectives == 0)
            echo "<script type='text/javascript'>hunterr = \"At least one Location objective needed\";</script>";
            else {

                $logitude = $latitude = $question = $answer = $photoDescription = "";

                // Create the hunt in the database
                $sql = $conn->prepare("INSERT INTO Hunt (Name, Description, Username) VALUES(?, ?, ?);");
                $sql->bind_param("sss", $title, $description, $user);

                if ($sql->execute()) {
                    $hunt_id = $conn->insert_id;
                } else {
                    echo "<script type='text/javascript'>dberr = \"Error adding the hunt.
                     Please try again. If this error persists contact the system admin\";</script>";
                }

                // Prepare sql statements for adding hunt to the database
                $objectiveSql = $conn->prepare("INSERT INTO objectives (HuntID) Values(?)");
                $objectiveSql->bind_param("i", $hunt_id);

                $locationSql = $conn->prepare("INSERT INTO location (ObjectiveID, Longitude,
                Latitude, Question, Answer, Direction) VALUES(?, ?, ?, ?, ?, ?);");
                $locationSql->bind_param("iddsss", $last_id, $longitude, $latitude,
                $question, $answer, $directions);

                $photoSql = $conn->prepare( "INSERT INTO PhotoOps (ObjectiveID, Specification)
                VALUES(?, ?);");
                $photoSql->bind_param("is", $last_id, $specification);

                for ($x = 1; $x < $objectives; $x++) {
                    // Add new objective to database
                    if ($objectiveSql->execute()) {
                        $last_id = $conn->insert_id;
                    } else {
                        echo "<script type='text/javascript'>dberr = \"Error adding the hunt.
                        Please try again. If this error persists contact the system admin\";</script>";
                        break;
                    }

                    // Check which type each objective is and add a SQL statement to add to the correct table
                    if (array_key_exists("objective{$x}Longitude", $_POST)) {
                        // Make the attributes database safe
                        $longitude = (string)$_POST["objective{$x}Longitude"];
                        $latitude = (string)$_POST["objective{$x}Latitude"];
                        $question = makeSafe($_POST["objective{$x}Question"]);
                        $answer = makeSafe($_POST["objective{$x}Answer"]);
                        $directions = makeSafe($_POST["objective{$x}Directions"]);

                        if ($logitude != "" && $latitude != "" && $question != "" && $answer != "" && $directions != "") {
                            continue;
                        }
                        // Add Location to database
                        if (!($locationSql->execute())){
                            echo "<script type='text/javascript'>dberr = \"Error adding the hunt.
                            Please try again. If this error persists contact the system admin\";</script>";
                        }

                    } else {

                        $specification = makeSafe($_POST["objective{$x}Description"]);
                        if (!$specification)
                            continue;

                        // Add photo objective to database
                        if ($photoSql->execute()){
                            echo "<script type='text/javascript'>dberr = \"Error adding the hunt.
                            Please try again. If this error persists contact the system admin\";</script>";
                        }
                    }

                }

                $objectiveSql->close();
                $sql->close();
                $photoSql->close();
                $locationSql->close();
                $conn->close();
                header("Location: index.php");
            }
        }
    }
    ?>

</head>
<body>
<!-- Header -->
<?php include('templates/header.php'); ?>
<!-- Content -->
<main class="page create-page">
    <section class="portfolio-block hire-me">
        <div class="container">
            <div class="heading">
                <h2>Create A Hunt</h2>
            </div>

          <form id="create-form" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <p id="dberror"></p>
                <p id="hunterror"></p>
                <div class="form-group">
                    <label for="title">Title</label><br>
                    <input class="form-control" type="text" name="title" required value= <?php echo $title; ?>>
                </div>
                <div class="form-group">
                    <label for="description">Description</label><br>
                    <textarea class="form-control"
                              name="description" required><?php echo $description; ?></textarea>
                </div>
                <div class="form-group">
                    <label for="objectives">Objectives</label><br>
                    <div class="btn-group d-flex btn-block">
                        <button class="btn btn-primary border" type="button" onclick=newGPSObjective()>Add GPS Objective</button>
                        <button class="btn btn-primary border" type="button" onclick=newPhotoObjective()>Add Photo Objective</button>
                    </div>
                    <br>
                    <br>
                    <div id="objectives"></div>
                </div>
                <div class="form-row">
                    <input class="btn btn-primary btn-block" type="submit" name="submit" value="Submit Hunt">
                </div>
            </form>
            <form id="map-window" style="display: none">
                <div id="map"></div>
                <br>
                <div class="btn-group btn-block">
                    <button class="btn btn-primary border" type="button" onclick=submitMap()>Confirm Location</button>
                    <button class="btn btn-primary border" type="button" onclick=hideMap()>Back</button>
                </div>
            </form>
        </div>
    </section>

</main>
<!-- Footer -->
<?php include('templates/footer.php'); ?>
</body>
<?php include('api/google_api_key.php'); ?>
<script type="text/javascript">
    var map;
    var marker;
    var currentObjective;
    var objectiveCounter = 0;

    /**
     * Initialises the google maps API map
     *
     * @author Jakub Kwak
     */
    function init() {
        var defaultLoc = new google.maps.LatLng(50.737341, -3.535180);
        //make map
        map = (new google.maps.Map(document.getElementById("map"), {
            zoom: 15,
            center: defaultLoc
        }));
        //make map marker
        marker = new google.maps.Marker({
            position: defaultLoc,
            title: "Objective"
        });
        //add marker to map
        marker.setMap(map);
        //add a click listener for moving the marker
        map.addListener('click', function (event) {
            marker.setPosition(event.latLng);
        });
        //sets the map location to current location
        setCurrentLocation();
    }

    /**
     * Sets the location of the google maps API map to the user's location
     *
     * @author Jakub Kwak
     */
    function setCurrentLocation() {
        //only does this if geolocation is allowed/supported
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function (position) {
                var pos = {
                    lat: position.coords.latitude,
                    lng: position.coords.longitude
                };
                //center map on user location
                map.setCenter(pos);
                //move marker to user location
                marker.setPosition(pos);
            });
        }
    }

    /**
     * Takes the coordinates of map marker and inserts them into the Longitude/Latitude textboxes
     *
     * @author Jakub Kwak
     */
    function submitMap() {
        var lat = Math.round((marker.getPosition().lat() + Number.EPSILON) * 100000) / 100000;
        var lng = Math.round((marker.getPosition().lng() + Number.EPSILON) * 100000) / 100000;

        document.getElementById("objective" + currentObjective + "Latitude").value = lat;
        document.getElementById("objective" + currentObjective + "Longitude").value = lng;

        hideMap();
    }

    /**
     * Hides the map window
     *
     * @author Jakub Kwak
     */
    function hideMap() {
        document.getElementById("map-window").style.display = "none";
        document.getElementById("create-form").style.display = "block";
    }

    /**
     * Dsiplays the map window and hides the create form
     *
     * @param btnName objective number to update
     * @author Jakub Kwak
     */
    function showMap(objNum) {
        currentObjective = objNum;
        document.getElementById("map-window").style.display = "block";
        document.getElementById("create-form").style.display = "none";
    }

    /**
     * Expand or collapse the objective form
     *
     * @author Michael Freeman
     */
    function expand() {
        content = this.parentNode.querySelector("#content");
        if (content.style.display === "none") {
            content.style.display = "block";
        } else {
            content.style.display = "none";
        }
    }

    /**
     * Add an objective form header
     * @param type - string representing objective type- GPS or PHOTO
     * @returns {HTMLDivElement}
     * @author Michael Freeman
     */
    function newObjective(type) {
        objectiveCounter++;
        var objCard = document.createElement("div");
        newObjective.className = "objective";
        newObjective.id = "objective" + objectiveCounter.toString();
        document.getElementById("objectives").appendChild(objCard);
        objCard.className = "card bg-light";



        //adds expand button
        var expandButton = document.createElement("button");
        expandButton.type = "button";
        expandButton.className = "form-control bg-secondary text-white";
        if (type === "GPS") {
            expandButton.innerHTML = "GPS Objective";
        } else if (type === "PHOTO") {
            expandButton.innerHTML = "Photo Objective";
        }
        expandButton.onclick = expand;

        var content = document.createElement("div");
        content.className = "card-body bg-light";
        content.id = "content";

        objCard.appendChild(expandButton);
        objCard.appendChild(content);

        return objCard;
    }

    /**
     * Adds GPS objective form elements
     *
     * @author Michael Freeman
     */
    function newGPSObjective() {
        var objective = newObjective("GPS");
        var content = objective.querySelector("#content");

        //adds directions textbox
        content.innerHTML += "Directions:<br>";
        var txtBoxDir = document.createElement("input");
        txtBoxDir.type = "text";
        txtBoxDir.className = "form-control";
        txtBoxDir.name = "objective" + objectiveCounter + "Directions";
        txtBoxDir.required = true;
        content.appendChild(txtBoxDir);
        content.appendChild(document.createElement("br"));

        //adds map button
        var mapBtn = document.createElement("button");
        mapBtn.type = "button";
        mapBtn.className = "btn btn-outline-primary btn-block";
        mapBtn.name = objectiveCounter.toString();
        mapBtn.innerHTML = "Select location on map";
        content.appendChild(mapBtn);
        content.appendChild(document.createElement("br"));
        mapBtn.setAttribute("onclick", "showMap(this.name)");

        //adds latitude textbox
        content.innerHTML += "Latitude:<br>";
        var txtBoxLat = document.createElement("input");
        txtBoxLat.type = "number";
        txtBoxLat.step = "0.000001";
        txtBoxLat.className = "form-control";
        txtBoxLat.name = "objective" + objectiveCounter + "Latitude";
        txtBoxLat.id = "objective" + objectiveCounter + "Latitude";
        txtBoxLat.required = true;
        content.appendChild(txtBoxLat);
        content.appendChild(document.createElement("br"));

        //adds longitude textbox
        content.innerHTML += "Longitude:<br>";
        var txtBoxLong = document.createElement("input");
        txtBoxLong.type = "number";
        txtBoxLong.step = "0.000001";
        txtBoxLong.name = "objective" + objectiveCounter + "Longitude";
        txtBoxLong.id = "objective" + objectiveCounter + "Longitude";
        txtBoxLong.className = "form-control";
        txtBoxLong.required = true;
        content.appendChild(txtBoxLong);
        content.appendChild(document.createElement("br"));

        //adds question textbox
        content.innerHTML += "Question:<br>";
        var txtBoxQue = document.createElement("input");
        txtBoxQue.type = "text";
        txtBoxQue.name = "objective" + objectiveCounter + "Question";
        txtBoxQue.className = "form-control";
        txtBoxQue.required = true;
        content.appendChild(txtBoxQue);
        content.appendChild(document.createElement("br"));

        //adds answer textbox
        content.innerHTML += "Answer:<br>";
        var txtBoxAns = document.createElement("input");
        txtBoxAns.type = "text";
        txtBoxAns.name = "objective" + objectiveCounter + "Answer";
        txtBoxAns.className = "form-control";
        txtBoxAns.required = true;
        content.appendChild(txtBoxAns);
        content.appendChild(document.createElement("br"));
    }

    /**
     * Adds Photo objective form elements
     *
     * @author Michael Freemans
     */
    function newPhotoObjective() {
        var objective = newObjective("PHOTO");
        var content = objective.querySelector("#content");

        //Adds description textbox
        content.innerHTML += "Description:<br>";
        var txtBoxDesc = document.createElement("input");
        txtBoxDesc.type = "text";
        txtBoxDesc.name = "objective" + objectiveCounter + "Description";
        txtBoxDesc.className = "form-control";
        txtBoxDesc.required = true;
        content.appendChild(txtBoxDesc);
        content.appendChild(document.createElement("br"));
    }


    window.onload = function(){
        document.getElementById("dberror").innerHTML = dberr;
        document.getElementById("hunterror").innerHTML = hunterr;
    }
</script>
</html>
