<?php 
	
	if (isset($_POST['_action_']) && $_POST['_action_'] == 'add_complaints') {
		$_SESSION['message'] = '';

		$query  = "INSERT INTO complaints (title, description)";
		$query .= " VALUES (?, ?)";
		$stmt = mysqli_prepare($MySQL, $query);
		mysqli_stmt_bind_param($stmt, 'ss', $_POST['title'], $_POST['description']);
		$result = mysqli_stmt_execute($stmt);
		
		$ID = mysqli_insert_id($MySQL);
		
        if($_FILES['picture']['error'] == UPLOAD_ERR_OK && $_FILES['picture']['name'] != "") {
                
			$ext = strtolower(strrchr($_FILES['picture']['name'], "."));
			
            $_picture = $ID . '-' . rand(1,100) . $ext;
			copy($_FILES['picture']['tmp_name'], "complaint_images/".$_picture);
			
			if ($ext == '.jpg' || $ext == '.png' || $ext == '.gif') {
				$_query  = "UPDATE complaints SET picture=?";
				$_query .= " WHERE id=? LIMIT 1";
				$_stmt = mysqli_prepare($MySQL, $_query);
				mysqli_stmt_bind_param($_stmt, 'si', $_picture, $ID);
				$_result = mysqli_stmt_execute($_stmt);
				$_SESSION['message'] .= '<p class = "message">You successfully added picture.</p>';
			}
        }
		
		
		$_SESSION['message'] .= '<p class = "message">You successfully added complaints!</p>';

		header("Location: index.php?menu=7&action=2");
	}
	
	if (isset($_POST['_action_']) && $_POST['_action_'] == 'edit_complaints') {
		$stmt = mysqli_prepare($MySQL, "UPDATE complaints SET title=?, description=?, consider=? WHERE id=? LIMIT 1");
		mysqli_stmt_bind_param($stmt, 'sssi', $_POST['title'], $_POST['description'], $_POST['consider'], $_POST['edit']);
		$result = mysqli_stmt_execute($stmt);
		
        if($_FILES['picture']['error'] == UPLOAD_ERR_OK && $_FILES['picture']['name'] != "") {
                
			$ext = strtolower(strrchr($_FILES['picture']['name'], "."));
            
			$_picture = (int)$_POST['edit'] . '-' . rand(1,100) . $ext;
			copy($_FILES['picture']['tmp_name'], "complaint_images/".$_picture);
			
			
			if ($ext == '.jpg' || $ext == '.png' || $ext == '.gif') { # test if format is picture
				$_query  = "UPDATE complaints SET picture=?";
				$_query .= " WHERE id=? LIMIT 1";
				$_stmt = mysqli_prepare($MySQL, $_query);
				mysqli_stmt_bind_param($_stmt, 'si', $_picture, $_POST['edit']);
				$_result = mysqli_stmt_execute($_stmt);
				$_SESSION['message'] .= '<p>You successfully added picture.</p>';
			}
        }
		
		$_SESSION['message'] = '<p class = "message">You successfully changed complaints!</p>';
		
		
		header("Location: index.php?menu=7&action=2");
	}

	if (isset($_GET['delete']) && $_GET['delete'] != '') {
		
		$stmt = mysqli_prepare($MySQL, "SELECT picture FROM complaints WHERE id=? LIMIT 1");
		mysqli_stmt_bind_param($stmt, 'i', $_GET['delete']);
		mysqli_stmt_execute($stmt);
		$result = mysqli_stmt_get_result($stmt);
		$row = mysqli_fetch_array($result);
		unlink("complaint_images/".$row['picture']);

		$stmt = mysqli_prepare($MySQL, "DELETE FROM complaints WHERE id=? LIMIT 1");
		mysqli_stmt_bind_param($stmt, 'i', $_GET['delete']);
		mysqli_stmt_execute($stmt);

		$_SESSION['message'] = '<p class = "message">You successfully deleted complaints!</p>';
		header("Location: index.php?menu=7&action=2");
	}
	
	
	if (isset($_GET['id']) && $_GET['id'] != '') {
		$stmt = mysqli_prepare($MySQL, "SELECT * FROM complaints WHERE id=? ORDER BY date DESC");
		mysqli_stmt_bind_param($stmt, 'i', $_GET['id']);
		mysqli_stmt_execute($stmt);
		$result = mysqli_stmt_get_result($stmt);
		$row = mysqli_fetch_array($result);
		print '
		<div class="complaints">
		<h2>' . $row['title'] . '</h2>
		' . $row['description'] . '
		<time datetime="' . $row['date'] . '">' . pickerDateToMysql($row['date']) . '</time>
		<img src="complaint_images/' . $row['picture'] . '" alt="' . $row['title'] . '" title="' . $row['title'] . '">
		<p><a class="AddLink" href="index.php?menu='.$menu.'&amp;action='.$action.'">Back</a></p>
		<hr>
		
		</div>
		';
	}
	
	else if (isset($_GET['add']) && $_GET['add'] != '') {
		
		print '
		<div class="edit_complaint">
		<h2>Add complaint</h2>
		<form action="" id="complaints_form" name="complaints_form" method="POST" enctype="multipart/form-data">
			<input type="hidden" id="_action_" name="_action_" value="add_complaints">
			
			<label for="title">Title *</label>
			<input type="text" id="title" name="title" placeholder="Complaint title.." required>

			<label for="description">Description *</label>
			<textarea id="description" name="description" placeholder="Complaint description.." required></textarea>
				
			<label for="picture">Picture</label>
			<input type="file" id="picture" name="picture">
						
			<label for="consider">Take into consideration?: </label><br />
            <input type="radio" name="consider" value="Y" disabled> <s>YES</s> &nbsp;&nbsp;
			<input type="radio" name="consider" value="N" checked> NO
			
			<hr>
			
			<input type="submit" value="Submit">
		</form>
		<p><a href="index.php?menu='.$menu.'&amp;action='.$action.'">Back</a></p>
		</div>';
	}
	else if (isset($_GET['edit']) && $_GET['edit'] != '') {
		$stmt = mysqli_prepare($MySQL, "SELECT * FROM complaints WHERE id=?");
		mysqli_stmt_bind_param($stmt, 'i', $_GET['edit']);
		mysqli_stmt_execute($stmt);
		$result = mysqli_stmt_get_result($stmt);
		$row = mysqli_fetch_array($result);
		$checked_consider = false;

		print '
		<div class="edit_complaint">
		<h2>Edit complaints</h2>
		<form action="" id="complaints_form_edit" name="complaints_form_edit" method="POST" enctype="multipart/form-data">
			<input type="hidden" id="_action_" name="_action_" value="edit_complaints">
			<input type="hidden" id="edit" name="edit" value="' . $row['id'] . '">
			
			<label for="title">Title *</label>
			<input type="text" id="title" name="title" value="' . $row['title'] . '" placeholder="complaints title.." required>

			<label for="description">Description *</label>
			<textarea id="description" name="description" placeholder="complaints description.." required>' . $row['description'] . '</textarea>
				
			<label for="picture">Picture</label>
			<input type="file" id="picture" name="picture">
						
			<label for="consider">Take into consideration?: </label><br />
            <input type="radio" name="consider" value="Y" disabled'; if($row['consider'] == 'Y') { echo ' checked="checked"'; $checked_consider = true; } echo ' /> <s>YES</s> &nbsp;&nbsp;
			<input type="radio" name="consider" value="N"'; if($checked_consider == false) { echo ' checked="checked"'; } echo ' /> NO
			
			<hr>
			
			<input type="submit" value="Submit">
		</form>
		<p><a href="index.php?menu='.$menu.'&amp;action='.$action.'">Back</a></p></div>';
	}
	else {
		print '
		<div id="complaints">
		<h2>Žalbe</h2>
		
			<table>
				<thead>
					<tr>
						<th width="16"></th>
						<th width="16"></th>
						<th width="16"></th>
						<th>Title</th>
						<th>Description</th>
						<th>Date</th>
						<th width="16"></th>
					</tr>
				</thead>
				<tbody>';
				$query  = "SELECT * FROM complaints";
				$query .= " ORDER BY date DESC";
				$stmt = mysqli_prepare($MySQL, $query);
				mysqli_stmt_execute($stmt);
				$result = mysqli_stmt_get_result($stmt);
				while($row = @mysqli_fetch_array($result)) {
					print '
					<tr>
						<td><a href="index.php?menu='.$menu.'&amp;action='.$action.'&amp;id=' .$row['id']. '"><img src="imgs/eye.png" alt="user"></a></td>
						<td><a href="index.php?menu='.$menu.'&amp;action='.$action.'&amp;edit=' .$row['id']. '"><img src="imgs/edit.png" alt="uredi"></a></td>
						<td><a href="index.php?menu='.$menu.'&amp;action='.$action.'&amp;delete=' .$row['id']. '"><img src="imgs/trash.png" alt="obriši"></a></td>
						<td>' . $row['title'] . '</td>
						<td>';
						if(strlen($row['description']) > 160) {
                            echo substr(strip_tags($row['description']), 0, 160).'...';
                        } else {
                            echo strip_tags($row['description']);
                        }
						print '
						</td>
						<td>' . pickerDateToMysql($row['date']) . '</td>
						<td>';
							if ($row['consider'] == 'N') { print '<img src="imgs/inactive.png" alt="" title="" />'; }
                            else if ($row['consider'] == 'Y') { print '<img src="imgs/active.png" alt="" title="" />'; }
						print '
						</td>
					</tr>';
				}
			print '
				</tbody>
			</table>
			<a href="index.php?menu=' . $menu . '&amp;action=' . $action . '&amp;add=true" class="AddLink">Add new complaint</a>
		</div> <br><br>';
	}
	
	# Close MySQL connection
	@mysqli_close($MySQL);
?>