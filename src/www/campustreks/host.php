<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta name="author" content = "Marek Tancak">
      <meta name="contributors" content = "Jakub Kwak">
    <title>Host - CampusTreks</title>
    <link rel="stylesheet" href="css/hunt_session_stylesheet.css">
    <?php include('templates/head.php'); ?>
  </head>
  <body>
    <!-- Header -->
    <?php include('templates/header.php'); ?>
    <!-- Content -->
    <div id="host">
        <main class="page host-page">
            <section class="portfolio-block project-no-images">
                <div class="container">
                    <div v-if="!huntstarted">
                        <start-hunt @hunt-started="sessionIntervalStart()"></start-hunt>
                    </div>
                    <div v-else>
                        <hunt-session @hunt-ended="huntstarted=false" :jsondata="jsondata"
                                        :gameid="gameid" :username="username">
                        </hunt-session>
                    </div>
                </div>
            </section>
        </main>
    </div>
    <!-- Footer -->
    <?php include('templates/footer.php'); ?>

</body>

</html>

<script src="js/jquery-3.4.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
<!--<script src="js/vue.min.js"></script>-->
<script src="js/host.js"></script>
