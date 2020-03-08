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
                        {{ currentObjectiveKey }}
                        <button type="button" v-on:click="submit">Submit Location</button><br>
                        <div v-show="show">
                            <br>
                            {{ q }}<br>
                            <input id='answer'> <br>
                            <button v-on:click=checkQuestion>Submit Answer</button>
                        </div>
                    </div>
                    <script src="https://cdn.jsdelivr.net/npm/vue"></script>
                    <script src="js/location-vue.js"></script>
                </div>
            </div>
        </section>
    </main>
</body>
</html>