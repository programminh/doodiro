<?php
require('bootstrap.php');

// Redirect to login if not logged in
if (! $_SESSION['is_logged_in']) {
    header('Location: login.php');
}

if(! isset($_GET['p'])) {
    $content = 'admin/events.php';
    $menu = 1;
}
else {
    $content = 'admin/'.$_GET['p'].'.php';
    $menu = 2;
}

// Fetching user's info from the session
$user = $_SESSION['user'];
?>
<!DOCTYPE html>
<html class="no-js">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>IFT3225 - Doodiro - Admin</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width">

        <link rel="stylesheet" href="css/bootstrap.min.css">
        <style>
            body {
                padding-top: 60px;
                padding-bottom: 40px;
            }
        </style>
        <link href='http://fonts.googleapis.com/css?family=Reenie+Beanie' rel='stylesheet' type='text/css'>
        <link rel="stylesheet" href="http://code.jquery.com/ui/1.9.2/themes/base/jquery-ui.css" />
        <link rel="stylesheet" href="css/main.css">

        <!--[if lt IE 9]>
            <script src="//html5shiv.googlecode.com/svn/trunk/html5.js"></script>
            <script>window.html5 || document.write('<script src="js/vendor/html5shiv.js"><\/script>')</script>
        <![endif]-->
    </head>
    <body>
        <div class="container" id="top-menu">
            <div class="row">
                <div class="span6">
                    <div class="btn-group">
                        <a class="btn" href="index.php">
                            Doodiro
                        </a>
                        <a class="btn btn-success" href="index.php?p=new_event">
                            Nouveau
                        </a>
                        <?php if ($user->is_admin): ?>
                            <a href="admin.php" class="btn btn-info">Admin</a>
                        <?php endif ?>
                    </div>
                </div>
                <div class="span6">
                    <h5 class="pull-right">
                        <?php echo $user->firstname ?> <?php echo $user->lastname ?>
                        (<a href="logout.php">Déconnexion</a>)
                    </h5>
                </div>
            </div>
            <?php if (! $user->is_admin): ?>
	    		<div style="margin-top: 40px" class="span6 offset3">
	    			<div class="alert alert-danger" style="text-align: center">
	    				<h2>Autorisation refusé</h2>
	    				<h5>
	    					Votre autorisation est aussi refusée qu'une offre du banquier 
	    					présentée par Julie Snyder lorsqu'il reste seulement des montants
	    					dans la colonne de droite
	    				</h5>
	    			</div>
	    		</div>
    			<?php die() ?>
    		<?php endif ?>
        </div>

        <div class="container">
        	<div class="row">
        		<div class="span12">
        			<ul class="nav nav-pills pull-right">
		        		<li <?php echo ($menu == 1) ? 'class="active"' : '' ?>><a href="admin.php">Événements</a></li>
		        		<li <?php echo ($menu == 2) ? 'class="active"' : '' ?>><a href="admin.php?p=users">Utilisateurs</a></li>
		        	</ul>
        		</div>
        		<div class="span12">
        			<hr>
        		</div>
        	</div>
        </div>

        
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
        <script>window.jQuery || document.write('<script src="js/vendor/jquery-1.8.3.min.js"><\/script>')</script>

        <script src="js/vendor/bootstrap.min.js"></script>

        <script src="js/main.js"></script>

        <script src="js/vendor/jquery-ui-1.9.2.custom.js"></script>

        <?php include($content); ?>

    </body>
</html>