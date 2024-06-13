<?php 
	print '
	<h1>Sign In form</h1>
	<div id="signin">';
	
	if ($_POST['_action_'] == FALSE ) {
		print '
		<div class="edit_complaint">
        <h2>Prijava</h2>
        <form action="" method="POST">
            
                <label for="username">Username:</label>
                
                    <input type="text" name="username" id="username" class="form-field-textual" required>
					<input type="hidden" id="_action_" name="_action_" value="TRUE">
                

            
                <label for="password">Lozinka:</label>
                
                    <input type="password" name="password" id="password" class="form-field-textual" required>
                
            

                <input type="submit" value = "Prijava" >
                <input type="reset" value = "Poništi">
        </form>
    </div>';
	}
	else if ($_POST['_action_'] == TRUE) {

		
		
		$query  = "SELECT * FROM users";
		$query .= " WHERE username=?";
		$stmt = mysqli_prepare($MySQL, $query);
		mysqli_stmt_bind_param($stmt, "s", $_POST['username']);
		mysqli_stmt_execute($stmt);
		$result = mysqli_stmt_get_result($stmt);
		$row = mysqli_fetch_array($result, MYSQLI_ASSOC);


		
		if (password_verify($_POST['password'], $row['password'])) {
			$_SESSION['user']['valid'] = 'true';
			$_SESSION['user']['id'] = $row['id'];
			$_SESSION['user']['firstname'] = $row['firstname'];
			$_SESSION['user']['lastname'] = $row['lastname'];

			$_SESSION['message'] = '<p class = "message">Dobrodošli, ' . $_SESSION['user']['firstname'] . ' ' . $_SESSION['user']['lastname'] . '</p>';
			header("Location: index.php?menu=7");

		}
		
        else {
			unset($_SESSION['user']);

			$_SESSION['message'] = '<p class = "message">You entered wrong email or password!</p>';
			header("Location: index.php?menu=6");
		}
	}
	print '
	</div>';
?>