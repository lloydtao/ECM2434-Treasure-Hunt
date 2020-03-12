<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta name="author" content = "Marek Tancak">
      <meta name="contributors" content = "Jakub Kwak">
    <title>Host - CampusTreks</title>
    <?php include('templates/head.php'); ?>
  </head>
  <body>
    <!-- Header -->
    <?php include('templates/header.php'); ?>
    <!-- Content -->
    <div id="host">
        <main class="page host-page">
            <section class="portfolio-block project-no-images">
                    <div v-if="!huntstarted">
                        <start-hunt @hunt-started="sessionIntervalStart()"></start-hunt>
                    </div>
                    <div v-else>
                        <hunt-session 
                            :jsondata="jsondata"
                            :gameid="gameid" 
                            :username="username"
                            @end-hunt="endHunt()"
                            @best-team="bestteam = $event"
                            @high-score="highscore = $event">
                        </hunt-session>
                    </div>
            </section>
        </main>
    </div>
    <!-- Footer -->
    <?php include('templates/footer.php'); ?>

</body>

</html>

<script src="js/jquery-3.4.1.min.js"></script>
<script src="js/vue.min.js"></script>
<script src="js/host.js"></script>
