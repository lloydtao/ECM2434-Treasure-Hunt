<?php
/**
 * Script for checking if any hunts hosted by the use already exist
 *
 * @author Jakub Kwak
 */

/**
 * Check if any hunts exist with $user as master and returns a hunt if one exists
 *
 * @param string $user
 * @return string
 */
function checkHunts($user) {
	if (is_dir("hunt_sessions/")) {
		$hunts = scandir("hunt_sessions/");
		foreach($hunts as $hunt) {
			if ($hunt != "." && $hunt != "..") {
				$jsonData = json_decode(file_get_contents("hunt_sessions/".$hunt), true);
				if ($jsonData["gameinfo"]["master"] == $user) {
					return $jsonData["gameinfo"]["gamePin"];
				}
			}
		}
	}
    return null;
}