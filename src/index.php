<?php
/**
 * @author Abdelkader Benkhadra
 * This is the main application
*/
 
// Include the necessary files
include_once 'config/init.php';

if($_SESSION['user_email'] == NULL)
{
	header("Location: /../app/login.php");
}

?>

<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Pragma" content="no-cache" />
	<meta http-equiv="Expires" content="-1" />
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link href='http://fonts.googleapis.com/css?family=Chela+One' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" type="text/css" href="css/style.css" media="all">
	<title>Charts for your data</title>
</head>
<body>
    <div id='maincontent'>
    	<form action="common/process.php" method="post">
    	<div id="logout">
    		<input type="submit" value="Se déconnecter"/>
    	 	<input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>"/>
    	 	<input type="hidden" name="action" value="user_logout"/>
    	</div>
    	</form>	
     	<div id='users' class='active content'>Utilisateurs
     		<div class='table'>
     		<?php
     			$obj = new Users($dbo);
				$users = $obj->createTable();
			?>
			</div>
		</div>
     	<div id='visits' class='content'>Sites
			<div class='table'>
     		<?php
     			$obj = new Sites($dbo);
				$sites = $obj->createTable();
			?>
			</div>
     	</div>
     	<div id='clients'class='content'>Business
			<div class='table'>
     		<?php
     			$obj = new Business($dbo);
				$business = $obj->createTable();
			?>
			</div>
     	</div>
		<div id="nav">
			<div id="nav-container">
 				<div id="u-nav"></div>
 				<div id="v-nav"></div>
				<div id="c-nav"></div>
  			</div>
 		</div>
 		<div id='tooltip'></div>
	</div>
	<script type="text/javascript" src="js/jquery-1.0.1.min.js"></script>
 	<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.9/jquery-ui.min.js" type="text/javascript"></script>
	<script type="text/javascript" src="js/app.js"></script>

</body>
</html>