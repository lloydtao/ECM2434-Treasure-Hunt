<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8"/>
        <link rel="stylesheet" href="/css/stylesheet.css"/>
        <link rel="stylesheet" href="/css/new_hunt_stylesheet.css"/>
        <title>New Hunt - CampusTreks</title>
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
				txtBoxName.placeholder = "Add a name for the GPS objective"
                content.appendChild(txtBoxName);
                content.appendChild(document.createElement("br"));

                content.innerHTML += "Latitude:<br>";
                var txtBoxLat = document.createElement("input");
                txtBoxLat.type = "number";
                txtBoxLat.name = "latitude";
                content.appendChild(txtBoxLat);
                content.appendChild(document.createElement("br"));
                
                content.innerHTML += "Longitude:<br>";
                var txtBoxLat = document.createElement("input");
                txtBoxLat.type = "number";
                txtBoxLat.name = "Longitude";
                content.appendChild(txtBoxLat);
                content.appendChild(document.createElement("br"));
            }

            function newPhotoObjective(){
                var objective = newObjective();
                var content = objective.querySelector("#content");
                
				var txtBoxName = document.createElement("input");
                txtBoxName.type = "text";
                txtBoxName.name = "name";
				txtBoxName.placeholder = "Add a name for the photo objective"
                content.appendChild(txtBoxName);
                content.appendChild(document.createElement("br"));
				
                var txtBoxDesc = document.createElement("input");
                txtBoxDesc.type = "text";
                txtBoxDesc.name = "description";
				txtBoxDesc.placeholder = "Add a short description of what should be achieved in the photo"
                content.appendChild(txtBoxDesc);
                content.appendChild(document.createElement("br"));
            }
        </script>
        <h1>CampusTreks</h1>
        <h3>New Hunt</h3>
        <form>
            <div id = "newHuntFormLeft">
                Hunt Name:<br>
                <input type = "text" name = "huntName"><br>
                <button type = "button" class = "button" onclick = "newGPSObjective()">New GPS Objective</button><br>
                <button type = "button" class = "button" onclick = "newPhotoObjective()">New Photo Objective</button><br>
				<button type = "submit" class = "button">Save Hunt</button><br>
            </div>
            <div id = "newHuntFormRight">
                Objectives<br>
                <div id = "objectives"></div>
            </div>
        </form>
    </body>
</html>