<?php session_start();?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
  <meta name="author" content = "James Caddock">
	<meta name="contributors" content="Jakub Kwak, Joseph Lintern">
    <?php include('templates/head.php'); ?>
  </head>

  <body>
    <main class="page">
      <section class="portfolio-block hire-me">
        <div id="play"> 
          <div v-if="togglecomponent==0">
            <game-start @start-game="startGame()"></game-start>
          </div>
          <div v-else-if="togglecomponent==1">
            <team-table 
            @fetch-json="fetchJson()" 
            :jsondata="jsondata" 
            @toggle-component="togglecomponent = $event" 
            @in-team="currentteam = $event" 
            :currentteam="currentteam"> 
            </team-table>
          </div>
          <div v-else-if="togglecomponent==2">
            <create-team 
            @team-exit="togglecomponent=1"
            @team-made="currentteam = $event">
            </create-team>
          </div>
          <div v-else>
            <photo-submit
            :currentteam="currentteam"
            :pin="pin"
            :huntsessiondata="jsondata"
            @return-table="togglecomponent=1">
            </photo-submit>
          </div>
        </div>
      </section>
    </main>
  </body>
</html>


<script src="../js/jquery-3.4.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
<!--<script src="../js/vue.min.js"></script>-->
<script type="module" src="../js/play.js"></script>