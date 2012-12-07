<?php
require("db.php");
require("utils.php");

$invalid_login = false;
if (!empty($_POST)) {
    if (check_credientials($_POST["email"], $_POST["password"])) {
        $location = create_url("index.html");
    	header("Location: $location");
    }
    else {
    	$invalid_login = true;
    }
}
?>

<!DOCTYPE html>
<html class="no-js">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title></title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width">

        <link rel="stylesheet" href="css/bootstrap.min.css">
        <style>
            body {
                padding-top: 60px;
                padding-bottom: 40px;
            }
        </style>
        <link rel="stylesheet" href="css/bootstrap-responsive.min.css">
        <link rel="stylesheet" href="css/main.css">

        <!--[if lt IE 9]>
            <script src="//html5shiv.googlecode.com/svn/trunk/html5.js"></script>
            <script>window.html5 || document.write('<script src="js/vendor/html5shiv.js"><\/script>')</script>
        <![endif]-->
    </head>
    <body>
        <div class="container">

            <!-- Main hero unit for a primary marketing message or call to action -->
            <div class="hero-unit">
                <h1>Bienvenue à Doodiro!</h1>
                <p>Doodiro est un logiciel permettant à plusieurs participants d'un événement de spécifier les périodes qui leur convient le mieux, et ainsi de permettre à l'organisateur de satisfaire le plus de gens possible.</p>
                <?php if ($invalid_login): ?>
					<div class="alert alert-error">
                    Courriel ou mot de passe incorrect.
                    </div>
				<?php endif ?>
                <form method="post" action="login.php">
                  <table class="table">
                    <tr>
                      <th>Courriel</th>
                      <td><input type="text" name="email" /></td>
                    </tr>
                    <tr>
                      <th>Mot de passe</th>
                      <td><input type="password" name="password" /></td>
                    </tr>
                  </table>

                  <input class="btn" type="submit" value="Connexion" />
                </form>
            </div>


            <footer>
                <p>Vincent Foley &amp; Truong Pham</p>
            </footer>

        </div> <!-- /container -->

        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
        <script>window.jQuery || document.write('<script src="js/vendor/jquery-1.8.3.min.js"><\/script>')</script>

        <script src="js/vendor/bootstrap.min.js"></script>

        <script src="js/main.js"></script>
    </body>
</html>
