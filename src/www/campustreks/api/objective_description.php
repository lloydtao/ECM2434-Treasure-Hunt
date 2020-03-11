<?php
include "../utils/connection.php";

/**
 * Get all descriptions for a set of photo objectives
 * @param $objectiveIDs
 * @return false|string
 *
 * @author Marek Tancak
 * @contributor Jakub Kwak
 */
function getDescription($objectiveIDs) {
	$conn = openCon();
	$descriptions = [];
	foreach ($objectiveIDs as $objectiveID) {
        $sql = $conn->prepare("SELECT `Specification` FROM `photoops` WHERE `ObjectiveID` = ?");
        $sql->bind_param("i", $objectiveID);
        $sql->execute();

        $result = $sql->get_result();

        if ($result->num_rows > 0) {
            $descriptions[] = ($result->fetch_assoc())["Specification"];
        }
    }
	return json_encode($descriptions);
}
if (isset($_GET['objectiveIDs'])) {
    echo getDescription(explode(",",$_GET['objectiveIDs']));
}
?>