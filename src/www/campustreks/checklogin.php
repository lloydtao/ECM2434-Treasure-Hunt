<?php
/**
 * Opens session and checks if the user is logged in
 *
 * @return boolean true if logged in, otherwise false
 */
function CheckLogin()
{
    session_start();
    if (!isset($_SESSION['isLoggedIn']) || $_SESSION['isLoggedIn'] == false) {
        return false;
    }
    return true;
}
