<?php
require_once('config.php');
require_once('core/controller.Class.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login to my app</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
</head>
<body>
<div class="container" style="margin-top: 100px;">
    <?php if(isset($_COOKIE["id"]) && isset($_COOKIE["sess"])){
        $Controller = new Controller;
        if($Controller -> checkUserStatus($_COOKIE["id"], $_COOKIE["sess"])){
            echo $Controller -> printData(intval($_COOKIE["id"]));
            echo '<a href="logout.php">Log Out</a>';
        }
    }else{ ?>
        <img src="public/images/upb_logo.jpg" alt="logo" style="max-width: 550px; margin: 0 auto; display: table;" />
        <form action='' method="POST">
            <button onclick="window.location = '<?php echo $login_url; ?>'" type="button" class="btn btn-danger">Login with Google</button>
        </form>
    <?php } ?>
</div>
</body>
</html>