<html>
  <head>
    <title>Create - CampusTreks</title>
	<?php include('templates/head.php'); ?>
  </head>
  <body>
	<!-- Header -->
	<?php include('templates/header.php'); ?>
	<!-- Content -->
    <main class="page hire-me-page">
        <section class="portfolio-block hire-me">
            <div class="container">
                <div class="heading">
                    <h2>Create A HunT</h2>
                </div>
                <form>
                    <div class="form-group">
						<label for="title">Title</label><br>
						<input class="form-control" type="text" name="title">
					</div>
                    <div class="form-group">
						<label for="location">Location</label><br>
						<input class="form-control" type="text" name="location">
					</div>
                    <div class="form-group">
						<label for="description">Description</label><br>
						<textarea class="form-control form-control-lg" name="Description"></textarea>
					</div>
                    <div class="form-group">
						<label for="objectives">Objectives</label><br>
						<button class="btn btn-primary" type="button">Add Objective</button>
					</div>
                    <div class="form-group">
                        <div class="form-row">
                            <div class="col-md-6">
								<label for="hire-date">Estimated Duration</label><br>
								<input class="form-control" type="number">
							</div>
								<div class="col-md-6 button">
								<button class="btn btn-primary btn-block" type="submit">Submit Hunt</button>
							</div>
                        </div>
                    </div>
                </form>
            </div>
        </section>
    </main>
	<!-- Footer -->
	<?php include('templates/footer.php'); ?>
  </body>
</html>
