<html>
  <head>
    <title>Create - CampusTreks</title>
	<?php include('templates/head.php'); ?>
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

                newObjective.appendChild(expandButton);
                newObjective.appendChild(content);
                
                return newObjective;
            }

            function newGPSObjective(){
                var objective = newObjective();
                var content = objective.querySelector("#content");
                
                var txtBoxName = document.createElement("input");
                txtBoxName.type = "text";
                txtBoxName.name = "name";
                txtBoxName.className = "form-control";
                txtBoxName.placeholder = "Add a name for the GPS objective"
                content.appendChild(txtBoxName);
                content.appendChild(document.createElement("br"));

                content.innerHTML += "Latitude:<br>";
                var txtBoxLat = document.createElement("input");
                txtBoxLat.type = "number";
                txtBoxLat.className = "form-control";
                txtBoxLat.name = "latitude";
                content.appendChild(txtBoxLat);
                content.appendChild(document.createElement("br"));
                
                content.innerHTML += "Longitude:<br>";
                var txtBoxLong = document.createElement("input");
                txtBoxLong.type = "number";
                txtBoxLong.name = "Longitude";
                txtBoxLong.className = "form-control";
                content.appendChild(txtBoxLong);
                content.appendChild(document.createElement("br"));
            }

            function newPhotoObjective(){
                var objective = newObjective();
                var content = objective.querySelector("#content");
                
                var txtBoxName = document.createElement("input");
                txtBoxName.type = "text";
                txtBoxName.name = "name";
                txtBoxName.className = "form-control";
                txtBoxName.placeholder = "Add a name for the photo objective"
                content.appendChild(txtBoxName);
                content.appendChild(document.createElement("br"));
                
                var txtBoxDesc = document.createElement("input");
                txtBoxDesc.type = "text";
                txtBoxDesc.name = "description";
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
                <form>
                    <div class="form-group">
						<label for="title">Title</label><br>
						<input class="form-control" type="text" name="title">
					</div>
                    <div class="form-group">
						<label for="location">Location</label><br>
						<input class="form-control" type="text" name="location">
					</div>
                    <div class="form-group">
						<label for="description">Description</label><br>
						<textarea class="form-control form-control-lg" name="Description"></textarea>
					</div>
                    <div class="form-group">
						<label for="objectives">Objectives</label><br>
						<button class="btn btn-primary" type="button" onclick = newGPSObjective()>Add GPS Objective</button>
                        <button class="btn btn-primary" type="button" onclick = newPhotoObjective()>Add Photo Objective</button><br>
                        <br><div id = "objectives"></div>
					</div>
                    <div class="form-group">
                        <div class="form-row">
                            <div class="col-md-6">
								<label for="hire-date">Estimated Duration</label><br>
								<input class="form-control" type="number">
							</div>
								<div class="col-md-6 button">
								<button class="btn btn-primary btn-block" type="submit">Submit Hunt</button>
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
