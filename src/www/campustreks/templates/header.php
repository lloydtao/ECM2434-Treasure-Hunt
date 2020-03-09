<nav class="navbar navbar-dark navbar-expand-lg fixed-top bg-white portfolio-navbar gradient">
    <div class="container"><a class="navbar-brand logo" href="/">Campus Treks</a><button data-toggle="collapse" class="navbar-toggler" data-target="#navbarNav"><span class="sr-only">Toggle navigation</span><span class="navbar-toggler-icon"></span></button>
        <div class="collapse navbar-collapse"
            id="navbarNav">
            <ul class="nav navbar-nav ml-auto">
                <li class="nav-item" role="presentation"><a class="nav-link active" href="index.php">Home</a></li>
                <li class="nav-item" role="presentation"><a class="nav-link" href="create.php">Create</a></li>
                <li class="nav-item" role="presentation"><a class="nav-link" href="host.php">Host</a></li>
                <?php
                if (session_status() == PHP_SESSION_NONE) session_start();
                if (!isset($_SESSION['isLoggedIn']) || $_SESSION['isLoggedIn'] == false) :?>
                    <li class="nav-item" role="presentation"><a class="nav-link" href="login.php">Log In/Register</a></li>
                <?php else: ?>
                    <li class="nav-item" role="presentation"><a class="nav-link" href="logout.php">Log Out</a></li>
                    <li class="nav-item" role="presentation"><a class="nav-link" href="account.php"><?php echo $_SESSION["username"]?></a></li>
                <?php endif ?>
            </ul>
        </div>
    </div>
</nav>
