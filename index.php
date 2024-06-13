<?php 	



    ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);



    session_start();


    
    if(isset($_GET['menu'])) { $menu   = (int)$_GET['menu']; }
    if(isset($_GET['action'])) { $action   = (int)$_GET['action']; }

    if(!isset($_POST['_action_']))  { $_POST['_action_'] = FALSE;  }

	include ("connectdb.php");
    include ("extra.php");
	
	if (!isset($menu)) { $menu = 1; }
	
print '
<!DOCTYPE html>
<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Uprava Vodovoda</title>
    <link rel="stylesheet" href="./style.css">
</head>
<body>
	<header>
		<div'; if ($menu > 1) { print ' class="banner-image"'; } else { print ' class="banner-image-main"'; }  print '></div>
		<div class=navbar>';
			include("menu.php");
		print '</div>
	</header>
	<main>';
    if (isset($_SESSION['message'])) {
        print $_SESSION['message'];
        unset($_SESSION['message']);
    }
	
	if (!isset($menu) || $menu == 1) { include("home.php"); 
    }
	
	else if ($menu == 2) { include("complaints.php"); }
	
	else if ($menu == 3) { include("contact.php"); 
        }
	
	else if ($menu == 4) { include("about.php"); 
        }
	
	else if ($menu == 5) { include("register.php"); 
        }
	
	else if ($menu == 6) { include("signin.php"); 
        }
	
	else if ($menu == 7) { include("admin.php"); 
        }
	
	print '
	</main>
	<footer class=footer>
		&copy;'. date("Y").'  Uprava Vodovoda. Sva prava pridržana.
	</footer>
</body>
</html>';
?>
