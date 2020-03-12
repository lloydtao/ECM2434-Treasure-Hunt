<meta name="author" content = "Michael Freeman">

<?php
include "../www/campustreks/utils/connection.php";
$conn = openCon();

/** User Creation **/
$users = array(
    array("email" => 'campustreks@outlook.com', "username" => 'ExeCS', "pass" => password_hash('Campustr3k5.69', PASSWORD_DEFAULT), "verified" => 1),
    array("email" => 'exeta@uni.ac.uk', "username" => 'UoE', "pass" => password_hash('UoE2020', PASSWORD_DEFAULT), "verified" => 1),
    array("email" => 'johnmcglove@hotmail.co.uk', "username" => 'JohnM', "pass" => password_hash('John21.3', PASSWORD_DEFAULT), "verified" => 0),
    array("email" => 'jeffbeozos@amazon.com', "username" => 'AWS1', "pass" => password_hash('Ama50N.1', PASSWORD_DEFAULT), "verified" => 0),
    array("email" => 'david.wakeling@exeter.ac.uk', "username" => 'D.Wakeling', "pass" => password_hash('wak3l1Ng', PASSWORD_DEFAULT), "verified" => 1)
);

foreach ($users as $user) {
    $email = $user["email"];
    $username = $user["username"];
    $pass = $user["pass"];
    $verified = $user["verified"];

    $sql = "INSERT INTO users (Email, Username, Password, Verified)
    VALUES ('$email', '$username', '$pass', '$verified');";

    if($conn->query($sql)){
        echo "User successfully created <br>";
    }else{
        echo "Error creating user: ". $conn->error . "<br>";
    }
}


/** Hunt Creation **/
$hunts = array(
    array("id" => 1, "name" => 'Computer Science Induction', "description" => 'A hunt for the induction of computer science students.', "username" => 'ExeCS'),
    array("id" => 2, "name" => 'Campus Tours', "description" => 'A trail that will get students familiar with the campus in absolutely NO TIME!', "username" => 'UoE'),
    array("id" => 3, "name" => 'Best Campus Spots', "description" => 'I really think students miss out on the true highlights of campus. No longer.', "username" => 'JohnM'),
    array("id" => 4, "name" => 'Strictly No Caching', "description" => 'I made this trek in the hopes that it will teach students how to traverse campus, INCREDIBLY FAST!', "username" => 'D.Wakeling'),
    array("id" => 5, "name" => 'Campus Highlights', "description" => 'We love our campus, and we want you to love it too! This hunt definitely helps.', "username" => 'UoE'),
    array("id" => 6, "name" => 'The Beauty of Exeter', "description" => 'Exeter is a lovely city - so why not explore it whilst having fun?', "username" => 'JohnM')
);

foreach ($hunts as $hunt) {
    $name = $hunt["name"];
    $description = $hunt["description"];
    $username = $hunt["username"];

    $sql = "INSERT INTO hunt (Name, Description, Username)
    VALUES ('$name', '$description', '$username');";

    if($conn->query($sql)){
        echo "Hunt successfully created <br>";
    }else{
        echo "Error creating hunt: ". $conn->error . "<br>";
    }
}



/** Objective Creation **/
$objectives = array(
    array("id" => 1, "objnum" => 9),
    array("id" => 2, "objnum" => 6),
    array("id" => 3, "objnum" => 9),
    array("id" => 4, "objnum" => 7),
    array("id" => 5, "objnum" => 9),
    array("id" => 6, "objnum" => 10)
);

$start_number = 0;
$total_obj = 0;

foreach ($objectives as $objective) {
    $huntId = $objective["id"];

    for($i = $start_number; $i < $objective["objnum"] + $start_number; $i++){
        if($conn->query("INSERT INTO objectives (HuntID) VALUES ('$huntId');")){
            $total_obj += 1;
            echo "Objective successfully created <br>";
        }else{
            echo "Failed to create Objective <br>";
        }
    }
    $start_number += $i;
}


