<?php
/**
 * Script for handling user log out
 * @author Jakub Kwak
 */
session_start();
if(session_destroy()) {// Destroys session
  header("Location: index.php");// If successful, go back to home page
}
