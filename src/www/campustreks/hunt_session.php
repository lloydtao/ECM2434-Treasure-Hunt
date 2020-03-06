<html>
<head>
    <meta name="author" content = "Marek Tancak">
    <title>Host - CampusTreks</title>
    <link rel="stylesheet" href="css/hunt_session_stylesheet.css">
    <?php include('templates/head.php'); ?>
</head>
<body>
    <?php 
    if(isset($_GET['sessionID'])){
        $huntSessionID = $_GET['sessionID'];
        $json_data = file_get_contents('hunt_sessions/' . $huntSessionID . '.json');
        $huntSessionData = json_decode($json_data, true);
        print_r($huntSessionData);
    }
    else{
        header('Location: /host.php');
        die();
    }
    ?>
    <!-- Header -->
    <?php include('templates/header.php'); ?>
    <!-- Content -->
    <div id="host">
        <main class="page host-page">
            <section class="portfolio-block project-no-images">
                <div class="container">
                    <div class="heading">
                        <h2>Game Pin</h2>
                        <h3><div id="pin"><?php echo $huntSessionData['gameinfo']['gamePin']; ?></div></h3>
                    </div>
                    
                    <submissions-leaderboard></submissions-leaderboard>
                
                    
                    <button class="btn btn-primary" type="button" @onclick ="this.fetchJson()">Refresh</button>
                    <button class="btn btn-primary" type="button">End Game</button>
                </div>
            </section>
        </main>
    </div>

    <!-- Footer -->
    <?php include('templates/footer.php'); ?>
</body>
</html>


<script src="https://unpkg.com/vue"></script>
<script src="js/host.js"></script>
