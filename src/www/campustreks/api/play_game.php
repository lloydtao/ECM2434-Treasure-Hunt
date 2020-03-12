<?php

/**
 * Check if game is in session, if not add it
 * @author James Caddock
 */
function playGame() {
    session_start();

    if(isset($_SESSION['game'])) {
        echo "game-already-started";
        return;
    }

    $_SESSION['game'] = "active";
    echo "play-game-success";
    return;
}

playGame()
?>