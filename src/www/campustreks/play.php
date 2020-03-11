<?php session_start();?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
    <head>
        <meta name="author" content="James Caddock">
        <meta name="contributors" content="Jakub Kwak, Joseph Lintern">
        <?php include('templates/head.php'); ?>
    </head>
    <body>
        <!-- Header -->
        <?php include('templates/header_mobile.php'); ?>
        <!-- Content -->
        <main class="page">
            <div id="play">
                <div v-if="togglecomponent==0">
                    <game-start @start-game="startGame()"></game-start>
                </div>
                <div v-else-if="togglecomponent==1">
                    <team-table @fetch-json="fetchJson()" :jsondata="jsondata"
                        @toggle-component="togglecomponent = $event" @in-team="currentteam = $event"
                        :currentteam="currentteam" @quit-game="quitGame()">
                    </team-table>
                </div>
                <div v-else-if="togglecomponent==2">
                    <create-team @team-exit="togglecomponent=1" @team-made="currentteam = $event">
                    </create-team>
                </div>
                <div v-else-if="togglecomponent==3">
                    <location @photo-submit="togglecomponent=4" :jsondata="jsondata"
                    :currentteam="currentteam" @quit-game="quitGame()">
                    </location>
                </div>
                <div v-else-if="togglecomponent==4">
                    <photo-submit :currentteam="currentteam" :pin="pin" :huntsessiondata="jsondata"
                        @return-table="togglecomponent=3">
                    </photo-submit>
                </div>
                <div v-else>
                    <div class="card">
                        <div class="card-header">
                            <h5>Game Finished</h5>
                        </div>
                        <div class="card-body">
                            <button type="button" class='btn btn-outline-primary' @click="togglecomponent=0" >Play Another</button>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </body>

<script src="js/jquery-3.4.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
<!--<script src="../js/vue.min.js"></script>-->
<script type="module" src="js/play.js"></script>

</html>
