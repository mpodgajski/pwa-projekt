<?php 
	print '
	<h1>Registration Form</h1>
	<div id="register">';
	
	if ($_POST['_action_'] == FALSE) {
		print '
        <div class = "edit_complaint">
		<form action="" id="registration_form" name="registration_form" method="POST">
			<input type="hidden" id="_action_" name="_action_" value="TRUE">
			
			<label for="fname">Ime *</label>
			<input type="text" id="fname" name="firstname" placeholder="Ime..." required>

			<label for="lname">Prezime *</label>
			<input type="text" id="lname" name="lastname" placeholder="Prezime..." required>
				
			<label for="email">E-mail *</label>
			<input type="email" id="email" name="email" placeholder="Vaš e-mail..." required>
			
			<label for="username">Username:* <small>(Username must have min 5 and max 10 char)</small></label>
			<input type="text" id="username" name="username" pattern=".{5,10}" placeholder="Username.." required><br>
			
									
			<label for="password">Lozinka:* <small>(Lozinka mora imati barem 4 znaka)</small></label>
			<input type="password" id="password" name="password" placeholder="Lozinka..." pattern=".{4,}" required>

			<label for="country">Country:</label>
			<select name="country" id="country">
				<option value="">Odaberi državu</option>';
				#Select all countries from database webprog, table countries
				$query  = "SELECT * FROM countries";
				$result = @mysqli_query($MySQL, $query);
				while($row = @mysqli_fetch_array($result)) {
					print '<option value="' . $row['country_code'] . '">' . $row['country_name'] . '</option>';
				}
			print '
			</select>

			<input type="submit" value="Submit">
		</form>';
	}
	else if ($_POST['_action_'] == TRUE) {
		
        $query  = "SELECT * FROM users";
        $query .= " WHERE email=? OR username=?";
        $stmt = mysqli_prepare($MySQL, $query);
        mysqli_stmt_bind_param($stmt, "ss", $_POST['email'], $_POST['username']);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
		if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
            if ($row) {
                if ($row['email'] == $_POST['email']) {
                    echo '<p class = "message">Email već postoji. </p>';
                }
                if ($row['username'] == $_POST['username']) {
                    echo '<p class = "message">Korisničko ime već postoji. </p>';
                }
            }
        } else {
            $pass_hash = password_hash($_POST['password'], PASSWORD_DEFAULT);
            
            $query  = "INSERT INTO users (firstname, lastname, email, username, password, country)";
            $query .= " VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = mysqli_prepare($MySQL, $query);
            mysqli_stmt_bind_param($stmt, "ssssss", $_POST['firstname'], $_POST['lastname'], $_POST['email'], $_POST['username'], $pass_hash, $_POST['country']);
            $result = mysqli_stmt_execute($stmt);
            
            if ($result) {
                echo '<p class = "message">Registracija uspješna! </p>';
            } else {
                echo '<p class = "message">Došlo je do pogreške prilikom registracije. </p>';
            }
        }
	}
	print '
	</div>';
?>