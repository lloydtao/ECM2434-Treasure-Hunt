<!DOCTYPE html>
<html>
<head>
    <meta name="author" content="Marek Tancak">
    <meta name="contributors" content="Jakub Kwak">
    <title>Submit Photo - CampusTreks</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
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
                <script src="https://cdn.jsdelivr.net/npm/vue"></script>
                <div id="objectives">
                    <div v-if="showUpload">
                        <img width="500px" v-if="objectives[currentObjective]['completed']"
                             v-bind:src="imgPath">
                        <form id="uploadForm" v-on:submit.prevent enctype="multipart/form-data">
                            <p>Select image to upload:</p><br>
                            <input type="file" name="image" /><br>
                            <button v-on:click="submitForm()">Upload</button>
                            <button v-on:click="hideUploadForm()">Back</button>
                        </form>
                    </div>
                    <div v-else>
                        <li v-for="(objective, index) in objectives">
                            <button v-on:click="showUploadForm(index)">{{ objective["description"] }}</button>
                        </li>
                    </div>
                    <script src="js/listphotos.js"></script>
                </div>
            </div>
        </div>
    </section>
</main>
</body>
</html>
