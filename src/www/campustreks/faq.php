<!DOCTYPE html>
<html lang="en" dir="ltr">
    <head>
        <meta charset="utf-8">
        <meta name="author" content="Joseph Lintern">
        <title>FAQs</title>
        <?php include('templates/head.php'); ?>
    </head>
    <body>
        <!-- Header -->
    	<?php include('templates/header.php'); ?>

        <!-- Content -->
        <main class="page home-page">
            <section class="portfolio-block block-intro">
                <div class="container"><img class="img-fluid" src="img/logo/Logo-375x-Res.jpg">
    				<br><br>
                    <div class="about-me">
                        <h2>Frequently Asked Questions</h2>
                        <div class="row justify-content-center">
                            <div class="col-md-6 align">
                                <div id="accordion">
                                  <div class="card">
                                    <div class="card-header" id="headingOne">
                                      <h5 class="mb-0">
                                        <button class="btn btn-link text-dark" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                          How do I join a game?
                                        </button>
                                      </h5>
                                    </div>

                                    <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
                                      <div class="card-body">
                                        Go to the <a href="play.php">play page</a> and enter the game pin and a unique nickname. After that, you can either join
                                        a team or create a new team. Once you are ready, join the game and try and get as many points as you can by completing
                                        objectives!
                                      </div>
                                    </div>
                                  </div>
                                  <div class="card">
                                    <div class="card-header" id="headingTwo">
                                      <h5 class="mb-0">
                                        <button class="btn btn-link text-dark collapsed" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                          How do I create a hunt?
                                        </button>
                                      </h5>
                                    </div>
                                    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
                                      <div class="card-body">
                                          Login on a desktop computer and navigate to the <a href="account.php">create link</a> in the navigation bar.
                                          Then you create a hunt with your own a title, a description and you can add many of 2 types of objectives:
                                          GPS objective or photo objective. The GPS objective will require coordniates (or you can click a location on the map)
                                          and a question and answer, which are automatically checked. The photo objectives only require a description and are manually checked.
                                      </div>
                                    </div>
                                  </div>
                                  <div class="card">
                                    <div class="card-header" id="headingThree">
                                      <h5 class="mb-0">
                                        <button class="btn btn-link text-dark collapsed" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                           How do I host a game?
                                        </button>
                                      </h5>
                                    </div>
                                    <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordion">
                                      <div class="card-body">
                                          Login on a desktop computer and navigate to the <a href="host.php">host link</a> in the navigation bar. Then find
                                               the hunt you want to host and click the button
                                      </div>
                                    </div>
                                  </div>
                                  <div class="card">
                                    <div class="card-header" id="headingFour">
                                      <h5 class="mb-0">
                                        <button class="btn btn-link text-dark collapsed" data-toggle="collapse" data-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                                           How do I change my password?
                                        </button>
                                      </h5>
                                    </div>
                                    <div id="collapseFour" class="collapse" aria-labelledby="headingFour" data-parent="#accordion">
                                      <div class="card-body">
                                          Click on your <a href="account.php">username</a>
                                          in the navigation bar and fill out the form to change it.
                                      </div>
                                    </div>
                                  </div>
                                  <div class="card">
                                    <div class="card-header" id="headingFive">
                                      <h5 class="mb-0">
                                        <button class="btn btn-link text-dark collapsed" data-toggle="collapse" data-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                                           What is a verified user?
                                        </button>
                                      </h5>
                                    </div>
                                    <div id="collapseFive" class="collapse" aria-labelledby="headingFive" data-parent="#accordion">
                                      <div class="card-body">
                                         A user that has been recognised by our team as a member of University of Exeter.
                                      </div>
                                    </div>
                                  </div>
                                  <div class="card">
                                    <div class="card-header" id="headingSix">
                                      <h5 class="mb-0">
                                        <button class="btn btn-link text-dark collapsed" data-toggle="collapse" data-target="#collapseSix" aria-expanded="false" aria-controls="collapseSix">
                                           How do I get verified?
                                        </button>
                                      </h5>
                                    </div>
                                    <div id="collapseSix" class="collapse" aria-labelledby="headingSix" data-parent="#accordion">
                                      <div class="card-body">
                                         Contact our team by emailing campustreks@outlook.com, with the subject line 'Make_Me_Verified'.
                                      </div>
                                    </div>
                                  </div>
                                  <div class="card">
                                    <div class="card-header" id="headingSeven">
                                      <h5 class="mb-0">
                                        <button class="btn btn-link text-dark collapsed" data-toggle="collapse" data-target="#collapseSeven" aria-expanded="false" aria-controls="collapseSeven">
                                           How can I ask my own question?
                                        </button>
                                      </h5>
                                    </div>
                                    <div id="collapseSeven" class="collapse" aria-labelledby="headingSeven" data-parent="#accordion">
                                      <div class="card-body">
                                          You can follow our <a href="https://twitter.com/Campus_treks" target="_blank">Twitter</a> and
                                          <a href="https://www.instagram.com/campus_treks/?hl=en" target="_blank">Instagram</a> pages, and either direct message us or tag us.
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>

                    </div>
                </div>
            </section>


        <?php include('templates/footer.php'); ?>
    </body>
</html>
