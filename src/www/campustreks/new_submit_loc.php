<!DOCTYPE html>
<html>
<head>
    <meta name="author" content = "Marek Tancak">
    <title>Submit Photo - CampusTreks</title>
</head>
<style>
    .wrapper { 
  height: 100%;
  width: 100%;
  left:0;
  right: 0;
  top: 0;
  bottom: 0;
  position: absolute;
background: linear-gradient(124deg, #ff2400, #e81d1d, #e8b71d, #e3e81d, #1de840, #1ddde8, #2b1de8, #dd00f3, #dd00f3);
background-size: 1800% 1800%;

-webkit-animation: rainbow 18s ease infinite;
-z-animation: rainbow 18s ease infinite;
-o-animation: rainbow 18s ease infinite;
  animation: rainbow 18s ease infinite;}

  .heading { 
  height: 10%;
  width: 50%;
  left:20%;
  right: 0;
  top: 40%;
  bottom: 0;
  position: absolute;
background: linear-gradient(124deg, #ff2400, #e81d1d, #e8b71d, #e3e81d, #1de840, #1ddde8, #2b1de8, #dd00f3, #dd00f3);
background-size: 1800% 1800%;

-webkit-animation: rainbow 9s ease infinite;
-z-animation: rainbow 9s ease infinite;
-o-animation: rainbow 9s ease infinite;
  animation: rainbow 9s ease infinite;}

    #rest { 
  height: 10%;
  width: 20%;
  left:10%;
  right: 0;
  top: 70%;
  bottom: 0;
  position: absolute;
background: linear-gradient(124deg, #ff2400, #e81d1d, #e8b71d, #e3e81d, #1de840, #1ddde8, #2b1de8, #dd00f3, #dd00f3);
background-size: 1800% 1800%;

-webkit-animation: rainbow 3s ease infinite;
-z-animation: rainbow 3s ease infinite;
-o-animation: rainbow 3s ease infinite;
  animation: rainbow 3s ease infinite;}

      #alert { 
  height: 20%;
  width: 20%;
  left:70%;
  right: 0;
  top: 10%;
  bottom: 0;
  position: absolute;
background: linear-gradient(124deg, #ff2400, #e81d1d, #e8b71d, #e3e81d, #1de840, #1ddde8, #2b1de8, #dd00f3, #dd00f3);
background-size: 1800% 1800%;

-webkit-animation: rainbow 0.1s ease infinite;
-z-animation: rainbow 0.5s ease infinite;
-o-animation: rainbow 0.5s ease infinite;
  animation: rainbow 0.5s ease infinite;}

  .test { 
  height: 10%;
  width: 33%;
  left:65%;
  right: 0;
  top: 85%;
  bottom: 0;
  position: absolute;
background: linear-gradient(124deg, #ff2400, #e81d1d, #e8b71d, #e3e81d, #1de840, #1ddde8, #2b1de8, #dd00f3, #dd00f3);
background-size: 1800% 1800%;

-webkit-animation: rainbow 1s ease infinite;
-z-animation: rainbow 1s ease infinite;
-o-animation: rainbow 1s ease infinite;
  animation: rainbow 1s ease infinite;}

@-webkit-keyframes rainbow {
    0%{background-position:0% 82%}
    50%{background-position:100% 19%}
    100%{background-position:0% 82%}
}
@-moz-keyframes rainbow {
    0%{background-position:0% 82%}
    50%{background-position:100% 19%}
    100%{background-position:0% 82%}
}
@-o-keyframes rainbow {
    0%{background-position:0% 82%}
    50%{background-position:100% 19%}
    100%{background-position:0% 82%}
}
@keyframes rainbow { 
    0%{background-position:0% 82%}
    50%{background-position:100% 19%}
    100%{background-position:0% 82%}
}
    </style>
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
                        <div id="rest">
                        {{ direction }}
                        <button type="button" v-show="!show && !complete" v-on:click="submit">Submit Location</button><br>
                        <div v-show="show && !complete">
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