<?php
/**
 * @author Abdelkader Benkhadra
 * This is the login form file
*/

// Include the necessary files
include_once 'config/init.php';

// Redirect to the application page if the session is started
if(isset($_SESSION['user_email']))
{
	header("Location: ../index.php");
	exit();
}

// Make sure that the passed variable to url is an actual error message
$error = 'Votre combinaison email/mot de passe est invalide'

?>

<!DOCTYPE html>
<html>
	<head>
		<link href='http://fonts.googleapis.com/css?family=Chela+One' rel='stylesheet' type='text/css'>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link rel="stylesheet" type="text/css" href="css/login.css" media="all">
		<title>Authentification</title>
	</head>
	<body>
		<div id="main_container">
			<div class="content">
				<div class="authentificate">
					<div class="error"><?php if(htmlentities($_GET['Error'], ENT_QUOTES) == $error) echo $error ?></div>
					<form action="common/process.php" method="post">
					<fieldset>
     					<dl>
                        	<input type="text" name="email" id="email" size="25" placeholder="xyz@example.com"/>
                    	</dl>
                    	<dl>
                        	<input type="text" name="password" id="password" size="25" placeholder="Votre mot de passe"/>
                    	</dl>
                    		<input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>"/>
                    		<input type="hidden" name="action" value="user_login"/>
                    	<dl class="submit">
                    		<input type="submit" name="submit" id="submit" value="Se connecter">
                    	</dl>
					</fieldset>
					</form>
				</div>
			</div>
		</div>
		<script type="text/javascript" src="js/jquery-1.0.1.min.js"></script>
		<script type="text/javascript" src="js/login.js"></script>
	</body>
</html>