/** Location Creation **/
$locations = array( 
    array("1" => 
        array("id" => "1", "lat" => 50.737477, "lon" => -3.532928, "question" => "What is the name of the cafe in Harrison?", "answer" => "The Engine Room",
            "direction" => "From the Innovation Centre go back along North park road and enter the building that are up the steps on the right"),
        array("id" => "2","lat" => 50.736429, "lon" => -3.535956, "question" => "Which colours are names of lecture theatres in Newman?", "answer" => "Blue, Purple, Red, Green",
            "direction" => "From Harrison head back towards the Forum and go to the building at the bend in the road."),
        array("id" => "3","lat" => 50.735458, "lon" => -3.533437, "question" => "Which floor is the Computer Science section on in the Library?", "answer" => "-1",
            "direction" => "From Peter Chalk enter the Forum and pass through the barriers on the left"),
        array("id" => "4","lat" => 50.735728, "lon" => -3.531318, "question" => "Which college is Streatham Court part of?", "answer" => "Business School",
            "direction" => "Head down Forum hill, cross the road and find a fountain"),
        array("id" => "5","lat" => 50.736313, "lon" => -3.531622, "question" => "What is the name of the large lecture theatre in Amory?", "answer" => "Parker Moot",
            "direction" => "Cross back over the road and go to the building opposite the bus stop"),
        array("id" => "6","lat" => 50.738207, "lon" => -3.531090, "question" => "How many buildings are paart of the Innovation Centre?", "answer" => "2",
            "direction" => "Go up the path to th left of Amory then turn right and go to the building at the end of North Park road")
            ),
    array("2" => 
        array("id" => "10","lat" => 50.737477, "lon" => -3.532928, "question" => "What is the name of the cafe in Harrison?", "answer" => "The Engine Room",
            "direction" => "From the Innovation Centre go back along North park road and enter the building that are up the steps on the right"),
        array("id" => "11","lat" => 50.736429, "lon" => -3.535956, "question" => "Which colours are names of lecture theatres in Newman?", "answer" => "Blue, Purple, Red, Green",
            "direction" => "From Harrison head back towards the Forum and go to the building at the bend in the road."),
        array("id" => "12","lat" => 50.735458, "lon" => -3.533437, "question" => "Which floor is the Computer Science section on in the Library?", "answer" => "-1",
            "direction" => "From Peter Chalk enter the Forum and pass through the barriers on the left"),
        array("id" => "13","lat" => 50.735728, "lon" => -3.531318, "question" => "Which college is Streatham Court part of?", "answer" => "Business School",
            "direction" => "Head down Forum hill, cross the road and find a fountain"),
        array("id" => "14","lat" => 50.736313, "lon" => -3.531622, "question" => "What is the name of the large lecture theatre in Amory?", "answer" => "Parker Moot",
            "direction" => "Cross back over the road and go to the building opposite the bus stop"),
        array("id" => "15","lat" => 50.738207, "lon" => -3.531090, "question" => "How many buildings are paart of the Innovation Centre?", "answer" => "2",
            "direction" => "Go up the path to th left of Amory then turn right and go to the building at the end of North Park road")
    ),
    array("3" => 
        array("id" => "16","lat" => 50.737477, "lon" => -3.532928, "question" => "What is the name of the cafe in Harrison?", "answer" => "The Engine Room",
            "direction" => "From the Innovation Centre go back along North park road and enter the building that are up the steps on the right"),
        array("id" => "17","lat" => 50.736429, "lon" => -3.535956, "question" => "Which colours are names of lecture theatres in Newman?", "answer" => "Blue, Purple, Red, Green",
            "direction" => "From Harrison head back towards the Forum and go to the building at the bend in the road."),
        array("id" => "18","lat" => 50.735458, "lon" => -3.533437, "question" => "Which floor is the Computer Science section on in the Library?", "answer" => "-1",
            "direction" => "From Peter Chalk enter the Forum and pass through the barriers on the left"),
        array("id" => "19","lat" => 50.735728, "lon" => -3.531318, "question" => "Which college is Streatham Court part of?", "answer" => "Business School",
            "direction" => "Head down Forum hill, cross the road and find a fountain"),
        array("id" => "20","lat" => 50.736313, "lon" => -3.531622, "question" => "What is the name of the large lecture theatre in Amory?", "answer" => "Parker Moot",
            "direction" => "Cross back over the road and go to the building opposite the bus stop"),
        array("id" => "21","lat" => 50.738207, "lon" => -3.531090, "question" => "How many buildings are paart of the Innovation Centre?", "answer" => "2",
            "direction" => "Go up the path to th left of Amory then turn right and go to the building at the end of North Park road")
    ),
    array("4" => 
        array("id" => "25","lat" => 50.737477, "lon" => -3.532928, "question" => "What is the name of the cafe in Harrison?", "answer" => "The Engine Room",
            "direction" => "From the Innovation Centre go back along North park road and enter the building that are up the steps on the right"),
        array("id" => "26","lat" => 50.736429, "lon" => -3.535956, "question" => "Which colours are names of lecture theatres in Newman?", "answer" => "Blue, Purple, Red, Green",
            "direction" => "From Harrison head back towards the Forum and go to the building at the bend in the road."),
        array("id" => "27","lat" => 50.735458, "lon" => -3.533437, "question" => "Which floor is the Computer Science section on in the Library?", "answer" => "-1",
            "direction" => "From Peter Chalk enter the Forum and pass through the barriers on the left"),
        array("id" => "28","lat" => 50.735728, "lon" => -3.531318, "question" => "Which college is Streatham Court part of?", "answer" => "Business School",
            "direction" => "Head down Forum hill, cross the road and find a fountain"),
        array("id" => "29","lat" => 50.736313, "lon" => -3.531622, "question" => "What is the name of the large lecture theatre in Amory?", "answer" => "Parker Moot",
            "direction" => "Cross back over the road and go to the building opposite the bus stop"),
        array("id" => "30","lat" => 50.738207, "lon" => -3.531090, "question" => "How many buildings are paart of the Innovation Centre?", "answer" => "2",
            "direction" => "Go up the path to th left of Amory then turn right and go to the building at the end of North Park road")
    ),
    array("5" => 
        array("id" => "32","lat" => 50.737477, "lon" => -3.532928, "question" => "What is the name of the cafe in Harrison?", "answer" => "The Engine Room",
            "direction" => "From the Innovation Centre go back along North park road and enter the building that are up the steps on the right"),
        array("id" => "33","lat" => 50.736429, "lon" => -3.535956, "question" => "Which colours are names of lecture theatres in Newman?", "answer" => "Blue, Purple, Red, Green",
            "direction" => "From Harrison head back towards the Forum and go to the building at the bend in the road."),
        array("id" => "34","lat" => 50.735458, "lon" => -3.533437, "question" => "Which floor is the Computer Science section on in the Library?", "answer" => "-1",
            "direction" => "From Peter Chalk enter the Forum and pass through the barriers on the left"),
        array("id" => "35","lat" => 50.735728, "lon" => -3.531318, "question" => "Which college is Streatham Court part of?", "answer" => "Business School",
            "direction" => "Head down Forum hill, cross the road and find a fountain"),
        array("id" => "36","lat" => 50.736313, "lon" => -3.531622, "question" => "What is the name of the large lecture theatre in Amory?", "answer" => "Parker Moot",
            "direction" => "Cross back over the road and go to the building opposite the bus stop"),
        array("id" => "37","lat" => 50.738207, "lon" => -3.531090, "question" => "How many buildings are paart of the Innovation Centre?", "answer" => "2",
            "direction" => "Go up the path to th left of Amory then turn right and go to the building at the end of North Park road")
    ),
    array("6" => 
        array("id" => "41","lat" => 50.737477, "lon" => -3.532928, "question" => "What is the name of the cafe in Harrison?", "answer" => "The Engine Room",
            "direction" => "From the Innovation Centre go back along North park road and enter the building that are up the steps on the right"),
        array("id" => "42","lat" => 50.736429, "lon" => -3.535956, "question" => "Which colours are names of lecture theatres in Newman?", "answer" => "Blue, Purple, Red, Green",
            "direction" => "From Harrison head back towards the Forum and go to the building at the bend in the road."),
        array("id" => "43","lat" => 50.735458, "lon" => -3.533437, "question" => "Which floor is the Computer Science section on in the Library?", "answer" => "-1",
            "direction" => "From Peter Chalk enter the Forum and pass through the barriers on the left"),
        array("id" => "44","lat" => 50.735728, "lon" => -3.531318, "question" => "Which college is Streatham Court part of?", "answer" => "Business School",
            "direction" => "Head down Forum hill, cross the road and find a fountain"),
        array("id" => "45","lat" => 50.736313, "lon" => -3.531622, "question" => "What is the name of the large lecture theatre in Amory?", "answer" => "Parker Moot",
            "direction" => "Cross back over the road and go to the building opposite the bus stop"),
        array("id" => "46","lat" => 50.738207, "lon" => -3.531090, "question" => "How many buildings are paart of the Innovation Centre?", "answer" => "2",
            "direction" => "Go up the path to th left of Amory then turn right and go to the building at the end of North Park road")
    )
);



foreach ($locations as $location) {
    $i = 0;
    foreach ($location as $objective) { 
        $location = $conn->prepare("INSERT INTO location (ObjectiveID, Longitude,
            Latitude, Question, Answer, Direction) Values(?, ?, ?, ?, ?, ?, ?);");
        $location->bind_param("iiddsss", $id, $long, $lat, $question, $answer, $direction);

        $id = $objective['id'];
        $lat = $objective['lat'];
        $long = $objective['lon'];
        $question = $objective['question'];
        $answer = $objective['answer'];
        $direction = $objective['direction'];
        if($location->execute()){
            echo "Location{$i} successfully created <br>";
        }else{
            echo "Failed to create location{$i} <br>";
        }
        $i++;
    }
}

$location->close();

/** Photo Creation **/
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