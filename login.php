<?php
require('bootstrap.php');

$invalid_login = false;

if (!empty($_POST)) {
    if ($user = User::auth($_POST["email"], $_POST["password"])) {
        $_SESSION["user"] = $user;
        $_SESSION["is_logged_in"] = true;
    	header("Location: index.php");
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
        <title>IFT3225 - Doodiro - Authentification</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width">

        <link rel="stylesheet" href="css/bootstrap.min.css">
        <style>
            body {
                padding-top: 60px;
                padding-bottom: 40px;
            }
        </style>
        <link rel="stylesheet" href="css/main.css">

        <!--[if lt IE 9]>
            <script src="//html5shiv.googlecode.com/svn/trunk/html5.js"></script>
            <script>window.html5 || document.write('<script src="js/vendor/html5shiv.js"><\/script>')</script>
        <![endif]-->
    </head>
    <body>
        <div class="container">

            <div class="row">
                <div class="span6 offset3 well">
                    <h1>Bienvenue à Doodiro!</h1>
                    <p>Doodiro est un logiciel permettant à plusieurs participants d'un événement de spécifier les périodes qui leur convient le mieux, et ainsi de permettre à l'organisateur de satisfaire le plus de gens possible.</p>
                    <?php if ($invalid_login): ?>
                          <div class="alert alert-error">
                               Courriel ou mot de passe incorrect.
                          </div>
                    <?php endif ?>

                    <form method="post" action="login.php">
                      <fieldset>
                        <legend>Authentification</legend>
                        <label>Courriel</label>
                        <input type="text" name="email" value="<?php echo (array_key_exists('email', $_POST) ? $_POST['email'] : '') ?>" />
                        <label>Mot de passe</label>
                        <input type="password" name="password" />
                      </fieldset>

                      <input class="btn btn-primary" type="submit" value="Connexion" />
                    </form>
                </div>

                <div class="span6 offset3">
                    <footer>
                        <p>&copy; Vincent Foley &amp; Truong Pham</p>
                    </footer>
                </div>
            </div>
            
            

        </div> <!-- /container -->

        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
        <script>window.jQuery || document.write('<script src="js/vendor/jquery-1.8.3.min.js"><\/script>')</script>

        <script src="js/vendor/bootstrap.min.js"></script>

        <script src="js/main.js"></script>
    </body>
</html>
