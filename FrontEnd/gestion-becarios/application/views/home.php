<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
require_once('config.php');
require_once('core/controller.Class.php');
?>

<!DOCTYPE html>
<html lang="en">

    <head>

        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Log in</title>

        <!-- Favicon -->
        <link rel="shortcut icon" href="<?=base_url()?>public/images/icon.ico">
        <!-- favicon ends --->
        
        <!--- LOAD FILES -->
        <?php if($_SERVER['HTTP_HOST'] == "localhost" || (stristr($_SERVER['HTTP_HOST'], "192.168.") !== FALSE)|| (stristr($_SERVER['HTTP_HOST'], "127.0.0.") !== FALSE)): ?>
        <link rel="stylesheet" href="<?=base_url()?>public/bootstrap/css/bootstrap.min.css">
        <link rel="stylesheet" href="<?=base_url()?>public/font-awesome/css/font-awesome.min.css">

        <script src="<?=base_url()?>public/js/jquery.min.js"></script>
        <script src="<?=base_url()?>public/bootstrap/js/bootstrap.min.js"></script>

        <?php else: ?>
        
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css">

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

        <?php endif; ?> 
        
        <!-- CSS -->
        <link rel="stylesheet" href="<?=base_url()?>public/css/form-elements.css">
        <link rel="stylesheet" href="<?=base_url()?>public/css/style.css">
        <link rel="stylesheet" href="<?=base_url()?>public/css/main.css">

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
            <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->

    </head>

    <body>

        <!-- Top content -->
        <div class="container" style="margin-top: 100px;">
            <?php if(isset($_COOKIE["email"]) && isset($_COOKIE["id"]) && isset($_COOKIE["sess"])){
                $Controller = new Controller;?>
                <?php if($Controller -> checkUserExist($_COOKIE["email"], $_COOKIE["sess"])){?>
                    <div class="top-content">
                        <div class="inner-bg">
                            <div class="container">
                                <div class="row">
                                    <div class="col-sm-8 col-sm-offset-2 text">
                                        <div style="font-size:100px">
                                            <h1><img src="<?=base_url()?>public/images/upb_logo.jpg" alt="1410-logo" height="250px"></h1>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6 col-sm-offset-3">
                                        <div class="bg-primary text-center">
                                            <span id="errMsg"></span>
                                        </div>
                                        <div class="form-bottom">
                                            <form id="loginForm">
                                                <div class="form-group">
                                                    <label class="sr-only" for="email">E-mail</label>
                                                    <input type="email" class="form-control checkField" id="email" value="<?php echo $_COOKIE["email"]; ?>">
                                                </div>
                                                <button style="background-color:MediumSeaGreen;" type="submit" class="btn">Iniciar Sesión</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-sm-12 text-center" style="color:white">
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <?php echo '<a href="logout.php">Log Out</a>';
                }?>
            <?php }else{ ?>
                <img src="public/images/upb_logo.jpg" alt="logo" style="max-width: 550px; margin: 0 auto; display: table;" />
                <form action='' method="POST">
                    <button onclick="window.location = '<?php echo $login_url; ?>'" type="button" class="btn btn-danger">Login with Google</button>
                </form>

            <?php } ?>
        </div>
        <!-- Javascript -->
        <script src="<?=base_url()?>public/js/main.js"></script>
        <script src="<?=base_url()?>public/js/access.js"></script>
        <script src="<?=base_url()?>public/js/jquery.backstretch.min.js"></script>
        <!--Javascript--->

    </body>

</html>
