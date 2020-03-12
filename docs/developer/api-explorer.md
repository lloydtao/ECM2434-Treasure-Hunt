# API Explorer

## API Methods

### api/check_question - POST

- Purpose: Used to check if the answer is correct. If it is then the hunt session JSON file is updated and score is added to the team.

- Parameters:

	-	objectiveKey – The key used to find the objective in the JSON

	-	objectiveID – ID of the objective in the database

	-	answer – User submitted answer to the question

- Response:

	-	Correct – bool, true if the answer was correct, false if it was incorrect

### /api/create_team – POST

- Purpose: Create a team in the hunt session for the players to join

- Response:

	-	Success – bool, true if team created, false if team was not created

### /api/end_hunt – POST

- Purpose: End the session, store the hunt data in the database and delete the JSON

- Parameters:

	-	gameID – ID of the hunt session to end

	-	highscore – The highest score in the hunt

	-	teamName – Highscoring team

- Return:

	-	Status – OK if session is ended and error if it was not

	-	Message – Message to from the server 

### /api/upload_photo – POST

- Purpose: Uploads a photo submission for a team

- Parameters:

	-	objective_id – ID of the completed photo objective

	-	image – Photo that is submitted (must be jpg or png)

### /api/check_game – GET

- Purpose: Checks if the game exists with a specific pin

 - Return:

	-	status – fail if game does not exis, success if it does

	-	gameID – gameID of the hunt session

	-	nickname – player name

	-	teamName – name of the players team if they are in one

	-	game – active if game still exists, inactive otherwise

### /api/join_game – POST

- Purpose: Adds player information to the hunt session JSON

- Parameters:

	-	pin – pin of the session that the player is trying to join

	-	nickname – player nickname

 - Return: 
	-	pin-error if incorrect pi

	-	join-success if player has joined the team

	-	form-error if the request was incorrect

### /api/join-team – POST

- Purpose: Adds a player to a team in a hunt session

- Parameters:

	-	chosenteam – Team to join in the hunt

- Return:

	-	join-team-success if joined successfully

	-	duplicate name if name already in team

	-	form-error if request is incorrect

### /api/location_description – GET

- Purpose: Fetches the directions to a location from the database

- Parameters

	-	ojbectiveID – database ID of the location objective to fetch

- Return

	-	Value for the direction stored in the db

### /api/objective_question – GET

- Purpose: Fetches the GPS objective question associated with the objective

- Parameters

	-	ojbectiveID – database ID of the location objective question to fetch

- Return: 

	-	Question that is linked to the location from the db

### /api/play_game – GET

- Purpose: Sets the game as active in the php session

- Return:

	-	play-game-success if game set as active

	-	game-already-started if game was already active

### /api/quit_game – POST

- Purpose: Removes the player from their team and deletes the team if there are no more players left in it

- Return 
	-	game-ended if the player has quit

	-	form-error if the request is invalid

