<!DOCTYPE html>
<html>
  <head>
    <meta name="author" content = "Marek Tancak">
    <title>Submit Photo - CampusTreks</title>
  </head>
  <body>
    <!-- Header -->
    <?php include('templates/header_mobile.php'); ?>
    <!-- Content -->
    <main class="page host-page">
        <section class="portfolio-block project-no-images">
            <div class="container">
                <div class="heading">
                    <h2>Submit location</h2>
                </div>
                <div class="content">
                    <div id="objectives">
                        <?php
                        /*
                        if(isset($_GET['objective'])){
                            echo '<img width=500px v-if="(objectives[\'objective1\'][\'completed\'])" v-bind:src="(objectives[\'objective1\'][\'path\'])">';
                            echo '<form method="post" action="/new_submit_photo.php?objective_id='.$_GET['objective'].'" enctype="multipart/form-data">';
                            echo '    Select image to upload:<br>';
                            echo '<input type="file" name="image" /><br>';
                            echo '<input type="submit" value="submit" name="submit">';
                            echo '</form>';
                            echo '<script src="js/uploadphoto.js"></script>';

                        }
                        else{
                            echo '<li v-for="(info, objective) in objectives" v-if="(info[\'type\']===\'gps\')"><a v-bind:href="\'new_submit_photo.php?objective=\'+objective">{{ objective }}</a></li>';
                            echo '<script src="js/listphotos.js"></script>';
                        }
                        */
                        ?>
                    </div>
                    <script src="js/location-vue.js"/>
                    <script src="https://cdn.jsdelivr.net/npm/vue"/>
                </div>
            </div>
        </section>
    </main>
  </body>
</html>