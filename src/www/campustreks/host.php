<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta name="author" content = "Lewis Lloyd">
    <meta name="Contributors" content = "Marek Tancak, Jakub Kwak">
    <title>Host - CampusTreks</title>
	<?php include('templates/head.php'); ?>
  </head>
  <?php
  include "checkhunts.php";
  session_start();
  if (isset($_SESSION["username"])) {
      $pin = checkHunts($_SESSION["username"]);
      if ($pin != null) {
          header("Location: /hunt_session.php?sessionID=".$pin);
      }
  }
  ?>
  <body>
    <script>
        function startHunt(huntID){
            location.href = '/start_hunt.php?huntID='+huntID;
        }
    </script>
	<!-- Header -->
	<?php include('templates/header.php'); ?>
	<!-- Content -->
    <main class="page host-page">
        <section class="portfolio-block project-no-images">
            <div class="container">
                <div class="heading">
                    <h2>Host a Hunt</h2>
                </div>
                <div class="row">
                    <?php
                    $ip = "localhost";
                    $username = "root";
                    $password = "";
                    $dbname = "campustreks";

                    $conn = new mysqli($ip, $username, $password, $dbname);
                    if ($conn->connect_error) {
                        die("Database connection failed - " . $conn->connect_error . "<br>");
                    }

                    $query = "SELECT * FROM Hunt";
                    $result = $conn->query($query);

                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            //check if user is verified
                            $verified = 0;
                            $huntUser = $row["Username"];
                            $sql = $conn->prepare("SELECT `Verified` FROM `users` WHERE `Username` = ?");
                            $sql->bind_param("s", $huntUser);
                            $sql->execute();
                            $resultUser = $sql->get_result();
                            if ($resultUser->num_rows > 0) {
                                $rowUser = $resultUser->fetch_assoc();
                                $verified = $rowUser["Verified"];
                            }
                            echo '<div class="col-md-6 col-lg-4">';
                            echo '<div class="project-card-no-image">';
                            echo '<h3>' . $row["Name"] . '</h3>';
                            echo '<h4>Author: ' . $row["Username"];
                            if ($verified == 1) {
                                echo ' <img src="img/exeter-logo.png" height="14px" width="14px"></h4>';
                            } else {
                                echo '</h4>';
                            }
                            echo '<h4>' . $row["Description"] . '</h4>';
                            echo '<a class="btn btn-outline-primary btn-sm" role="button" href="#" onclick=startHunt(' . $row["HuntID"] . ')>Host</a>';
                            echo '<div class="tags">High Score: ' . $row["Highscore"] . ' - ' . $row["BestTeam"] . '</div>';
                            echo '</div>';
                            echo '</div>';
                        }
                    } else {
                        echo 'No hunts found. Click <a href="/create.php">here</a> to create a new hunt.<br>';
                    }
                    ?>
                </div>
            </div>
        </section>
    </main>
	<!-- Footer -->
	<?php include('templates/footer.php'); ?>
  </body>
</html>
