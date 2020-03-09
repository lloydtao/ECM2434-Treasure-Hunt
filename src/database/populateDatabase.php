<meta name="author" content = "Michael Freeman">

<?php
include "../www/campustreks/utils/connection.php";
$conn = openCon();

$pass = password_hash('Campustr3k5.69', PASSWORD_DEFAULT);
$sql = "INSERT INTO users (Email, Username, Password, Verified)
VALUES ('campustreks@outlook.com', 'ExeCS', '".$pass."', True);";

if($conn->query($sql)){
    echo "User successfully created <br>";
}else{
    echo "Error creating user: ". $conn->error . "<br>";
}

$sql = "INSERT INTO hunt (Name, Description, Username)
VALUES ('Computer Science induction', 'A hunt for the the induction of computer
 science students', 'ExeCS');";

if($conn->query($sql)){
    echo "Hunt successfully created <br>";
}else{
    echo "Error creating hunt: ". $conn->error . "<br>";
}

$huntId = $conn->insert_id;
$objectiveIds = array();
for($i = 0; $i < 9; $i++){
    if($conn->query("INSERT INTO objectives (HuntID) VALUES (".$huntId.");")){
        $objectiveIds[$i] = $conn->insert_id;
        echo "Objective{$i} successfully added created <br>";
    }else{
        echo "Failed to create Objective{$i} <br>";
    }
}

$location = $conn->prepare("INSERT INTO location (ObjectiveID, HuntOrder, Longitude,
Latitude, Question, Answer, Direction) Values(?, ?, ?, ?, ?, ?, ?);");
$location->bind_param("iiddsss", $id, $i, $long, $lat, $question, $answer, $direction);

$i = 0;
$id = $objectiveIds[$i];
$lat = 50.737477;
$long = -3.532928;
$question = "What is the name of the cafe in Harrison?";
$answer = "The Engine Room";
$direction = "From the Innovation Centre go back along North park road and enter the building that 
are up the steps on the right";
if($location->execute()){
    echo "Location{$i} successfully created <br>";
}else{
    echo "Failed to create location{$i} <br>";
}

$i++;
$id = $objectiveIds[$i];
$lat = 50.736429;
$long = -3.535956;
$question = "Which colours are names of lecture theatres in Newman?";
$answer = "Blue, Purple, Red, Green";
$direction = "From Harrison head back towards the Forum and go to the building at the bend in the road.";
if($location->execute()){
    echo "Location{$i} successfully created <br>";
}else{
    echo "Failed to create location{$i} <br>";
}

$i++;
$id = $objectiveIds[$i];
$lat = 50.735458;
$long = -3.533437;
$question = "Which floor is the Computer Science section on in the Library?";
$answer = "-1";
$direction = "From Peter Chalk enter the Forum and pass through the barriers on the left";
if($location->execute()){
    echo "Location{$i} successfully created <br>";
}else{
    echo "Failed to create location{$i} <br>";
}

$i++;
$id = $objectiveIds[$i];
$lat = 50.735728;
$long = -3.531318;
$question = "Which college is Streatham Court part of?";
$answer = "Business School";
$direction = "Head down Forum hill, cross the road and find a fountain";
if($location->execute()){
    echo "Location{$i} successfully created <br>";
}else{
    echo "Failed to create location{$i} <br>";
}

$i++;
$id = $objectiveIds[$i];
$lat = 50.736313;
$long = -3.531622;
$question = "What is the name of the large lecture theatre in Amory?";
$answer = "Parker Moot";
$direction = "Cross back over the road and go to the building opposite the bus stop";
if($location->execute()){
    echo "Location{$i} successfully created <br>";
}else{
    echo "Failed to create location{$i} <br>";
}

$i++;
$id = $objectiveIds[$i];
$lat = 50.738207;
$long = -3.531090;
$question = "How many buildings are paart of the Innovation Centre?";
$answer = "2";
$direction = "Go up the path to th left of Amory then turn right and go to the building at the end of North Park road";
if($location->execute()){
    echo "Location{$i} successfully created <br>";
}else{
    echo "Failed to create location{$i} <br>";
}

$location->close();
$photo = $conn->prepare("INSERT INTO photoops (ObjectiveID, Specification) VALUES(?, ?);");
$photo->bind_param("is", $id, $spec);

$i++;
$id = $objectiveIds[$i];
$spec = "Take a picture of the Harrision Learning Resource Centre";
if($photo->execute()){
    echo "Photo successfully created <br>";
}else{
    echo "Failed to create photo <br>";
}

$i++;
$id = $objectiveIds[$i];
$spec = "Take a picture of the Forum Hill cat";
if($photo->execute()){
    echo "Photo successfully created <br>";
}else{
    echo "Failed to create photo <br>";
}

$i++;
$id = $objectiveIds[$i];
$spec = "Take a picture of the plaque for the opening of the Guild";
if($photo->execute()){
    echo "Photo successfully created <br>";
}else{
    echo "Failed to create photo <br>";
}

$photo->close();
$conn->close();
?>