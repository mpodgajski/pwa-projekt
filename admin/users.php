<?php 
	
	if (isset($_POST['edit']) && $_POST['_action_'] == 'TRUE') {
		$query  = "UPDATE users SET firstname=?, lastname=?, email=?, username=?, country=?, archive=?";
		$query .= " WHERE id=?";
		$stmt = mysqli_prepare($MySQL, $query);
		mysqli_stmt_bind_param($stmt, "ssssssi", $_POST['firstname'], $_POST['lastname'], $_POST['email'], $_POST['username'], $_POST['country'], $_POST['archive'], $_POST['edit']);
		$result = mysqli_stmt_execute($stmt);

		@mysqli_close($MySQL);
		
		$_SESSION['message'] = '<p class = "message">You successfully changed user profile!</p>';
		
		
		header("Location: index.php?menu=7&action=1");
	}
	
	
	if (isset($_GET['delete']) && $_GET['delete'] != '') {
	
		$query  = "DELETE FROM users";
		$query .= " WHERE id=?";
		$query .= " LIMIT 1";
		$stmt = mysqli_prepare($MySQL, $query);
		mysqli_stmt_bind_param($stmt, "i", $_GET['delete']);
		$result = mysqli_stmt_execute($stmt);

		$_SESSION['message'] = '<p class = "message">You successfully deleted user profile!</p>';
		
		
		header("Location: index.php?menu=7&action=1");
	}
	

	if (isset($_GET['id']) && $_GET['id'] != '') {
		$query  = "SELECT * FROM users";
		$query .= " WHERE id=?";
		$stmt = mysqli_prepare($MySQL, $query);
		mysqli_stmt_bind_param($stmt, "i", $_GET['id']);
		mysqli_stmt_execute($stmt);
		$result = mysqli_stmt_get_result($stmt);
		$row = mysqli_fetch_array($result);
		print '
		<div class=complaints>
		<h2>User profile</h2>
		<p><b>First name:</b> ' . $row['firstname'] . '</p>
		<p><b>Last name:</b> ' . $row['lastname'] . '</p>
		<p><b>Username:</b> ' . $row['username'] . '</p>';
		$query  = "SELECT * FROM countries";
		$query .= " WHERE country_code=?";
		$stmt = mysqli_prepare($MySQL, $query);
		mysqli_stmt_bind_param($stmt, "s", $row['country']);
		mysqli_stmt_execute($stmt);
		$result = mysqli_stmt_get_result($stmt);
		$_row = mysqli_fetch_array($result);
		print '
		<p><b>Country:</b> ' .$_row['country_name'] . '</p>
		<p><b>Date:</b> ' . pickerDateToMysql($row['date']) . '</p>
		<p><a href="index.php?menu='.$menu.'&amp;action='.$action.'">Back</a></p>
		</div>';
	}
	else if (isset($_GET['edit']) && $_GET['edit'] != '') {
		$query  = "SELECT * FROM users";
		$query .= " WHERE id=?";
		$stmt = mysqli_prepare($MySQL, $query);
		mysqli_stmt_bind_param($stmt, "i", $_GET['edit']);
		mysqli_stmt_execute($stmt);
		$result = mysqli_stmt_get_result($stmt);
		$row = mysqli_fetch_array($result);
		$checked_archive = false;
		
		print '
		<div class=edit_complaint>
		<h2>Edit user profile</h2>
		<form action="" id="registration_form" name="registration_form" method="POST">
			<input type="hidden" id="_action_" name="_action_" value="TRUE">
			<input type="hidden" id="edit" name="edit" value="' . $_GET['edit'] . '">
			
			<label for="fname">First Name *</label>
			<input type="text" id="fname" name="firstname" value="' . $row['firstname'] . '" placeholder="Your name.." required>

			<label for="lname">Last Name *</label>
			<input type="text" id="lname" name="lastname" value="' . $row['lastname'] . '" placeholder="Your last natme.." required>
				
			<label for="email">Your E-mail *</label>
			<input type="email" id="email" name="email"  value="' . $row['email'] . '" placeholder="Your e-mail.." required>
			
			<label for="username">Username *<small>(Username must have min 5 and max 10 char)</small></label>
			<input type="text" id="username" name="username" value="' . $row['username'] . '" pattern=".{5,10}" placeholder="Username.." required><br>
			
			<label for="country">Country</label>
			<select name="country" id="country">
				<option value="">molimo odaberite</option>';
				$query  = "SELECT * FROM countries";
				$stmt = mysqli_prepare($MySQL, $query);
				mysqli_stmt_execute($stmt);
				$result = mysqli_stmt_get_result($stmt);
				while($_row = mysqli_fetch_array($result)) {
					print '<option value="' . $_row['country_code'] . '"';
					if ($row['country'] == $_row['country_code']) { print ' selected'; }
					print '>' . $_row['country_name'] . '</option>';
				}
			print '
			</select>
			
			<label for="archive">Archive:</label><br />
            <input type="radio" name="archive" value="Y"'; if($row['archive'] == 'Y') { echo ' checked="checked"'; $checked_archive = true; } echo ' /> YES &nbsp;&nbsp;
			<input type="radio" name="archive" value="N"'; if($checked_archive == false) { echo ' checked="checked"'; } echo ' /> NO
			
			<hr>
			
			<input type="submit" value="Submit">
		</form>
		<p><a href="index.php?menu='.$menu.'&amp;action='.$action.'">Back</a></p>
		</div>';
	}
	else {
		print '
		<div id="users">
		<h2>List of users</h2>
		
			<table>
				<thead>
					<tr>
						<th width="16"></th>
						<th width="16"></th>
						<th width="16"></th>
						<th>First name</th>
						<th>Last name</th>
						<th>E mail</th>
						<th>Država</th>
						<th width="16"></th>
					</tr>
				</thead>
				<tbody>';
				$query  = "SELECT * FROM users";
				$stmt = mysqli_prepare($MySQL, $query);
				mysqli_stmt_execute($stmt);
				$result = mysqli_stmt_get_result($stmt);
				while($row = mysqli_fetch_array($result)) {
					print '
					<tr>
						<td><a href="index.php?menu='.$menu.'&amp;action='.$action.'&amp;id=' .$row['id']. '"><img src="imgs/eye.png" alt="user"></a></td>
						<td><a href="index.php?menu='.$menu.'&amp;action='.$action.'&amp;edit=' .$row['id']. '"><img src="imgs/edit.png" alt="edit"></a></td>
						<td><a href="index.php?menu='.$menu.'&amp;action='.$action.'&amp;delete=' .$row['id']. '"><img src="imgs/trash.png" alt="delete"></a></td>
						<td><strong>' . $row['firstname'] . '</strong></td>
						<td><strong>' . $row['lastname'] . '</strong></td>
						<td>' . $row['email'] . '</td>
						<td>';
							$_query  = "SELECT * FROM countries";
							$_query .= " WHERE country_code=?";
							$_stmt = mysqli_prepare($MySQL, $_query);
							mysqli_stmt_bind_param($_stmt, "s", $row['country']);
							mysqli_stmt_execute($_stmt);
							$_result = mysqli_stmt_get_result($_stmt);
							$_row = mysqli_fetch_array($_result);
							print $_row['country_name'] . '
						</td>
						<td>';
							if ($row['archive'] == 'Y') { print '<img src="imgs/inactive.png" alt="archived" title="" />'; }
                            else if ($row['archive'] == 'N') { print '<img src="imgs/active.png" alt="active" title="" />'; }
						print '
						</td>
					</tr>';
				}
			print '
				</tbody>
			</table>
		</div>';
	}
	
	@mysqli_close($MySQL);
?>