<!DOCTYPE html>
<html>
<head>
    <meta name="author" content = "Marek Tancak">
    <title>Submit Photo - CampusTreks</title>
</head>

<body><div class="wrapper">
    <!-- Header -->
    <div class = "test">
    <?php include('templates/header_mobile.php'); ?></div>
    <!-- Content -->
    <main class="page host-page">
        <section class="portfolio-block project-no-images">
            <div class="container">
                <div class="heading">
                    <h2>Submit location</h2>
                </div>
                <div class="content">
                    <div id="objectives">
                        <div id="rest" v-show="!complete">
                        <div v-show="!show">
                        {{ direction }}
                        <button type="button" v-on:click="submit">Submit Location</button><br>
                    </div>
                        <div v-show="show">
                            <br>
                            {{ q }}<br>
                            <input id='answer'> <br>
                            <button v-on:click=checkQuestion>Submit Answer</button>
                        </div>
                    </div>
                    <div id="alert" v-show="!(alert=='')">{{ alert }}</div>
                    </div>
                    <script src="https://cdn.jsdelivr.net/npm/vue"></script>
                    <script src="js/location-vue.js"></script>
                </div>
            </div>
        </section>
    </main>
</div>
</body>
</html>