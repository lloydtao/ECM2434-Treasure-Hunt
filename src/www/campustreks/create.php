<html>
  <head>
	<meta name="author" content = "Michael Freeman">
	<style>.error {color: #FF0000;}</style>
    <title>Create - CampusTreks</title>
	<?php include('templates/head.php'); ?>
    <?php
    // Redirect to login.php if not already logged in
    include "checklogin.php";
    if (!CheckLogin()) {
      header("location:login.php");
    }
    ?>
	<?php
	include "utils/connection.php";
	$conn = openCon();
	$user = $_SESSION["username"];
	?>
	<?php 
	$titleErr = $descriptionErr = "";
	$objectives = 1;
	$sql = "";
	$title = $description = "";
	
	/**
	 *Removes whitespace, slashes && special characters from strings
	 *@param string $data
	 *@return string $data
	*/
	function makeSafe($data)
	{
		$data = trim($data);
		$data = stripslashes($data);
		$data = htmlspecialchars($data);

		return $data;
	}

	// Only run if the submit button has been pressed
	if(isset($_POST['submit'])){
		
		$title = makeSafe($_POST["title"]);
		$description = makeSafe($_POST["description"]);
		
		// Count how many objectives have been added
		while(array_key_exists("objective{$objectives}Name", $_POST))
			$objectives++;
		
		
		// Check the hunt title and description have been set
		if(!$title || !$description){
			if(!$title)
				$titleErr = "Required field";
			if(!$description)
				$descriptionErr = "Required field";
			if($objectives == 1)
				echo "<script type='text/javascript'>alert('At least one objective is needed');</script>";
		}
		else{
			if($objectives == 1){
				echo "<script type='text/javascript'>alert('At least one objective is needed');</script>";
			}else{
				
				$locations = 0;
				$logitude = $latitude = $question = $answer = $photoDescription = "";
				
				// Create the hunt in the database
				$sql = "INSERT INTO Hunt (Name, Description, Username)
				VALUES('$title', '$description', '$username');";
				
				if($conn->query($sql) === TRUE) {
					$hunt_id = $conn->insert_id;
				}else {
					echo "<script type='text/javascript'>alert('".$conn->error."');</script>";
				}
				
				for($x = 1; $x < $objectives; $x++){
					
					// Add new objective to database
					if($conn->query("INSERT INTO Objectives (HuntID) Values('$hunt_id')") === TRUE) {
						$last_id = $conn->insert_id;
					}else {
						echo "<script type='text/javascript'>alert('".$conn->error."');</script>";
					}
					
					// Check which type each objective is and add a SQL statement to add to the correct table
					if(array_key_exists("objective{$x}Longitude", $_POST)){
								
						// Make the attributes database safe
						$longitude = (string)$_POST["objective{$x}Longitude"];
						$latitude = (string)$_POST["objective{$x}Latitude"];
						$question = makeSafe($_POST["objective{$x}Question"]);
						$answer = makeSafe($_POST["objective{$x}Answer"]);
                        $directions = (string)$_POST["objective{$x}Directions"];
						
						if($logitude!="" && $latitude!="" && $question!="" && $answer!="" && $directions!="");
							continue;
						
						// Add Location to database
						$sql = "INSERT INTO Location (ObjectiveID, HuntOrder, Longitude, Latitude, Question, Answer, Direction)
						VALUES('$last_id', '$locations', '$longitude', '$latitude', '$question', '$answer', '$directions');";
						
						if($conn->query($sql) === TRUE)
							$locations++;
						else {
							echo "<script type='text/javascript'>alert('".$conn->error."');</script>";
						}
						
					}else{
						
						$photoDescription = makeSafe($_POST["objective{$x}Description"]);
						if(!$photoDescription)
							continue;
						
						// Add photo objective to database
						$sql = "INSERT INTO PhotoOps (ObjectiveID, Specification)
						VALUES('$last_id', '$photoDescription');";
						
						if($conn->query($sql) === FALSE)
							echo "<script type='text/javascript'>alert('".$conn->error."');</script>";
					}
					
				}
				
				$conn->close();
				header("Location: host.php");
			}
		}
	}
	?>

  </head>
  <body>
    <script>
            var objectiveCounter = 0;

            function expand(){
                content = this.parentNode.querySelector("#content");
                if(content.style.display === "none"){
                    content.style.display = "block";
                }
                else{
                    content.style.display = "none";
                }
            }

            function newObjective(){
                objectiveCounter++;
                var newObjective = document.createElement("div");
                newObjective.className = "objective";
                newObjective.id = "objective"+objectiveCounter.toString();
                document.getElementById("objectives").appendChild(newObjective);

                var expandButton = document.createElement("button");
                expandButton.type = "button";
                expandButton.className = "form-control";
                expandButton.innerHTML = "Objective "+objectiveCounter.toString();
                expandButton.onclick = expand;

                var content = document.createElement("div");
                content.id = "content";
                
                var txtBoxName = document.createElement("input");
                txtBoxName.type = "text";
                txtBoxName.name = "objective" + objectiveCounter + "Name";
                txtBoxName.className = "form-control";
                txtBoxName.placeholder = "Add a name for the objective"
                content.appendChild(txtBoxName);
                content.appendChild(document.createElement("br"));

                newObjective.appendChild(expandButton);
                newObjective.appendChild(content);
                
                return newObjective;
            }

            function newGPSObjective(){
                var objective = newObjective();
                var content = objective.querySelector("#content");

                content.innerHTML += "Directions:<br>";
                var txtBoxDir = document.createElement("input");
                txtBoxDir.type = "text";
                txtBoxDir.className = "form-control";
                txtBoxDir.name = "objective" + objectiveCounter + "Directions";
                content.appendChild(txtBoxDir);
                content.appendChild(document.createElement("br"));

                content.innerHTML += "Latitude:<br>";
                var txtBoxLat = document.createElement("input");
                txtBoxLat.type = "number";
                txtBoxLat.className = "form-control";
                txtBoxLat.name = "objective" + objectiveCounter + "Latitude";
                content.appendChild(txtBoxLat);
                content.appendChild(document.createElement("br"));
                
                content.innerHTML += "Longitude:<br>";
                var txtBoxLong = document.createElement("input");
                txtBoxLong.type = "number";
                txtBoxLong.name = "objective" + objectiveCounter + "Longitude";
                txtBoxLong.className = "form-control";
                content.appendChild(txtBoxLong);
                content.appendChild(document.createElement("br"));
				
				content.innerHTML += "Question:<br>";
                var txtBoxLong = document.createElement("input");
                txtBoxLong.type = "text";
                txtBoxLong.name = "objective" + objectiveCounter + "Question";
                txtBoxLong.className = "form-control";
                content.appendChild(txtBoxLong);
                content.appendChild(document.createElement("br"));
				
				content.innerHTML += "Answer:<br>";
                var txtBoxLong = document.createElement("input");
                txtBoxLong.type = "text";
                txtBoxLong.name = "objective" + objectiveCounter + "Answer";
                txtBoxLong.className = "form-control";
                content.appendChild(txtBoxLong);
                content.appendChild(document.createElement("br"));
            }

            function newPhotoObjective(){
                var objective = newObjective();
                var content = objective.querySelector("#content");
                
                var txtBoxDesc = document.createElement("input");
                txtBoxDesc.type = "text";
                txtBoxDesc.name = "objective" + objectiveCounter + "Description";
                txtBoxDesc.className = "form-control";
                txtBoxDesc.placeholder = "Add a short description of what should be achieved in the photo"
                content.appendChild(txtBoxDesc);
                content.appendChild(document.createElement("br"));
            }
        </script>
	<!-- Header -->
	<?php include('templates/header.php'); ?>
	<!-- Content -->
    <main class="page create-page">
        <section class="portfolio-block hire-me">
            <div class="container">
                <div class="heading">
                    <h2>Create A Hunt</h2>
                </div>
				
                <form  method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
					<p><span class="error">* required field</span></p>
                    <div class="form-group">
						<label for="title">Title</label><span class="error">*<?php echo $titleErr;?></span><br>
						<input class="form-control" type="text" name="title" value = <?php echo $title;?>>
					</div>
                    <div class="form-group">
						<label for="description">Description</label><span class="error">*<?php echo $descriptionErr;?></span><br>
						<textarea class="form-control form-control-lg" name="description"><?php echo $description;?></textarea>
					</div>
                    <div class="form-group">
						<label for="objectives">Objectives</label><br>
						<button class="btn btn-primary" type="button" onclick = newGPSObjective()>Add GPS Objective</button>
                        <button class="btn btn-primary" type="button" onclick = newPhotoObjective()>Add Photo Objective</button><br>
                        <br><div id = "objectives"></div>
					</div>
                    <div class="form-row">
						<div class="col-md-6 button">
							<input class="btn btn-primary btn-block" type="submit" name = "submit">
						</div>
                    </div>
                    </div>
                </form>
            </div>
        </section>
    </main>
	<!-- Footer -->
	<?php include('templates/footer.php'); ?>
  </body>
</html>