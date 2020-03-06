<html>
  <head>
    <title>Home - CampusTreks</title>
	<?php include('templates/head.php'); ?>
  </head>
  <body>
	<!-- Header -->
	<?php include('templates/header.php'); ?>
	<!-- Content -->
    <main class="page home-page">
        <section class="portfolio-block block-intro">
            <div class="container"><img class="img-fluid" src="img/Logo_Campus_Treks_360.jpg">
				<br><br>
                <div class="about-me">
                    <p>
						Welcome to Campus Treks! We aim to help new people find their way around.
						<br>
						Get started by creating a new hunt, hosting a hunt, or by reading more below.
					</p>
					<a class="btn btn-outline-primary" role="button" href="create.php">Create a Hunt</a>
                    <a class="btn btn-outline-primary" role="button" href="host.php">Host a Hunt</a>
                </div>
            </div>
        </section>
        <section class="portfolio-block photography">
            <div class="container">
                <div class="row no-gutters">
                    <div class="col-md-6 col-lg-4 item zoom-on-hover">
						<img class="img-fluid" src="img/Forum_Back.jpg">
					</div>
                    <div class="col-md-6 col-lg-4 item zoom-on-hover">
						<img class="img-fluid" src="img/Students_on_Campus.jpg">
					</div>
                    <div class="col-md-6 col-lg-4 item zoom-on-hover">
						<img class="img-fluid" src="img/Forum_Overhead.jpg">
					</div>
                </div>
            </div>
        </section>
        <section class="portfolio-block skills">
            <div class="container">
                <div class="heading"><h2>How It Works</h2></div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="card special-skill-item border-0">
                            <div class="card-header bg-transparent"><i class="icon ion-plus"></i></div>
                            <div class="card-body">
                                <h3 class="card-title">Create a Hunt</h3>
                                <p class="card-text">
									Create a series of objectives that players will aim to complete. 
									These objectives will mainly be to navigate to GPS co-ordinates, 
									with some bonus objectives for added fun!
								</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card special-skill-item border-0">
                            <div class="card-header bg-transparent"><i class="icon ion-ios-analytics"></i></div>
                            <div class="card-body">
                                <h3 class="card-title">Host a Hunt</h3>
                                <p class="card-text">
									Once you have made a hunt, or selected a pre-made hunt from the community, 
									you can host a session for players to join via a game pin and compete from 
									their smartphones.
								</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card special-skill-item border-0">
                            <div class="card-header bg-transparent"><i class="icon ion-trophy"></i></div>
                            <div class="card-body">
                                <h3 class="card-title">Play the Hunt</h3>
                                <p class="card-text">
									After all players have joined the hunt, the hunt can begin! 
									Monitor the progress of players and communicate with them as 
									they traverse your series of objectives.
								</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
    <section class="portfolio-block website gradient">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-12 col-lg-5 offset-lg-1 text">
                    <h3>About CampusTreks</h3>
                    <p>
						"As a first year student at university, I found myself desperately trying to 
						locate buildings for my lectures in my first term. This project aims to compress the 
						stressful few weeks of learning your whereabouts into a fun-filled hour."
						<br>
						&nbsp;-&nbsp;<em>Lewis, CampusTreks Team</em>
					</p>
                </div>
                <div class="col-md-12 col-lg-5">
                    <div class="portfolio-laptop-mockup">
                        <div class="screen">
                            <div class="screen-content" style="background-image:url();"></div>
                        </div>
                        <div class="keyboard"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>
	<!-- Footer -->
	<?php include('templates/footer.php'); ?>
  </body>
</html>
