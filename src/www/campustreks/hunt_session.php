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
    }
    else{
        header('Location: /host.php');
        die();
    }
    ?>
    <!-- Header -->
    <?php include('templates/header.php'); ?>
    <!-- Content -->
    <div id="host"></div>
    <!-- Footer -->
    <?php include('templates/footer.php'); ?>
</body>
</html>

<script src="https://unpkg.com/vue"></script>
<script src="js/host.js"></script>
