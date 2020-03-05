<!DOCTYPE html>
<html>
  <head>
    <?php
        function compressImage($source, $destination, $quality) {

          $info = getimagesize($source);

          if ($info['mime'] == 'image/jpeg') 
            $image = imagecreatefromjpeg($source);

          elseif ($info['mime'] == 'image/gif') 
            $image = imagecreatefromgif($source);

          elseif ($info['mime'] == 'image/png') 
            $image = imagecreatefrompng($source);

          imagejpeg($image, $destination, $quality);
        }

        if(isset($_FILES['image'])){
            $errors= array();
            $name = $_FILES['image']['name'];
            $tmp =$_FILES['image']['tmp_name'];
            $nameExp = explode('.', $name);
            $ext = end($nameExp);
            $allowedExt = array('jpg','jpeg','png');

            if(in_array($ext, $allowedExt)){
                $path = "image_uploads/".$_GET['team_id'].'-'.$_GET['objective_id'].'.jpg';
                compressImage($tmp,$path, 50);
                //move_uploaded_file($tmp,$path);

                $huntSessionID = $_GET['game_pin'];
                $json_data = file_get_contents('hunt_sessions/' . $huntSessionID . '.json');
                $hunt_session_data = json_decode($json_data, true);
                $objective_id = $_GET['objective_id'];
                $hunt_session_data['teams'][$_GET['team_id']]['objectives'][$objective_id] = array('type' => 'photo', "completed" => true, "objectiveId" => $_GET['objective_id'], "path" => $path, "score" => 0);
                $json_data = json_encode($hunt_session_data);
                file_put_contents('hunt_sessions/' . $_GET['game_pin'] . '.json', $json_data);
            }
            else{
                echo "Unsupported file format";
            }
        }
    ?>
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
                    <h2>Submit photo</h2>
                </div>
                <div class="content">
                    <form method="post" enctype="multipart/form-data">
                        Select image to upload:<br>
                        <input type="file" name="image" /><br>
                        <input type="submit" value="submit" name="submit">
                    </form>
                </div>
            </div>
        </section>
    </main>
    <!-- Footer -->
    <?php include('templates/footer.php'); ?>
  </body>
</html>